<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class CheckoutController extends Controller
{
    private const UNIT_PRICE = 35000;

    public function index()
    {
        $orders = Order::query()->latest()->paginate(15);

        return view('admin.orders', compact('orders'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_address' => ['required', 'string', 'max:500'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'payment_method' => ['required', 'in:cod,qris'],
        ]);

        $quantity = (int) $validated['quantity'];
        $totalAmount = self::UNIT_PRICE * $quantity;

        if ($validated['payment_method'] === 'qris') {
            $existingPendingQrisOrder = Order::query()
                ->where('payment_method', 'qris')
                ->where('status', 'waiting_payment')
                ->where('customer_phone', $validated['customer_phone'])
                ->where('total_amount', $totalAmount)
                ->where('created_at', '>=', now()->subDay())
                ->latest()
                ->first();

            if ($existingPendingQrisOrder) {
                $activePaymentLink = $existingPendingQrisOrder->tripay_checkout_url ?: $existingPendingQrisOrder->tripay_qr_url;

                if ($activePaymentLink) {
                    return redirect()->away($activePaymentLink);
                }

                return redirect('/#order')->with('error', 'Kamu masih punya transaksi QRIS aktif. Selesaikan pembayaran sebelumnya dulu ya.');
            }
        }

        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'customer_phone' => $validated['customer_phone'],
            'customer_address' => $validated['customer_address'],
            'quantity' => $quantity,
            'unit_price' => self::UNIT_PRICE,
            'total_amount' => $totalAmount,
            'payment_method' => $validated['payment_method'],
            'status' => $validated['payment_method'] === 'cod' ? 'waiting_cod' : 'waiting_payment',
            'merchant_ref' => 'FN-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
        ]);

        if ($validated['payment_method'] === 'cod') {
            return redirect('/#order')->with('success', 'Order COD berhasil dibuat. Tim kami akan menghubungi kamu untuk konfirmasi.');
        }

        $apiKey = config('services.tripay.api_key');
        $privateKey = config('services.tripay.private_key');
        $merchantCode = config('services.tripay.merchant_code');
        $channelCode = config('services.tripay.qris_channel', 'QRIS2');
        $baseUrl = rtrim(config('services.tripay.base_url', 'https://tripay.co.id/api-sandbox'), '/');

        if (! $apiKey || ! $privateKey || ! $merchantCode) {
            return redirect('/#order')->with('error', 'Konfigurasi Tripay belum lengkap. Isi TRIPAY_API_KEY, TRIPAY_PRIVATE_KEY, dan TRIPAY_MERCHANT_CODE di .env.');
        }

        $signature = hash_hmac('sha256', $merchantCode . $order->merchant_ref . $totalAmount, $privateKey);

        $payload = [
            'method' => $channelCode,
            'merchant_ref' => $order->merchant_ref,
            'amount' => $totalAmount,
            'customer_name' => $order->customer_name,
            'customer_email' => 'customer+' . $order->id . '@finenectar.local',
            'customer_phone' => $order->customer_phone,
            'order_items' => [
                [
                    'sku' => 'FN-35000',
                    'name' => 'Fine Nectar Honey',
                    'price' => self::UNIT_PRICE,
                    'quantity' => $quantity,
                    'product_url' => url('/'),
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

        return response()->json(['success' => true]);
    }
}
