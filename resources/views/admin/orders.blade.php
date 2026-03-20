<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order Admin | Fine Nectar</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-amber-50 text-zinc-900 antialiased">
        <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">Data Pesanan</h1>
                    <p class="text-sm text-zinc-600">Monitoring order QRIS dan COD Fine Nectar.</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-700">
                        Logout
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-zinc-50 text-left text-zinc-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Tanggal</th>
                                <th class="px-4 py-3 font-semibold">Nama</th>
                                <th class="px-4 py-3 font-semibold">Phone</th>
                                <th class="px-4 py-3 font-semibold">Qty</th>
                                <th class="px-4 py-3 font-semibold">Metode</th>
                                <th class="px-4 py-3 font-semibold">Total</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($orders as $order)
                                @php
                                    $paymentLink = $order->tripay_checkout_url ?: $order->tripay_qr_url;
                                    $waPhone = preg_replace('/\D+/', '', (string) $order->customer_phone);

                                    if (str_starts_with($waPhone, '0')) {
                                        $waPhone = '62' . substr($waPhone, 1);
                                    } elseif (str_starts_with($waPhone, '8')) {
                                        $waPhone = '62' . $waPhone;
                                    }

                                    $waMessage = 'hy kak ayo Fine Nectar nya di bayar sebelum di serepet orang lain,stocknya terus berkurang lohhh..';
                                    $waUrl = ($waPhone && $paymentLink)
                                        ? 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMessage . ' ' . $paymentLink)
                                        : null;
                                @endphp
                                <tr>
                                    <td class="px-4 py-3">{{ $order->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 font-semibold text-zinc-800">{{ $order->customer_name }}</td>
                                    <td class="px-4 py-3">{{ $order->customer_phone }}</td>
                                    <td class="px-4 py-3">{{ $order->quantity }}</td>
                                    <td class="px-4 py-3 uppercase">{{ $order->payment_method }}</td>
                                    <td class="px-4 py-3">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="rounded-full bg-zinc-100 px-3 py-1 text-xs font-semibold uppercase text-zinc-700">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            @if ($paymentLink)
                                                <a href="{{ $paymentLink }}" target="_blank" class="rounded-full bg-zinc-900 px-3 py-1 text-xs font-semibold text-white hover:bg-zinc-700">
                                                    Link Pembayaran
                                                </a>
                                            @endif

                                            @if ($waUrl)
                                                <a href="{{ $waUrl }}" target="_blank" class="rounded-full border border-zinc-300 bg-white px-3 py-1 text-xs font-semibold text-zinc-800 hover:border-zinc-900">
                                                    Reminder WA
                                                </a>
                                            @endif

                                            @if (! $paymentLink)
                                                <span class="text-xs text-zinc-400">-</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-zinc-500">Belum ada pesanan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-zinc-100 px-4 py-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </body>
</html>
