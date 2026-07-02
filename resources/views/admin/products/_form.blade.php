@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="name" class="mb-1 block text-sm font-semibold">Nama produk</label>
        <input id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="slug" class="mb-1 block text-sm font-semibold">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $product->slug) }}" placeholder="otomatis kalau kosong" class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
        @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div>
    <label for="description" class="mb-1 block text-sm font-semibold">Deskripsi</label>
    <textarea id="description" name="description" rows="4" class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">{{ old('description', $product->description) }}</textarea>
    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <div>
        <label for="price" class="mb-1 block text-sm font-semibold">Harga</label>
        <input id="price" name="price" type="number" min="0" value="{{ old('price', $product->price) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
        @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="compare_at_price" class="mb-1 block text-sm font-semibold">Harga coret</label>
        <input id="compare_at_price" name="compare_at_price" type="number" min="0" value="{{ old('compare_at_price', $product->compare_at_price) }}" class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
        @error('compare_at_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="stock" class="mb-1 block text-sm font-semibold">Stok</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
        @error('stock') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <div>
        <label for="net_weight" class="mb-1 block text-sm font-semibold">Isi bersih</label>
        <input id="net_weight" name="net_weight" value="{{ old('net_weight', $product->net_weight) }}" class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
    </div>

    <div>
        <label for="category" class="mb-1 block text-sm font-semibold">Kategori</label>
        <input id="category" name="category" value="{{ old('category', $product->category) }}" class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none focus:border-zinc-900">
    </div>
</div>

<div class="grid gap-4 sm:grid-cols-[220px_1fr]">
    <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-zinc-50">
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name ?: 'Preview produk' }}" class="h-52 w-full object-cover">
        @else
            <div class="flex h-52 items-center justify-center px-4 text-center text-sm font-semibold text-zinc-500">
                Belum ada gambar
            </div>
        @endif
    </div>

    <div>
        <label for="image" class="mb-1 block text-sm font-semibold">Foto produk</label>
        <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="w-full rounded-xl border border-zinc-300 bg-white px-4 py-3 text-sm outline-none file:mr-4 file:rounded-full file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-sm file:font-bold file:text-white focus:border-zinc-900">
        <p class="mt-2 text-xs text-zinc-500">Upload JPG, PNG, atau WEBP. Maksimal 4MB. Kalau dikosongkan saat edit, gambar lama tetap dipakai.</p>
        @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="flex flex-wrap gap-4 rounded-2xl bg-amber-50 p-4">
    <label class="inline-flex items-center gap-2 text-sm font-semibold">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active)) class="rounded border-zinc-300">
        Aktif
    </label>
    <label class="inline-flex items-center gap-2 text-sm font-semibold">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured)) class="rounded border-zinc-300">
        Featured
    </label>
    <label class="inline-flex items-center gap-2 text-sm font-semibold">
        <input type="checkbox" name="is_promo" value="1" @checked(old('is_promo', $product->is_promo)) class="rounded border-zinc-300">
        Promo
    </label>
    <label class="inline-flex items-center gap-2 text-sm font-semibold">
        <input type="checkbox" name="is_free_shipping" value="1" @checked(old('is_free_shipping', $product->is_free_shipping)) class="rounded border-zinc-300">
        Free ongkir
    </label>
</div>

<div class="flex flex-wrap gap-2">
    <button class="rounded-full bg-zinc-900 px-5 py-3 text-sm font-bold text-white hover:bg-zinc-700">{{ $buttonLabel }}</button>
    <a href="{{ route('admin.products.index') }}" class="rounded-full border border-zinc-300 bg-white px-5 py-3 text-sm font-bold hover:border-zinc-900">Batal</a>
</div>
