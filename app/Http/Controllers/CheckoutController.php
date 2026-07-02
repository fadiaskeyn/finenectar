<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\WhatsAppNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(private readonly WhatsAppNotificationService $whatsAppNotifications)
    {
    }

    public function index()
    {
        $orders = Order::query()->latest()->paginate(15);

        return view('admin.orders', compact('orders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $turnstileEnabled = filled(config('services.turnstile.site_key')) && filled(config('services.turnstile.secret_key'));

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:500'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'payment_method' => ['required', 'in:cod,qris'],
            'cf-turnstile-response' => $turnstileEnabled ? ['required', 'string'] : ['nullable', 'string'],
        ], [
            'cf-turnstile-response.required' => 'Selesaikan verifikasi keamanan dulu ya.',
            'product_id.required' => 'Pilih produk dulu ya.',
        ]);

        if ($turnstileEnabled && ! $this->verifyTurnstileToken($request)) {
            return back()
                ->withInput()
                ->withErrors([
                    'cf-turnstile-response' => 'Verifikasi keamanan gagal. Coba lagi.',
                ]);
        }

        $product = Product::query()->whereKey($validated['product_id'])->first();

        if (! $product) {
            return back()
                ->withInput()
                ->withErrors(['product_id' => 'Produk tidak tersedia. Pilih produk lain dulu ya.']);
        }

        $quantity = (int) $validated['quantity'];
        $subtotalAmount = $product->price * $quantity;
        $shippingAmount = $product->is_free_shipping ? 0 : 0;
        $totalAmount = $subtotalAmount + $shippingAmount;

        $existingPendingPaymentOrder = Order::query()
            ->where('status', 'waiting_payment')
            ->where('customer_phone', $validated['customer_phone'])
            ->latest()
            ->first();

        if ($existingPendingPaymentOrder) {
            $activePaymentLink = $existingPendingPaymentOrder->tripay_checkout_url ?: $existingPendingPaymentOrder->tripay_qr_url;

            if ($activePaymentLink) {
                return redirect()->away($activePaymentLink);
            }

            return redirect('/#order')->with('error', 'Nomor WhatsApp ini masih punya transaksi pembayaran yang belum selesai. Selesaikan dulu sebelum membuat order baru.');
        }

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'product_id' => $product->id,
            'product_name' => $product->name,
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'subtotal_amount' => $subtotalAmount,
            'shipping_amount' => $shippingAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] === 'cod' ? 'waiting_cod' : 'waiting_payment',
            'merchant_ref' => 'FN-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
        ]);

        if ($validated['payment_method'] === 'cod') {
            $this->whatsAppNotifications->sendOrderCreated($order);

            return redirect('/#order')->with('success', 'Order COD berhasil dibuat. Tim kami akan menghubungi kamu untuk konfirmasi.');
        }

        $apiKey = trim((string) config('services.tripay.api_key'));
        $privateKey = trim((string) config('services.tripay.private_key'));
        $merchantCode = trim((string) config('services.tripay.merchant_code'));
        $channelCode = trim((string) config('services.tripay.qris_channel', 'QRIS2'));
        $configuredBaseUrl = trim((string) config('services.tripay.base_url', 'https://tripay.co.id/api-sandbox'));
        $baseUrl = rtrim($configuredBaseUrl !== '' ? $configuredBaseUrl : 'https://tripay.co.id/api-sandbox', '/');

        if (! $apiKey || ! $privateKey || ! $merchantCode) {
            return redirect('/#order')->with('error', 'Konfigurasi Tripay belum lengkap. Isi TRIPAY_API_KEY, TRIPAY_PRIVATE_KEY, dan TRIPAY_MERCHANT_CODE di .env.');
        }

        $signatureSource = $merchantCode . $order->merchant_ref . (int) $totalAmount;
        $signature = hash_hmac('sha256', $signatureSource, $privateKey);

        $payload = [
            'method' => $channelCode,
            'merchant_ref' => $order->merchant_ref,
            'amount' => $totalAmount,
            'customer_name' => $order->customer_name,
            'customer_email' => 'customer+' . $order->id . '@finenectar.local',
            'customer_phone' => $order->customer_phone,
            'order_items' => [
                [
                    'sku' => 'FN-' . $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'product_url' => route('products.show', $product),
                ],
            ],
            'callback_url' => route('payments.tripay.callback'),
            'return_url' => url('/#order'),
            'expired_time' => now()->addHours(24)->timestamp,
            'signature' => $signature,
        ];

        try {
            $response = Http::withToken($apiKey)
                ->acceptJson()
                ->post($baseUrl . '/transaction/create', $payload);

            if (! $response->successful() || ! data_get($response->json(), 'success')) {
                $order->update([
                    'status' => 'tripay_failed',
                    'tripay_response' => $response->json(),
                ]);

                if (config('app.debug')) {
                    dd([
                        'message' => 'Gagal membuat transaksi QRIS ke Tripay',
                        'http_status' => $response->status(),
                        'response' => $response->json(),
                        'payload' => $payload,
                        'tripay_runtime' => [
                            'base_url' => $baseUrl,
                            'merchant_code' => $merchantCode,
                            'channel' => $channelCode,
                            'api_key_prefix' => substr($apiKey, 0, 4),
                            'private_key_prefix' => substr($privateKey, 0, 4),
                            'signature_source' => $signatureSource,
                        ],
                    ]);
                }

                return redirect('/#order')->with('error', 'Gagal membuat transaksi QRIS. Coba lagi beberapa saat.');
            }

            $tripayData = data_get($response->json(), 'data', []);

            $order->update([
                'status' => 'waiting_payment',
                'tripay_reference' => data_get($tripayData, 'reference'),
                'tripay_checkout_url' => data_get($tripayData, 'checkout_url'),
                'tripay_qr_url' => data_get($tripayData, 'qr_url') ?: data_get($tripayData, 'qr_string'),
                'tripay_response' => $response->json(),
            ]);

            $this->whatsAppNotifications->sendOrderCreated($order);

            $checkoutUrl = data_get($tripayData, 'checkout_url');
            if ($checkoutUrl) {
                return redirect()->away($checkoutUrl);
            }

            return redirect('/#order')->with('success', 'Transaksi QRIS berhasil dibuat. Cek detail pembayaran pada dashboard Tripay.');
        } catch (Throwable $exception) {
            $order->update([
                'status' => 'tripay_exception',
                'tripay_response' => [
                    'message' => $exception->getMessage(),
                ],
            ]);

            if (config('app.debug')) {
                dd([
                    'message' => 'Exception saat membuat transaksi QRIS',
                    'error' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'payload' => $payload,
                ]);
            }

            Log::error('Tripay QRIS transaction error', [
                'order_id' => $order->id,
                'exception' => $exception,
            ]);

            return redirect('/#order')->with('error', 'Gagal membuat transaksi QRIS. Coba lagi beberapa saat.');
        }
    }

    private function formatRupiah(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }

    private function verifyTurnstileToken(Request $request): bool
    {
        $secretKey = trim((string) config('services.turnstile.secret_key'));
        $token = trim((string) $request->input('cf-turnstile-response'));

        if (! $secretKey || ! $token) {
            return false;
        }

        try {
            $response = Http::asForm()
                ->acceptJson()
                ->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secretKey,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);

            if (! $response->successful()) {
                return false;
            }

            return (bool) data_get($response->json(), 'success', false);
        } catch (Throwable $exception) {
            Log::warning('Turnstile verification error', [
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function callback(Request $request): JsonResponse
    {
        $privateKey = config('services.tripay.private_key');
        $signatureHeader = $request->header('X-Callback-Signature');
        $event = $request->header('X-Callback-Event');
        $rawBody = $request->getContent();

        if (! $privateKey || ! $signatureHeader) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $expectedSignature = hash_hmac('sha256', $rawBody, $privateKey);
        if (! hash_equals($expectedSignature, $signatureHeader)) {
            return response()->json(['success' => false, 'message' => 'Invalid signature'], 401);
        }

        if ($event !== 'payment_status') {
            return response()->json(['success' => false, 'message' => 'Invalid callback event'], 400);
        }

        $payload = $request->all();
        $merchantRef = data_get($payload, 'merchant_ref');
        $tripayReference = data_get($payload, 'reference');
        $paymentStatus = strtolower((string) data_get($payload, 'status', ''));

        if (! $merchantRef && ! $tripayReference) {
            return response()->json(['success' => false, 'message' => 'Missing reference'], 400);
        }

        $order = Order::query()
            ->where(function ($query) use ($tripayReference, $merchantRef) {
                if ($tripayReference) {
                    $query->where('tripay_reference', $tripayReference);
                }

                if ($merchantRef) {
                    $query->orWhere('merchant_ref', $merchantRef);
                }
            })
            ->latest()
            ->first();

        if (! $order) {
            Log::warning('Tripay callback order not found', [
                'merchant_ref' => $merchantRef,
                'reference' => $tripayReference,
            ]);

            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $mappedStatus = match ($paymentStatus) {
            'paid' => 'paid',
            'expired' => 'expired',
            'failed', 'refund' => 'failed',
            default => 'waiting_payment',
        };

        $order->update([
            'status' => $mappedStatus,
            'tripay_response' => $payload,
        ]);

        if ($mappedStatus === 'paid') {
            $this->whatsAppNotifications->sendPaymentPaid($order);
        }

        return response()->json(['success' => true]);
    }
}
