<!DOCTYPE html>
    <html class="scroll-smooth md:scroll-auto">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>Fine Nectar | Madu Premium untuk Tenang & Sehat</title>
            <meta name="description" content="Fine Nectar adalah madu premium untuk momen tenang sehari-hari. Dirancang untuk membantu tubuh terasa lebih rileks, nyaman, dan tetap sehat.">

            <link rel="preconnect" href="https://fonts.bunny.net">
            <link rel="icon" type="image/png" href="{{ asset('storage/ico.png') }}">
            <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

            @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
                @vite(['resources/css/app.css', 'resources/js/app.js'])
            @else
                <script src="https://cdn.tailwindcss.com"></script>
            @endif

            @if (config('services.turnstile.site_key'))
                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
            @endif

            @include('partials.flash-alert')

            <script>
                document.documentElement.classList.add('reveal-init');
            </script>

            <style>
                .reveal-init [data-reveal] {
                    opacity: 0;
                    transform: translateY(22px);
                    transition: opacity .6s ease, transform .6s ease;
                    will-change: opacity, transform;
                }

                .reveal-init [data-reveal].is-visible {
                    opacity: 1;
                    transform: translateY(0);
                }

                @media (prefers-reduced-motion: reduce) {
                    .reveal-init [data-reveal] {
                        opacity: 1;
                        transform: none;
                        transition: none;
                    }
                }
            </style>
        </head>
        <body class="bg-stone-50 text-zinc-900 antialiased">
            <div class="relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-amber-200/35 via-teal-100/20 to-transparent"></div>
                <header class="mx-auto flex w-full max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                    <nav class="flex items-center gap-3 sm:gap-4">
                        <a href="{{ route('terms') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Syarat & Ketentuan</a>
                        <a href="{{ route('privacy') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Kebijakan Privasi</a>
                        <a href="{{ route('products.index') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Produk</a>
                        <!-- <a href="{{ route('dev') }}" class="text-sm font-semibold text-amber-700 transition hover:text-amber-900">Dev</a> -->
                        <a href="{{ route('contact') }}" class="text-sm font-semibold text-zinc-700 transition hover:text-zinc-900">Kontak CS</a>
                        <a href="#order" class="rounded-full bg-zinc-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-zinc-700">Pesan Sekarang</a>
                    </nav>
                </header>

                <main>
                    @php
                        $checkoutProduct ??= null;
                        $checkoutProducts ??= collect();
                        $featuredProducts ??= collect();
                        $checkoutPrice = $checkoutProduct?->price ?? 35000;
                        $checkoutComparePrice = $checkoutProduct?->compare_at_price ?? 55000;
                        $checkoutProductName = $checkoutProduct?->name ?? 'Fine Nectar Honey';
                        $checkoutProductPayload = $checkoutProducts->mapWithKeys(fn ($product) => [
                            $product->id => [
                                'name' => $product->name,
                                'description' => $product->description,
                                'price' => $product->price,
                                'formatted_price' => $product->formattedPrice(),
                                'image_url' => $product->image_url,
                                'net_weight' => $product->net_weight,
                                'is_free_shipping' => $product->is_free_shipping,
                                'category' => $product->category,
                            ],
                        ]);
                    @endphp

                    @if (session('success'))
                        <div class="mx-auto mt-3 w-full max-w-6xl px-4 sm:px-6 lg:px-8">
                            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
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

                    <section data-reveal class="mx-auto grid w-full max-w-6xl items-center gap-10 px-4 pb-14 pt-6 sm:px-6 md:pt-8 lg:grid-cols-2 lg:gap-12 lg:px-8 lg:pb-20">
                        <div>
                            <span class="inline-flex rounded-full border border-amber-300 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-amber-700">
                                Ritual Tenang dari Alam
                            </span>
                            <h1 class="mt-4 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                                Biar hari terasa lebih ringan dan tenang dengan
                                <span class="bg-gradient-to-r from-orange-500 to-amber-400 bg-clip-text text-transparent">Fine Nectar</span>
                            </h1>
                            <p class="mt-4 max-w-xl text-base text-zinc-700 sm:text-lg">
                                Madu murni premium Fine Nectar dengan isi bersih <span class="font-bold text-zinc-900">200ml per kemasan</span> untuk menemani momen santai, bantu tubuh terasa lebih nyaman, dan jadi bagian dari rutinitas sehat harian. Cocok diminum saat pagi, sore, atau ketika butuh jeda sejenak.
                            </p>

                            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                                <a href="https://api.whatsapp.com/send/?phone=6285859714058&text=Halo%21+Saya+mau+menanyakan+Madu+Fine+Nectar+yang+Saya+lihat+dari+Website&type=phone_number&app_absent=0" class="inline-flex items-center justify-center rounded-full bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition hover:bg-zinc-700">
                                Kirim pesan whatsapp
                                </a>
                                <a href="#manfaat" class="inline-flex items-center justify-center rounded-full border border-zinc-300 bg-white px-6 py-3 text-sm font-semibold text-zinc-900 transition hover:border-zinc-900">
                                    Lihat Manfaat
                                </a>
                            </div>

                            <p class="mt-4 max-w-lg text-sm text-zinc-600">Fokus brand kami sekarang sederhana: bantu kamu lebih tenang dulu, lalu tetap rawat kesehatan dengan cara yang natural.</p>

                            <div class="mt-8 grid grid-cols-3 gap-3 sm:gap-4">
                                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                    <p class="text-sm text-zinc-600">Murni</p>
                                    <p class="mt-1 text-lg font-bold">100% Madu asli</p>
                                </div>
                                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                    <p class="text-sm text-zinc-600">Rasa</p>
                                    <p class="mt-1 text-lg font-bold">Lembut & nyaman</p>
                                </div>
                                <div class="rounded-2xl bg-white p-3 shadow-sm ring-1 ring-zinc-200">
                                    <p class="text-sm text-zinc-600">Cocok untuk</p>
                                    <p class="mt-1 text-lg font-bold">Ritual harian</p>
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
    <p class="mt-1 text-sm font-semibold">Rasa natural yang halus, pas untuk momen santai tanpa terasa berat</p>

    <p class="mt-2 text-lg font-extrabold text-amber-300">
        <span class="text-zinc-400 line-through mr-2">Rp55k</span>
        Rp35k
    </p>
    </div>
                            </div>
                        </div>
                    </section>

                    <section data-reveal class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
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

                    @if ($featuredProducts->isNotEmpty())
                        <section data-reveal class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                            <div class="mb-6 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-amber-700">Etalase pilihan</p>
                                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Best Products Fine Nectar</h2>
                                </div>
                                <a href="{{ route('products.index') }}" class="text-sm font-bold text-zinc-700 hover:text-zinc-950">Lihat semua</a>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($featuredProducts as $product)
                                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200">
                                        @if ($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-52 w-full object-cover">
                                        @else
                                            <div class="flex h-52 items-center justify-center bg-gradient-to-br from-amber-200 via-orange-100 to-white px-5 text-center">
                                                <span class="text-xl font-extrabold text-amber-900">{{ $product->name }}</span>
                                            </div>
                                        @endif
                                        <div class="p-5">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">{{ $product->category ?: 'Fine Nectar' }}</p>
                                            <h3 class="mt-1 text-lg font-extrabold">{{ $product->name }}</h3>
                                            <div class="mt-4 flex items-center justify-between">
                                                <p class="text-xl font-extrabold">{{ $product->formattedPrice() }}</p>
                                                <a href="{{ route('products.show', $product) }}" class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-700">Detail</a>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section data-reveal id="manfaat" class="mx-auto w-full max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                        <div class="mb-6 flex items-end justify-between gap-4">
                            <div>
                                <p class="text-sm font-semibold text-amber-700">Kenapa banyak orang pilih Fine Nectar?</p>
                                <h2 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Manfaat untuk Tenang, Nyaman, dan Sehat</h2>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-2xl">🌿</p>
                                <h3 class="mt-3 text-lg font-bold">Biar Hari Lebih Rileks</h3>
                                <p class="mt-2 text-sm text-zinc-600">Rasa alami yang halus membantu menciptakan momen jeda yang lebih tenang di tengah aktivitas padat.</p>
                            </article>

                            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-2xl">💧</p>
                                <h3 class="mt-3 text-lg font-bold">Nyaman untuk Rutinitas</h3>
                                <p class="mt-2 text-sm text-zinc-600">Praktis diminum kapan pun kamu butuh jeda singkat tanpa sensasi yang terlalu berat.</p>
                            </article>

                            <article class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-zinc-200">
                                <p class="text-2xl">💛</p>
                                <h3 class="mt-3 text-lg font-bold">Teman Self-Care Harian</h3>
                                <p class="mt-2 text-sm text-zinc-600">Tetap relevan untuk kesehatan sehari-hari setelah prioritas utama rasa tenang terpenuhi.</p>
                            </article>
                        </div>
                    </section>

                    <section data-reveal class="mx-auto grid w-full max-w-6xl gap-6 px-4 pb-20 sm:px-6 lg:grid-cols-2 lg:px-8">
                        <div class="overflow-hidden rounded-3xl ring-1 ring-zinc-200">
                            <img
                                src="{{ $product2 }}"
                                alt="Madu alami"
                                class="h-64 w-full object-cover sm:h-full"
                            >
                        </div>
                        <div class="rounded-3xl bg-zinc-900 p-7 text-white sm:p-9">
                            <p class="text-xs uppercase tracking-widest text-zinc-300">Fine Nectar Daily Ritual</p>
                            <h3 class="mt-2 text-2xl font-extrabold leading-tight sm:text-3xl">1 sendok untuk mulai hari dengan lebih tenang, lalu tubuh tetap terjaga.</h3>
                            <ul class="mt-5 space-y-3 text-sm text-zinc-200 sm:text-base">
                                <li>• Pemanis alami yang enak untuk teh hangat, air hangat, atau minuman santai.</li>
                                <li>• Bisa jadi topping roti, oats, atau yogurt.</li>
                                <li>• Praktis dibawa untuk sekolah, kuliah, atau kerja.</li>
                                <li>• Cocok untuk momen pagi, sore, atau saat butuh jeda.</li>
                                <li>• Bisa dikonsumsi langsung karena nyaman di mulut dan perut tanpa bikin enek.</li>

                            </ul>
                            <a href="#order" class="mt-7 inline-flex rounded-full bg-amber-400 px-6 py-3 text-sm font-bold text-zinc-900 transition hover:bg-amber-300">
                                Mulai dari Rp35K sekarang
                            </a>
                        </div>
                    </section>

                    <section data-reveal id="order" class="mx-auto w-full max-w-6xl px-4 pb-20 sm:px-6 lg:px-8">
                        <div class="grid gap-6 lg:grid-cols-2">
                            <div class="rounded-3xl bg-white p-6 ring-1 ring-zinc-200 sm:p-7">
                                <p class="text-xs uppercase tracking-wide text-amber-700">Checkout Fine Nectar</p>
                                <h3 class="mt-2 text-2xl font-extrabold tracking-tight">Bayar pakai QRIS atau COD</h3>
                                <p class="mt-2 text-sm text-zinc-600">Produk: <span class="font-bold text-zinc-900">{{ $checkoutProductName }}</span></p>
                                <p class="mt-1 text-sm text-zinc-600">Harga per kemasan: <span class="font-bold text-zinc-900">Rp{{ number_format($checkoutPrice, 0, ',', '.') }}</span></p>

                                @unless ($checkoutProducts->isNotEmpty())
                                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-800">
                                        Produk belum siap dipilih. Tambahkan atau aktifkan produk dulu ya.
                                    </div>
                                @endunless

                                <form action="{{ route('checkout.store') }}" method="POST" class="mt-6 space-y-4">
                                    @csrf

                                    <div>
                                        <label for="product_id" class="mb-1 block text-sm font-semibold text-zinc-800">Pilih produk</label>
                                        <select id="product_id" name="product_id" required @disabled($checkoutProducts->isEmpty()) class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900 disabled:cursor-not-allowed disabled:bg-zinc-100">
                                            @if ($checkoutProducts->isEmpty())
                                                <option value="">Belum ada produk tersedia</option>
                                            @else
                                                <option value="" disabled {{ old('product_id') ? '' : 'selected' }}>Pilih produk</option>
                                                @foreach ($checkoutProducts as $productOption)
                                                    <option value="{{ $productOption->id }}" {{ (string) old('product_id', $checkoutProduct?->id) === (string) $productOption->id ? 'selected' : '' }}>
                                                        {{ $productOption->name }} - {{ $productOption->formattedPrice() }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @error('product_id')
                                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div id="selected-product-preview" class="hidden overflow-hidden rounded-2xl border border-zinc-200 bg-amber-50/70">
                                        <div class="grid gap-4 p-4 sm:grid-cols-[120px_1fr]">
                                            <div class="overflow-hidden rounded-xl bg-white ring-1 ring-zinc-200">
                                                <img id="selected-product-image" src="" alt="" class="hidden h-32 w-full object-cover">
                                                <div id="selected-product-image-empty" class="flex h-32 items-center justify-center px-3 text-center text-xs font-semibold text-zinc-500">
                                                    Foto belum tersedia
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-wide text-amber-700">Produk dipilih</p>
                                                <h4 id="selected-product-name" class="mt-1 text-lg font-extrabold text-zinc-900"></h4>
                                                <p id="selected-product-description" class="mt-1 line-clamp-2 text-sm text-zinc-600"></p>
                                                <div class="mt-3 grid gap-2 text-sm text-zinc-700 sm:grid-cols-3">
                                                    <div><span class="font-bold text-zinc-900">Harga:</span> <span id="selected-product-price"></span></div>
                                                    <div><span class="font-bold text-zinc-900">Isi:</span> <span id="selected-product-weight"></span></div>
                                                    <div><span class="font-bold text-zinc-900">Total:</span> <span id="selected-product-total"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                                        <span class="font-semibold text-emerald-900">Ongkir:</span>
                                        <span id="shipping-status">{{ $checkoutProduct?->is_free_shipping ? 'Free ongkir.' : 'Ongkir belum termasuk, akan dikonfirmasi setelah checkout.' }}</span>
                                    </div>

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

                                    @if (config('services.turnstile.site_key'))
                                        <div>
                                            <div
                                                class="cf-turnstile"
                                                data-sitekey="{{ config('services.turnstile.site_key') }}"
                                                data-theme="light"
                                            ></div>
                                            @error('cf-turnstile-response')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <button type="submit" @disabled($checkoutProducts->isEmpty()) class="inline-flex w-full items-center justify-center rounded-full bg-zinc-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-zinc-700 disabled:cursor-not-allowed disabled:bg-zinc-400">
                                        Buat Pesanan
                                    </button>
                                </form>
                            </div>

                            <div class="rounded-3xl bg-amber-100/70 p-6 ring-1 ring-amber-200 sm:p-7">
                                <h4 class="text-lg font-extrabold">Cara kerja pembayaran</h4>
                                <ul class="mt-4 space-y-3 text-sm text-zinc-700">
                                    <li>• <span class="font-semibold text-zinc-900">QRIS:</span> setelah klik "Buat Pesanan", kamu akan diarahkan ke halaman pembayaran Tripay untuk scan QR.</li>
                                    <li>• <span class="font-semibold text-zinc-900">COD:</span> pesanan langsung masuk, lalu tim Fine Nectar hubungi kamu untuk konfirmasi pengiriman.</li>
                                    <li>• <span class="font-semibold text-zinc-900">Harga produk saat ini:</span> {{ $checkoutProduct?->is_free_shipping ? 'Free ongkir.' : 'Ongkir belum termasuk, akan dikonfirmasi setelah checkout.' }} </li>
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
                                <p class="text-sm text-zinc-600">© {{ date('Y') }} Fine Nectar. Tenang dulu, sehat menyusul. Made for a calmer daily ritual.</p>
                                <div class="mt-3 flex flex-col gap-1 text-sm">
                                    <a href="{{ route('terms') }}" class="font-semibold text-zinc-800 hover:text-zinc-900">Lihat Syarat & Ketentuan</a>
                                    <a href="{{ route('privacy') }}" class="font-semibold text-zinc-800 hover:text-zinc-900">Lihat Kebijakan Privasi</a>
                                    <a href="{{ route('contact') }}" class="font-semibold text-zinc-800 hover:text-zinc-900">Kontak Customer Service</a>
                                </div>
                            </div>
                            <div>
                                <p class="text-base font-semibold text-zinc-900 mb-2">INFORMASI PEMILIK BRAND</p>
                                <p class="text-sm text-zinc-600">
                                    <span class="block">Fine Nectar</span>
                                    <span class="block">Jember, Jawa Timur</span>
                                    <span class="block font-medium text-zinc-900 mt-1">Hubungi: 085859714058</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const revealElements = document.querySelectorAll('[data-reveal]');
                    const checkoutProducts = @json($checkoutProductPayload);
                    const productSelect = document.getElementById('product_id');
                    const quantityInput = document.getElementById('quantity');
                    const preview = document.getElementById('selected-product-preview');
                    const previewImage = document.getElementById('selected-product-image');
                    const previewImageEmpty = document.getElementById('selected-product-image-empty');
                    const previewName = document.getElementById('selected-product-name');
                    const previewDescription = document.getElementById('selected-product-description');
                    const previewPrice = document.getElementById('selected-product-price');
                    const previewWeight = document.getElementById('selected-product-weight');
                    const previewTotal = document.getElementById('selected-product-total');
                    const shippingStatus = document.getElementById('shipping-status');

                    const formatRupiah = function (value) {
                        return new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            maximumFractionDigits: 0,
                        }).format(value || 0);
                    };

                    const updateProductPreview = function () {
                        if (!productSelect || !preview) {
                            return;
                        }

                        const product = checkoutProducts[productSelect.value];

                        if (!product) {
                            preview.classList.add('hidden');
                            return;
                        }

                        const quantity = Math.max(parseInt(quantityInput?.value || '1', 10) || 1, 1);
                        const subtotal = (product.price || 0) * quantity;
                        preview.classList.remove('hidden');
                        previewName.textContent = product.name || '-';
                        previewDescription.textContent = product.description || product.category || 'Detail produk belum diisi.';
                        previewPrice.textContent = product.formatted_price || formatRupiah(product.price);
                        previewWeight.textContent = product.net_weight || '-';
                        previewTotal.textContent = formatRupiah(subtotal);

                        if (shippingStatus) {
                            shippingStatus.textContent = product.is_free_shipping
                                ? 'Free ongkir.'
                                : 'Ongkir belum termasuk, akan dikonfirmasi setelah checkout.';
                        }

                        if (product.image_url) {
                            previewImage.src = product.image_url;
                            previewImage.alt = product.name || 'Foto produk';
                            previewImage.classList.remove('hidden');
                            previewImageEmpty.classList.add('hidden');
                        } else {
                            previewImage.removeAttribute('src');
                            previewImage.classList.add('hidden');
                            previewImageEmpty.classList.remove('hidden');
                        }
                    };

                    if (productSelect) {
                        productSelect.addEventListener('change', updateProductPreview);
                    }

                    if (quantityInput) {
                        quantityInput.addEventListener('input', updateProductPreview);
                    }

                    updateProductPreview();

                    if (!revealElements.length) {
                        return;
                    }

                    const observer = new IntersectionObserver(function (entries, currentObserver) {
                        entries.forEach(function (entry) {
                            if (!entry.isIntersecting) {
                                return;
                            }

                            entry.target.classList.add('is-visible');
                            currentObserver.unobserve(entry.target);
                        });
                    }, {
                        threshold: 0.18,
                        rootMargin: '0px 0px -10% 0px',
                    });

                    revealElements.forEach(function (element) {
                        observer.observe(element);
                    });
                });
            </script>
        </body>
    </html>
