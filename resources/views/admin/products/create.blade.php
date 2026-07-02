<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Tambah Produk | Fine Nectar</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-amber-50 text-zinc-900 antialiased">
        <main class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-extrabold tracking-tight">Tambah Produk</h1>
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5 rounded-3xl bg-white p-6 ring-1 ring-zinc-200">
                @include('admin.products._form', ['buttonLabel' => 'Simpan Produk'])
            </form>
        </main>
    </body>
</html>
