<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kebijakan Privasi | Fine Nectar</title>
        <meta name="description" content="Kebijakan privasi Fine Nectar terkait pengelolaan data pelanggan.">

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
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Kebijakan Privasi</h1>
                <p class="mt-2 text-sm text-zinc-600">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>

                <div class="mt-6 space-y-5 text-sm leading-relaxed text-zinc-700">
                    <section>
                        <h2 class="text-base font-bold text-zinc-900">1. Data yang Kami Kumpulkan</h2>
                        <p>Kami mengumpulkan data yang Anda isi saat checkout, seperti nama, nomor WhatsApp, alamat pengiriman, jumlah pesanan, dan metode pembayaran.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">2. Tujuan Penggunaan Data</h2>
                        <p>Data digunakan untuk memproses transaksi, konfirmasi pesanan, pengiriman produk, layanan pelanggan, serta kebutuhan administrasi transaksi.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">3. Pembayaran Pihak Ketiga</h2>
                        <p>Untuk pembayaran QRIS, data transaksi tertentu diproses melalui penyedia pembayaran Tripay sesuai kebijakan privasi dan ketentuan layanan pihak tersebut.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">4. Perlindungan Data</h2>
                        <p>Kami berupaya menjaga keamanan data pelanggan dan membatasi akses data hanya untuk keperluan operasional yang sah.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">5. Penyimpanan & Penghapusan Data</h2>
                        <p>Data disimpan selama diperlukan untuk proses transaksi dan layanan pelanggan. Anda dapat mengajukan permintaan penghapusan data melalui customer service.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">6. Hak Pelanggan</h2>
                        <p>Anda berhak meminta klarifikasi atas penggunaan data, melakukan koreksi data yang tidak akurat, serta menghubungi kami untuk pertanyaan privasi.</p>
                    </section>

                    <section>
                        <h2 class="text-base font-bold text-zinc-900">7. Kontak</h2>
                        <p>Jika ada pertanyaan terkait kebijakan privasi, silakan hubungi customer service Fine Nectar melalui WhatsApp 0858-5971-4058.</p>
                    </section>
                </div>
            </div>
        </main>
    </body>
</html>
