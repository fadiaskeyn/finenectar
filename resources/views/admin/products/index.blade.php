<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Produk Admin | Fine Nectar</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-amber-50 text-zinc-900 antialiased">
        <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight">Produk</h1>
                    <p class="text-sm text-zinc-600">Kelola etalase, promo, dan item checkout Tripay.</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold hover:border-zinc-900">Pesanan</a>
                    <a href="{{ route('admin.products.create') }}" class="rounded-full bg-zinc-900 px-4 py-2 text-sm font-semibold text-white hover:bg-zinc-700">Tambah Produk</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold hover:border-zinc-900">Logout</button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">{{ session('success') }}</div>
            @endif

            <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-zinc-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-zinc-50 text-left text-zinc-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Produk</th>
                                <th class="px-4 py-3 font-semibold">Harga</th>
                                <th class="px-4 py-3 font-semibold">Stok</th>
                                <th class="px-4 py-3 font-semibold">Status</th>
                                <th class="px-4 py-3 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100">
                            @forelse ($products as $product)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-zinc-900">{{ $product->name }}</div>
                                        <div class="text-xs text-zinc-500">{{ $product->slug }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-semibold">{{ $product->formattedPrice() }}</div>
                                        @if ($product->compare_at_price)
                                            <div class="text-xs text-zinc-400 line-through">Rp{{ number_format($product->compare_at_price, 0, ',', '.') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">{{ $product->stock }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-1">
                                            <span class="rounded-full px-2 py-1 text-xs font-bold {{ $product->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-zinc-100 text-zinc-600' }}">{{ $product->is_active ? 'Aktif' : 'Draft' }}</span>
                                            @if ($product->is_featured)
                                                <span class="rounded-full bg-amber-100 px-2 py-1 text-xs font-bold text-amber-800">Featured</span>
                                            @endif
                                            @if ($product->is_promo)
                                                <span class="rounded-full bg-orange-100 px-2 py-1 text-xs font-bold text-orange-800">Promo</span>
                                            @endif
                                            @if ($product->is_free_shipping)
                                                <span class="rounded-full bg-sky-100 px-2 py-1 text-xs font-bold text-sky-800">Free ongkir</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('products.show', $product) }}" class="rounded-full border border-zinc-300 px-3 py-1 text-xs font-semibold hover:border-zinc-900">Lihat</a>
                                            <a href="{{ route('admin.products.edit', $product) }}" class="rounded-full bg-zinc-900 px-3 py-1 text-xs font-semibold text-white hover:bg-zinc-700">Edit</a>
                                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-full border border-red-200 px-3 py-1 text-xs font-semibold text-red-700 hover:border-red-500">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Belum ada produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-zinc-100 px-4 py-3">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </body>
</html>
