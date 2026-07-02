<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Produk | Fine Nectar</title>
        <meta name="description" content="Katalog produk Fine Nectar. Pilih madu premium, bundle, dan promo yang tersedia.">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="bg-amber-50 text-zinc-900 antialiased">
        <header class="border-b border-zinc-200 bg-white/80">
            <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="text-lg font-extrabold">Fine Nectar</a>
                <nav class="flex items-center gap-4 text-sm font-semibold text-zinc-700">
                    <a href="{{ route('home') }}" class="hover:text-zinc-950">Home</a>
                    <a href="{{ route('products.index') }}" class="text-zinc-950">Produk</a>
                    <a href="{{ route('contact') }}" class="hover:text-zinc-950">Kontak</a>
                </nav>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Etalase Fine Nectar</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">Pilih produk yang pas buat rutinitas lo</h1>
                </div>

                <form method="GET" action="{{ route('products.index') }}" class="grid gap-2 sm:min-w-[420px] sm:grid-cols-[1fr_auto]">
                    <input name="q" value="{{ request('q') }}" placeholder="Cari produk..." class="rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none focus:border-zinc-900">
                    <button class="rounded-xl bg-zinc-900 px-5 py-3 text-sm font-bold text-white hover:bg-zinc-700">Cari</button>
                </form>
            </div>

            @if ($categories->isNotEmpty())
                <div class="mt-6 flex flex-wrap gap-2">
                    <a href="{{ route('products.index', request()->except('category')) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request('category') ? 'bg-white text-zinc-700 ring-1 ring-zinc-200' : 'bg-zinc-900 text-white' }}">Semua</a>
                    @foreach ($categories as $category)
                        <a href="{{ route('products.index', array_merge(request()->except('page'), ['category' => $category])) }}" class="rounded-full px-4 py-2 text-sm font-semibold {{ request('category') === $category ? 'bg-zinc-900 text-white' : 'bg-white text-zinc-700 ring-1 ring-zinc-200' }}">{{ $category }}</a>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($products as $product)
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200">
                        <a href="{{ route('products.show', $product) }}" class="block">
                            @if ($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-64 w-full object-cover">
                            @else
                                <div class="flex h-64 items-center justify-center bg-gradient-to-br from-amber-200 via-orange-100 to-white px-8 text-center">
                                    <span class="text-2xl font-extrabold text-amber-900">{{ $product->name }}</span>
                                </div>
                            @endif
                        </a>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">{{ $product->category ?: 'Fine Nectar' }}</p>
                                    <h2 class="mt-1 text-lg font-extrabold">{{ $product->name }}</h2>
                                </div>
                                @if ($product->is_promo)
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">Promo</span>
                                @endif
                            </div>
                            <p class="mt-2 line-clamp-2 text-sm text-zinc-600">{{ $product->description }}</p>
                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div>
                                    @if ($product->compare_at_price)
                                        <p class="text-xs font-semibold text-zinc-400 line-through">Rp{{ number_format($product->compare_at_price, 0, ',', '.') }}</p>
                                    @endif
                                    <p class="text-xl font-extrabold">{{ $product->formattedPrice() }}</p>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-bold text-white hover:bg-zinc-700">Detail</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-2xl bg-white p-8 text-center text-zinc-600 ring-1 ring-zinc-200 sm:col-span-2 lg:col-span-3">
                        Produk belum tersedia.
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        </main>
    </body>
</html>
