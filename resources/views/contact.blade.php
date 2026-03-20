<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kontak Customer Service | Fine Nectar</title>
        <meta name="description" content="Kontak customer service Fine Nectar untuk pertanyaan produk dan pesanan.">

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
                <h1 class="mt-2 text-3xl font-extrabold tracking-tight">Kontak Customer Service</h1>
                <p class="mt-3 text-sm text-zinc-600">Kami siap bantu pertanyaan terkait produk, pembayaran, dan status pengiriman.</p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-zinc-500">WhatsApp</p>
                        <p class="mt-1 text-base font-bold text-zinc-900">0858-5971-4058</p>
                        <a class="mt-3 inline-flex rounded-full bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-700" href="https://wa.me/6285859714058?text=Halo%2C+saya+ingin+bertanya+tentang+Fine+Nectar" target="_blank">Chat Sekarang</a>
                    </div>

                    <div class="rounded-2xl bg-amber-50 p-4">
                        <p class="text-xs uppercase tracking-wide text-zinc-500">Alamat Operasional</p>
                        <p class="mt-1 text-base font-bold text-zinc-900">Jember, Jawa Timur</p>
                        <p class="mt-3 text-sm text-zinc-700">Jam layanan customer service: Senin - Sabtu, 08.00 - 20.00 WIB.</p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-4 text-sm text-zinc-700">
                    Untuk komplain pesanan, mohon sertakan nomor pesanan, nama pemesan, dan bukti foto/video agar proses penanganan lebih cepat.
                </div>
            </div>
        </main>
    </body>
</html>
