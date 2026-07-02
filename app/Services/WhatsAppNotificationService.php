<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class WhatsAppNotificationService
{
    public function sendOrderCreated(Order $order): void
    {
        $productName = $order->product_name ?: 'Fine Nectar';
        $quantity = (int) $order->quantity;
        $unitPrice = (int) $order->unit_price;
        $subtotalAmount = (int) ($order->subtotal_amount ?: ($unitPrice * $quantity));
        $shippingAmount = (int) $order->shipping_amount;
        $totalAmount = (int) $order->total_amount;
        $paymentMethod = strtoupper((string) $order->payment_method);

        $this->sendMessage(
            $order,
            'Halo kak ' . $order->customer_name . ',' . PHP_EOL . PHP_EOL .
            'Konfirmasi, apakah benar pesanan Anda atas nama *' . $order->customer_name . '* untuk pembelian:' . PHP_EOL . PHP_EOL .
            '- ' . $productName . PHP_EOL .
            '  Jumlah: ' . $quantity . ' pcs' . PHP_EOL .
            '  Harga: ' . $this->formatRupiah($unitPrice) . ' / pcs' . PHP_EOL .
            '  Subtotal: ' . $this->formatRupiah($subtotalAmount) . PHP_EOL . PHP_EOL .
            'Total barang: ' . $quantity . ' pcs' . PHP_EOL .
            'Ongkir: ' . ($shippingAmount > 0 ? $this->formatRupiah($shippingAmount) : 'Free ongkir') . PHP_EOL .
            'Total harga: *' . $this->formatRupiah($totalAmount) . '*' . PHP_EOL .
            'Metode pembayaran: *' . $paymentMethod . '*' . PHP_EOL .
            'Alamat pengiriman: ' . $order->customer_address . PHP_EOL .
            'No. pesanan: ' . $order->merchant_ref . PHP_EOL . PHP_EOL .
            'Apakah data pesanan di atas sudah benar?' . PHP_EOL .
            'Balas *BENAR* jika sudah sesuai, atau *UBAH* jika ada yang perlu diperbaiki.' . PHP_EOL . PHP_EOL .
            'Terima kasih.'
        );
    }

    public function sendPaymentPaid(Order $order): void
    {
        $this->sendMessage(
            $order,
            'Pembayaran Fine Nectar sudah diterima.' . PHP_EOL . PHP_EOL .
            'Nama: ' . $order->customer_name . PHP_EOL .
            'Produk: ' . $order->product_name . PHP_EOL .
            'Jumlah: ' . $order->quantity . PHP_EOL .
            'Total: ' . $this->formatRupiah((int) $order->total_amount) . PHP_EOL .
            'No. Pesanan: ' . $order->merchant_ref . PHP_EOL . PHP_EOL .
            'Terima kasih, pesanan sedang kami proses.'
        );
    }

    private function sendMessage(Order $order, string $message): void
    {
        $phone = $this->normalizePhoneNumber($order->customer_phone);

        if ($phone === null) {
            Log::warning('WhatsApp notification skipped: invalid phone number', [
                'order_id' => $order->id,
                'phone' => $order->customer_phone,
            ]);

            return;
        }

        try {
            $script = base_path('whatsapp/send-message.mjs');

            if (! File::exists($script)) {
                throw new RuntimeException('Script pengirim WhatsApp tidak ditemukan.');
            }

            Log::debug('WhatsApp notification debug: starting send process', [
                'order_id' => $order->id,
                'phone' => $this->maskPhone($phone),
                'message_length' => strlen($message),
                'message_preview' => mb_substr($message, 0, 500),
                'script' => $script,
            ]);

            $process = Process::fromShellCommandline(
                sprintf('node %s', escapeshellarg($script)),
                base_path(),
                [
                    'WA_TO' => $phone,
                    'WA_MESSAGE' => $message,
                ]
            );

            $process->setTimeout(45);
            $process->run();

            if (! $process->isSuccessful()) {
                Log::debug('WhatsApp notification debug: send process failed', [
                    'order_id' => $order->id,
                    'phone' => $this->maskPhone($phone),
                    'exit_code' => $process->getExitCode(),
                    'output' => trim($process->getOutput()),
                    'error_output' => trim($process->getErrorOutput()),
                ]);

                throw new RuntimeException(trim($process->getErrorOutput() ?: $process->getOutput()) ?: 'Gagal mengirim notifikasi WhatsApp.');
            }

            Log::debug('WhatsApp notification debug: send process completed', [
                'order_id' => $order->id,
                'phone' => $this->maskPhone($phone),
                'output' => trim($process->getOutput()),
            ]);
        } catch (Throwable $exception) {
            Log::warning('WhatsApp notification failed', [
                'order_id' => $order->id,
                'phone' => $this->maskPhone($phone),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function normalizePhoneNumber(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        if (str_starts_with($digits, '8')) {
            $digits = '62' . $digits;
        }

        if (! str_starts_with($digits, '62')) {
            return null;
        }

        return $digits . '@s.whatsapp.net';
    }

    private function maskPhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $phone) ?: '';

        if (strlen($digits) <= 6) {
            return $digits;
        }

        return substr($digits, 0, 4) . str_repeat('*', max(strlen($digits) - 8, 0)) . substr($digits, -4);
    }

    private function formatRupiah(int $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
