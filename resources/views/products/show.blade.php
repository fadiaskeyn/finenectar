<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $product->name }} | Fine Nectar</title>
        <meta name="description" content="{{ \Illuminate\Support\Str::limit($product->description, 150) }}">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif

        @if (config('services.turnstile.site_key'))
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endif

        @include('partials.flash-alert')
    </head>
    <body class="bg-amber-50 text-zinc-900 antialiased">
        <header class="border-b border-zinc-200 bg-white/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-extrabold">Fine Nectar</a>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-zinc-700 hover:text-zinc-950">Semua Produk</a>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">{{ session('error') }}</div>
            @endif

            <section class="grid gap-8 lg:grid-cols-[1.05fr_.95fr] lg:items-start">
                <div class="overflow-hidden rounded-3xl bg-white ring-1 ring-zinc-200">
                    @if ($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-[420px] w-full object-cover">
                    @else
                        <div class="flex h-[420px] items-center justify-center bg-gradient-to-br from-amber-200 via-orange-100 to-white px-8 text-center">
                            <span class="text-4xl font-extrabold text-amber-900">{{ $product->name }}</span>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">{{ $product->category ?: 'Fine Nectar' }}</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-5xl">{{ $product->name }}</h1>
                    <p class="mt-4 text-base leading-7 text-zinc-700">{{ $product->description }}</p>

                    <div class="mt-5 flex items-end gap-3">
                        @if ($product->compare_at_price)
                            <span class="text-lg font-bold text-zinc-400 line-through">Rp{{ number_format($product->compare_at_price, 0, ',', '.') }}</span>
                        @endif
                        <span class="text-3xl font-extrabold">{{ $product->formattedPrice() }}</span>
                    </div>

                    <div class="mt-5 grid gap-3 text-sm text-zinc-700 sm:grid-cols-2">
                        <div class="rounded-2xl bg-white px-4 py-3 ring-1 ring-zinc-200"><span class="font-bold text-zinc-950">Isi:</span> {{ $product->net_weight ?: '-' }}</div>
                        <div class="rounded-2xl bg-white px-4 py-3 ring-1 ring-zinc-200"><span class="font-bold text-zinc-950">Stok:</span> {{ $product->stock }}</div>
                    </div>

                    <form id="order" action="{{ route('checkout.store') }}" method="POST" class="mt-7 space-y-4 rounded-3xl bg-white p-5 ring-1 ring-zinc-200">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <h2 class="text-xl font-extrabold">Checkout Produk Ini</h2>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="customer_name" class="mb-1 block text-sm font-semibold">Nama lengkap</label>
                                <input id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
                                @error('customer_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="customer_phone" class="mb-1 block text-sm font-semibold">Nomor WhatsApp</label>
                                <input id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
                                @error('customer_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="customer_address" class="mb-1 block text-sm font-semibold">Alamat lengkap</label>
                            <textarea id="customer_address" name="customer_address" rows="3" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">{{ old('customer_address') }}</textarea>
                            @error('customer_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="quantity" class="mb-1 block text-sm font-semibold">Jumlah</label>
                                <input id="quantity" name="quantity" type="number" min="1" value="{{ old('quantity', 1) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
                            </div>
                            <div>
                                <label for="payment_method" class="mb-1 block text-sm font-semibold">Metode</label>
                                <select id="payment_method" name="payment_method" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
                                    <option value="qris">QRIS</option>
                                    <option value="cod">COD</option>
                                </select>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            {{ $product->is_free_shipping ? 'Produk ini free ongkir.' : 'Ongkir belum termasuk dan akan dikonfirmasi setelah checkout.' }}
                        </div>

                        @if (config('services.turnstile.site_key'))
                            <div>
                                <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="light"></div>
                                @error('cf-turnstile-response') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif

                        <button class="w-full rounded-full bg-zinc-900 px-6 py-3 text-sm font-bold text-white hover:bg-zinc-700">Buat Pesanan</button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
