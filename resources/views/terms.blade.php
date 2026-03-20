<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Syarat & Ketentuan | Fine Nectar</title>
        <meta name="description" content="Syarat dan ketentuan pembelian produk Fine Nectar.">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="icon" type="image/png" href="{{ asset('storage/ico.png') }}">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-amber-50 text-zinc-900 antialiased">
        <main class="mx-auto w-full max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
            <a href="{{ url('/') }}" class="inline-flex items-center rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 hover:border-zinc-900">← Kembali ke Beranda</a>

            <div class="mt-6 rounded-3xl bg-white p-6 ring-1 ring-zinc-200 sm:p-8">
                <p class="text-sm font-semibold text-amber-700">Fine Nectar</p>
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Syarat & Ketentuan</h1>
                <p class="mt-2 text-sm text-zinc-600">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

                <div class="mt-6 space-y-5 text-sm leading-relaxed text-zinc-700">
                    <section>
                        <h2 class="text-base font-bold text-zinc-900">1. Informasi Produk</h2>
                        <p>Produk yang dijual adalah Fine Nectar Honey dengan isi bersih 200ml per kemasan. Produk berupa madu alami untuk konsumsi harian.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">2. Pemesanan & Pembayaran</h2>
                        <p>Pemesanan dilakukan melalui form checkout di website. Metode pembayaran yang tersedia adalah QRIS dan COD sesuai opsi yang dipilih saat checkout.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">3. Pengiriman</h2>
                        <p>Pengiriman diproses setelah data pesanan dan pembayaran tervalidasi (untuk QRIS). Estimasi pengiriman menyesuaikan lokasi penerima dan jasa kirim yang digunakan.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">4. Pembatalan & Pengembalian</h2>
                        <p>Pembatalan dapat dilakukan sebelum pesanan diproses pengiriman. Untuk kendala produk saat diterima, pelanggan dapat menghubungi customer service maksimal 1x24 jam setelah barang diterima dengan menyertakan bukti foto/video.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">5. Data Pelanggan</h2>
                        <p>Data pelanggan digunakan hanya untuk proses pemesanan, pengiriman, dan komunikasi layanan. Fine Nectar berkomitmen menjaga kerahasiaan data pelanggan.</p>
                    </section>
                </div>
            </div>
        </main>
    </body>
</html>
