<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Dev Landing | Fine Nectar</title>
        <meta name="robots" content="noindex, nofollow">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        @if (config('services.turnstile.site_key'))
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif

        <style>
            [data-animate] { opacity: 0; transform: translateY(18px); }
            .honey-glow { box-shadow: 0 24px 70px rgba(245, 158, 11, .22); }
        </style>
    </head>
    <body class="bg-[#fff8ea] text-zinc-950 antialiased">
        <div class="min-h-screen overflow-hidden">
            <header class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-extrabold">Fine Nectar</a>
                <nav class="flex items-center gap-4 text-sm font-semibold text-zinc-700">
                    <a href="{{ route('products.index') }}" class="hover:text-zinc-950">Produk</a>
                    <a href="#order" class="rounded-full bg-zinc-950 px-5 py-2.5 text-white hover:bg-zinc-700">Checkout</a>
                </nav>
            </header>

            <main>
                <section class="mx-auto grid max-w-6xl gap-10 px-4 pb-14 pt-6 sm:px-6 lg:grid-cols-[1fr_.9fr] lg:px-8 lg:pb-20">
                    <div class="self-center">
                        <p data-animate class="inline-flex rounded-full border border-amber-300 bg-white px-3 py-1 text-xs font-bold uppercase tracking-wide text-amber-700">Eksperimen Anime.js</p>
                        <h1 data-animate class="mt-5 max-w-3xl text-5xl font-extrabold leading-tight tracking-tight sm:text-6xl lg:text-7xl">
                            Madu premium yang terasa calm, clean, dan siap dijual lebih serius.
                        </h1>
                        <p data-animate class="mt-5 max-w-xl text-base leading-7 text-zinc-700 sm:text-lg">
                            Ini versi dev landing Fine Nectar dengan motion ringan. Fokusnya bukan ramai, tapi bikin produk terasa lebih hidup tanpa mengganggu orang yang mau cepat checkout.
                        </p>
                        <div data-animate class="mt-7 flex flex-col gap-3 sm:flex-row">
                            <a href="#products" class="rounded-full bg-zinc-950 px-6 py-3 text-center text-sm font-bold text-white hover:bg-zinc-700">Lihat Produk</a>
                            <a href="{{ route('home') }}" class="rounded-full border border-zinc-300 bg-white px-6 py-3 text-center text-sm font-bold hover:border-zinc-950">Balik ke Landing Lama</a>
                        </div>
                    </div>

                    <div data-animate class="relative">
                        <div class="honey-glow relative overflow-hidden rounded-[2rem] bg-zinc-950 p-4 text-white">
                            <div class="flex h-[520px] items-center justify-center rounded-[1.5rem] bg-gradient-to-br from-amber-200 via-orange-100 to-white p-8 text-center text-zinc-950">
                                <div>
                                    <p class="text-sm font-bold uppercase tracking-[.25em] text-amber-700">Fine Nectar</p>
                                    <p class="mt-5 text-5xl font-extrabold">Pure Honey</p>
                                    <p class="mt-3 text-sm font-semibold text-zinc-600">200ml daily ritual</p>
                                </div>
                            </div>
                            <div class="mt-4 flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-zinc-400">Signature Bottle</p>
                                    <p class="font-bold">Promo launch mulai Rp35K</p>
                                </div>
                                <span class="rounded-full bg-amber-300 px-4 py-2 text-sm font-extrabold text-zinc-950">-36%</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="products" class="mx-auto max-w-6xl px-4 pb-16 sm:px-6 lg:px-8">
                    <div data-animate class="mb-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide text-amber-700">Etalase Dev</p>
                            <h2 class="mt-1 text-3xl font-extrabold tracking-tight">Featured products</h2>
                        </div>
                        <a href="{{ route('products.index') }}" class="text-sm font-bold text-zinc-700 hover:text-zinc-950">Lihat semua</a>
                    </div>

                    <div class="grid gap-5 md:grid-cols-3">
                        @forelse ($products as $product)
                            <article data-card class="rounded-3xl bg-white p-4 ring-1 ring-zinc-200">
                                @if ($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-56 w-full rounded-2xl object-cover">
                                @else
                                    <div class="flex h-56 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-200 via-orange-100 to-white px-6 text-center">
                                        <span class="text-xl font-extrabold text-amber-900">{{ $product->name }}</span>
                                    </div>
                                @endif
                                <div class="p-2 pt-4">
                                    <p class="text-xs font-bold uppercase tracking-wide text-amber-700">{{ $product->category ?: 'Fine Nectar' }}</p>
                                    <h3 class="mt-1 text-lg font-extrabold">{{ $product->name }}</h3>
                                    <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ $product->description }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <p class="text-xl font-extrabold">{{ $product->formattedPrice() }}</p>
                                        <a href="{{ route('products.show', $product) }}" class="rounded-full bg-zinc-950 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-700">Checkout</a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-3xl bg-white p-8 text-center text-zinc-600 ring-1 ring-zinc-200 md:col-span-3">Isi produk dulu dari admin untuk melihat etalase dev.</div>
                        @endforelse
                    </div>
                </section>

                @if ($checkoutProduct)
                    <section id="order" class="mx-auto max-w-3xl px-4 pb-20 sm:px-6 lg:px-8">
                        <form data-animate action="{{ route('checkout.store') }}" method="POST" class="space-y-4 rounded-[2rem] bg-zinc-950 p-6 text-white honey-glow sm:p-8">
                            @csrf
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide text-amber-300">Quick checkout</p>
                                <h2 class="mt-1 text-3xl font-extrabold">{{ $checkoutProduct->name }}</h2>
                                <p class="mt-2 text-sm text-zinc-300">{{ $checkoutProduct->formattedPrice() }} per item, QRIS via Tripay atau COD.</p>
                            </div>

                            <div>
                                <label for="product_id" class="mb-1 block text-sm font-semibold text-zinc-100">Pilih produk</label>
                                <select id="product_id" name="product_id" required class="w-full rounded-xl border border-white/20 bg-zinc-900 px-4 py-3 text-sm outline-none focus:border-amber-300">
                                    @foreach ($checkoutProducts as $productOption)
                                        <option value="{{ $productOption->id }}" {{ (string) old('product_id', $checkoutProduct?->id) === (string) $productOption->id ? 'selected' : '' }}>
                                            {{ $productOption->name }} - {{ $productOption->formattedPrice() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('product_id') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <input name="customer_name" value="{{ old('customer_name') }}" placeholder="Nama lengkap" required class="rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm outline-none placeholder:text-zinc-400 focus:border-amber-300">
                                <input name="customer_phone" value="{{ old('customer_phone') }}" placeholder="Nomor WhatsApp" required class="rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm outline-none placeholder:text-zinc-400 focus:border-amber-300">
                            </div>
                            <textarea name="customer_address" rows="3" placeholder="Alamat lengkap" required class="w-full rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm outline-none placeholder:text-zinc-400 focus:border-amber-300">{{ old('customer_address') }}</textarea>
                            <div class="grid gap-4 sm:grid-cols-2">
                                <input name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required class="rounded-xl border border-white/20 bg-white/10 px-4 py-3 text-sm outline-none focus:border-amber-300">
                                <select name="payment_method" class="rounded-xl border border-white/20 bg-zinc-900 px-4 py-3 text-sm outline-none focus:border-amber-300">
                                    <option value="qris">QRIS Tripay</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                            @if (config('services.turnstile.site_key'))
                                <div>
                                    <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="dark"></div>
                                    @error('cf-turnstile-response') <p class="mt-1 text-xs text-red-300">{{ $message }}</p> @enderror
                                </div>
                            @endif
                            <button type="submit" class="w-full rounded-full bg-amber-300 px-6 py-3 text-sm font-extrabold text-zinc-950 hover:bg-amber-200">Buat Pesanan</button>
                        </form>
                    </section>
                @else
                    <section id="order" class="mx-auto max-w-3xl px-4 pb-20 sm:px-6 lg:px-8">
                        <div class="rounded-[2rem] bg-zinc-950 p-6 text-white sm:p-8">
                            <p class="text-xs font-bold uppercase tracking-wide text-amber-300">Quick checkout</p>
                            <h2 class="mt-1 text-3xl font-extrabold">Produk belum tersedia</h2>
                            <p class="mt-2 text-sm text-zinc-300">Aktifkan produk dulu supaya form checkout bisa dipakai.</p>
                        </div>
                    </section>
                @endif
            </main>
        </div>

        <script type="module">
            import { animate, stagger } from 'https://cdn.jsdelivr.net/npm/animejs/+esm';

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!reduceMotion) {
                animate('[data-animate]', {
                    opacity: [0, 1],
                    translateY: [18, 0],
                    delay: stagger(90),
                    duration: 760,
                    easing: 'out(3)',
                });

                animate('.honey-glow', {
                    translateY: [0, -8],
                    loop: true,
                    alternate: true,
                    duration: 2600,
                    easing: 'inOutSine',
                });

                document.querySelectorAll('[data-card]').forEach((card) => {
                    card.addEventListener('mouseenter', () => {
                        animate(card, { translateY: -8, scale: 1.015, duration: 260, easing: 'out(3)' });
                    });
                    card.addEventListener('mouseleave', () => {
                        animate(card, { translateY: 0, scale: 1, duration: 320, easing: 'out(3)' });
                    });
                });
            } else {
                document.querySelectorAll('[data-animate]').forEach((element) => {
                    element.style.opacity = 1;
                    element.style.transform = 'none';
                });
            }
        </script>
    </body>
</html>
