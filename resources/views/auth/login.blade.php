<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login Admin | Fine Nectar</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-amber-50 text-zinc-900 antialiased">
        <div class="mx-auto flex min-h-screen w-full max-w-md items-center px-4 py-10">
            <div class="w-full rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-200 sm:p-8">
                <h1 class="text-2xl font-extrabold tracking-tight">Login Admin</h1>
                <p class="mt-1 text-sm text-zinc-600">Masuk untuk melihat data pesanan Fine Nectar.</p>

                @if ($errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    <div>
                        <label for="email" class="mb-1 block text-sm font-semibold text-zinc-800">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                    </div>

                    <div>
                        <label for="password" class="mb-1 block text-sm font-semibold text-zinc-800">Password</label>
                        <input id="password" name="password" type="password" required class="w-full rounded-xl border border-zinc-300 px-4 py-3 text-sm outline-none transition focus:border-zinc-900">
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-zinc-900">
                        <label for="remember" class="text-sm text-zinc-700">Ingat saya</label>
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-zinc-900 px-6 py-3 text-sm font-bold text-white transition hover:bg-zinc-700">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>
