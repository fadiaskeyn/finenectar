<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fine Nectar | Madu Premium untuk Energi & Imunitas</title>
        <meta name="description" content="Fine Nectar adalah madu premium untuk gaya hidup aktif. Bantu jaga daya tahan tubuh, energi harian, dan kualitas istirahat.">

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
        <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-amber-200/40 via-orange-100/20 to-transparent"></div>

            <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <a href="#" class="inline-flex items-center gap-2">
                    <img
                        src="{{ asset('storage/ico.png') }}"
                        alt="Fine Nectar Icon"
                        class="h-9 w-9 rounded-xl object-cover"
                    >
                    <span class="text-lg font-extrabold tracking-tight">Fine Nectar</span>
                </a>
                <nav class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('terms') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Syarat & Ketentuan</a>
                    <a href="{{ route('contact') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Kontak CS</a>
                    <a href="#order" class="rounded-full bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700">Pesan Sekarang</a>
                </nav>
            </header>

            <main>
                @if (session('success'))
                    <div class="mx-auto mt-3 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mx-auto mt-3 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <section class="mx-auto grid w-full max-w-6xl items-center gap-10 px-4 pb-14 pt-6 sm:px-6 md:pt-8 lg:grid-cols-2 lg:gap-12 lg:px-8 lg:pb-20">
                    <div>
                        <span class="inline-flex rounded-full border border-amber-300 bg-white/70 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                            Natural Honey from the Heart of the Forest
                        </span>
                        <h1 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                            Boost Energi Harian Lo dengan
                            <span class="bg-gradient-to-r from-amber-500 to-orange-500 bg-clip-text text-transparent">Fine Nectar</span>
                        </h1>
                        <p class="mt-4 max-w-xl text-base text-zinc-700 sm:text-lg">
                            Madu murni premium Fine Nectar dengan isi bersih <span class="font-bold text-zinc-900">200ml per kemasan</span> untuk bantu jaga kesehatan tubuh, daya tahan, dan stamina saat aktivitas padat. Cocok buat rutinitas pagi, campuran minuman, dan camilan sehat harian.
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <a href="https://api.whatsapp.com/send/?phone=6285859714058&text=Halo%21+Saya+mau+menanyakan+Madu+Fine+Nectar+yang+Saya+lihat+dari+Website&type=phone_number&app_absent=0" class="inline-flex items-center justify-center rounded-full bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700">
                            Kirim pesan whatsapp
                            </a>
                            <a href="#manfaat" class="inline-flex items-center justify-center rounded-full border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-900 transition hover:border-zinc-900">
                                Lihat Manfaat
                            </a>
                        </div>

                        <div class="mt-8 grid grid-cols-3 gap-3 sm:gap-4">
                            <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-sm text-zinc-600">Murni</p>
                                <p class="mt-1 text-lg font-bold">100% Madu asli</p>
                            </div>
                            <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-sm text-zinc-600">Tanpa</p>
                                <p class="mt-1 text-lg font-bold">Pemanis tambahan</p>
                            </div>
                            <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-sm text-zinc-600">Memiliki rasa yang</p>
                                <p class="mt-1 text-lg font-bold">Unik alami</p>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -left-6 -top-6 h-28 w-28 rounded-full bg-amber-300/50 blur-2xl"></div>
                        <div class="absolute -bottom-8 -right-6 h-32 w-32 rounded-full bg-orange-300/40 blur-2xl"></div>

                        <div class="relative rounded-3xl bg-white p-3 shadow-xl ring-1 ring-zinc-200 sm:p-4">
                            @php
                                $productImage = file_exists(public_path('storage/finenectar.jpg'))
                                    ? asset('storage/finenectar.jpg')
                                    : asset('storage/fineneectar.jpg');

                                $product2 = file_exists(public_path('storage/fin2.jpg'))
                                    ? asset('storage/fin2.jpg')
                                    : asset('storage/fin2.jpg');
                            @endphp
                            <img
                                src="{{ $product2 }}"
                                alt="Produk Fine Nectar"
                                class="h-[360px] w-full rounded-2xl object-cover sm:h-[460px]"
                            >
                            <div class="mt-3 rounded-2xl bg-zinc-900 p-4 text-white">
                                <p class="text-xs uppercase tracking-wide text-zinc-300">Fine Nectar Signature</p>
                                <p class="mt-1 text-sm font-semibold">Isi bersih 200ml • Rasa unik natural tanpa bikin enek dikonsumsi rutin</p>
                                <p class="mt-2 text-lg font-extrabold text-amber-300">Harga launching: Rp35.000</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                    <div class="rounded-3xl bg-white p-6 ring-1 ring-zinc-200 sm:p-7">
                        <p class="text-sm font-semibold text-amber-700">Detail Produk Fine Nectar</p>
                        <h2 class="mt-2 text-2xl font-extrabold tracking-tight sm:text-3xl">Informasi produk yang Anda terima</h2>
                        <div class="mt-5 grid gap-3 text-sm text-zinc-700 sm:grid-cols-2">
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Nama Produk:</span> Fine Nectar Honey</div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Isi Bersih:</span> 200ml per kemasan</div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Komposisi:</span> 100% madu alami tanpa pemanis tambahan</div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Penyimpanan:</span> Simpan di suhu ruang dan tutup rapat setelah dibuka</div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Masa Simpan:</span> 12 bulan sejak tanggal produksi</div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3"><span class="font-semibold text-zinc-900">Kategori:</span> Produk konsumsi harian</div>
                        </div>
                    </div>
                </section>

                <section id="manfaat" class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                    <div class="mb-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-amber-700">Kenapa banyak orang pilih Fine Nectar?</p>
                            <h2 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Manfaat Madu untuk Kesehatan & Imunitas</h2>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                            <p class="text-2xl">🛡️</p>
                            <h3 class="mt-3 text-lg font-bold">Bantu Daya Tahan Tubuh</h3>
                            <p class="mt-2 text-sm text-zinc-600">Konsumsi madu rutin dapat membantu menjaga sistem imun agar tubuh lebih siap menghadapi aktivitas harian.</p>
                        </article>

                        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                            <p class="text-2xl">⚡</p>
                            <h3 class="mt-3 text-lg font-bold">Sumber Energi Alami</h3>
                            <p class="mt-2 text-sm text-zinc-600">Karbohidrat alami pada madu membantu isi ulang energi dengan cepat tanpa rasa “berat”.</p>
                        </article>

                        <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                            <p class="text-2xl">💛</p>
                            <h3 class="mt-3 text-lg font-bold">Teman Recovery Harian</h3>
                            <p class="mt-2 text-sm text-zinc-600">Cocok dikonsumsi setelah aktivitas padat untuk bantu tubuh kembali fit dan nyaman.</p>
                        </article>
                    </div>
                </section>

                <section class="mx-auto grid w-full max-w-6xl gap-6 px-4 pb-20 sm:px-6 lg:grid-cols-2 lg:px-8">
                    <div class="overflow-hidden rounded-3xl ring-1 ring-zinc-200">
                        <img
                            src="{{ $product2 }}"
                            alt="Madu alami"
                            class="h-64 w-full object-cover sm:h-full"
                        >
                    </div>
                    <div class="rounded-3xl bg-zinc-900 p-7 text-white sm:p-9">
                        <p class="text-xs uppercase tracking-widest text-zinc-300">Fine Nectar Daily Ritual</p>
                        <h3 class="mt-2 text-2xl font-extrabold leading-tight sm:text-3xl">1 sendok tiap pagi, biar badan tetap on sepanjang hari.</h3>
                        <ul class="mt-5 space-y-3 text-sm text-zinc-200 sm:text-base">
                            <li>• Pengganti gula sebagai pemanis alami tanpa meningkatkan kadar gula darah.</li>
                            <li>• Bisa jadi topping roti, oats, atau yogurt.</li>
                            <li>• Praktis dibawa untuk temen sekolah,kuliah, atau kerja</li>
                            <li>• Cocok untuk pre-workout atau post-workout.</li>
                            <li>• Bisa dikonsumsi langsung karena Nyaman di mulut dan perut tanpa bikin enek.</li>

                        </ul>
                        <a href="#order" class="mt-7 inline-flex rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-zinc-900 transition hover:bg-amber-300">
                            Order di harga Rp35K sekarang
                        </a>
                    </div>
                </section>

                <section id="order" class="mx-auto w-full max-w-6xl px-4 pb-20 sm:px-6 lg:px-8">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="rounded-3xl bg-white p-6 ring-1 ring-zinc-200 sm:p-7">
                            <p class="text-xs uppercase tracking-wide text-amber-700">Checkout Fine Nectar</p>
                            <h3 class="mt-2 text-2xl font-extrabold tracking-tight">Bayar pakai QRIS atau COD</h3>
                            <p class="mt-2 text-sm text-zinc-600">Harga per kemasan: <span class="font-bold text-zinc-900">Rp35.000</span></p>

                            <form action="{{ route('checkout.store') }}" method="POST" class="mt-6 space-y-4">
                                @csrf

                                <div>
                                    <label for="customer_name" class="mb-1 block text-sm font-semibold text-zinc-800">Nama lengkap</label>
                                    <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name') }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                                    @error('customer_name')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_phone" class="mb-1 block text-sm font-semibold text-zinc-800">Nomor WhatsApp</label>
                                    <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                                    @error('customer_phone')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="customer_address" class="mb-1 block text-sm font-semibold text-zinc-800">Alamat lengkap</label>
                                    <textarea id="customer_address" name="customer_address" rows="3" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">{{ old('customer_address') }}</textarea>
                                    @error('customer_address')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="quantity" class="mb-1 block text-sm font-semibold text-zinc-800">Jumlah Pcs</label>
                                        <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                                        @error('quantity')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="payment_method" class="mb-1 block text-sm font-semibold text-zinc-800">Metode pembayaran</label>
                                        <select id="payment_method" name="payment_method" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                                            <option value="qris" {{ old('payment_method') === 'qris' ? 'selected' : '' }}>QRIS</option>
                                            <option value="cod" {{ old('payment_method') === 'cod' ? 'selected' : '' }}>COD</option>
                                        </select>
                                        @error('payment_method')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-zinc-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-zinc-700">
                                    Buat Pesanan
                                </button>
                            </form>
                        </div>

                        <div class="rounded-3xl bg-amber-100/70 p-6 ring-1 ring-amber-200 sm:p-7">
                            <h4 class="text-lg font-extrabold">Cara kerja pembayaran</h4>
                            <ul class="mt-4 space-y-3 text-sm text-zinc-700">
                                <li>• <span class="font-semibold text-zinc-900">QRIS:</span> setelah klik "Buat Pesanan", kamu akan diarahkan ke halaman pembayaran Tripay untuk scan QR.</li>
                                <li>• <span class="font-semibold text-zinc-900">COD:</span> pesanan langsung masuk, lalu tim Fine Nectar hubungi kamu untuk konfirmasi pengiriman.</li>
                                <li>• Harga produk tetap <span class="font-semibold text-zinc-900">Rp35.000/kemasan</span> (belum termasuk ongkir).</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </main>

            <footer class="border-t border-zinc-200 bg-white/70">
                <div class="mx-auto w-full max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <p class="text-base font-semibold text-zinc-900 mb-2">TENTANG FINE NECTAR</p>
                            <p class="text-sm text-zinc-600">© {{ date('Y') }} Fine Nectar. Pure honey, pure energy. Made for modern healthy lifestyle.</p>
                        </div>
                        <div>
                            <p class="text-base font-semibold text-zinc-900 mb-2">INFORMASI PEMILIK BRAND</p>
                            <p class="text-sm text-zinc-600">
                                <span class="block">Fine Nectar</span>
                                <span class="block">Jember, Jawa Timur</span>
                                <span class="block font-medium text-zinc-900 mt-1">Hubungi: 085859714058</span>
                            </p>
                            <div class="mt-3 flex flex-col gap-1 text-sm">
                                <a href="{{ route('terms') }}" class="font-semibold text-zinc-800 hover:text-zinc-900">Lihat Syarat & Ketentuan</a>
                                <a href="{{ route('contact') }}" class="font-semibold text-zinc-800 hover:text-zinc-900">Kontak Customer Service</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
