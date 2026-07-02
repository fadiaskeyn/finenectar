<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>WhatsApp Login | Fine Nectar</title>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="min-h-screen bg-[radial-gradient(circle_at_top,_#fef3c7_0%,_#fff7ed_28%,_#f8fafc_100%)] text-zinc-900 antialiased">
        <div class="mx-auto flex min-h-screen w-full max-w-6xl items-center px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid w-full gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <section class="rounded-[2rem] border border-white/70 bg-white/80 p-8 shadow-[0_24px_80px_rgba(120,53,15,0.12)] backdrop-blur">
                    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-amber-700">Baileys login</p>
                            <h1 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Scan QR WhatsApp</h1>
                        </div>

                        <a href="{{ route('admin.orders.index') }}" class="rounded-full border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-800 hover:border-zinc-900">
                            Kembali ke order
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid gap-4 sm:grid-cols-3">
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Status</p>
                            <p id="wa-status" class="mt-2 text-lg font-bold text-zinc-900">{{ $session['status'] }}</p>
                        </div>
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Running</p>
                            <p id="wa-running" class="mt-2 text-lg font-bold text-zinc-900">{{ $session['running'] ? 'Ya' : 'Tidak' }}</p>
                        </div>
                        <div class="rounded-2xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500">Update</p>
                            <p id="wa-updated" class="mt-2 text-sm font-semibold text-zinc-900">{{ $session['updated_at'] ? \Illuminate\Support\Carbon::parse($session['updated_at'])->format('d M Y H:i:s') : '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <form method="POST" action="{{ route('admin.whatsapp.start') }}">
                            @csrf
                            <button type="submit" class="rounded-full bg-zinc-900 px-5 py-3 text-sm font-bold text-white hover:bg-zinc-700">
                                Mulai login WA
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.whatsapp.stop') }}">
                            @csrf
                            <button type="submit" class="rounded-full border border-zinc-300 bg-white px-5 py-3 text-sm font-bold text-zinc-800 hover:border-zinc-900">
                                Reset session
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 rounded-3xl border border-dashed border-amber-300 bg-amber-50/80 p-5 text-sm text-amber-950">
                        <p id="wa-message" class="font-semibold">{{ $session['message'] }}</p>
                        <p class="mt-2 text-amber-900/80">Reset session akan menghapus auth lama. Setelah itu klik mulai lagi untuk memaksa QR baru.</p>
                    </div>

                    <div class="mt-6 rounded-3xl border border-zinc-200 bg-zinc-950 p-5 text-sm text-zinc-100">
                        <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.22em] text-amber-300">DEBUG</p>
                                <p class="mt-1 text-zinc-300">Snapshot status backend, file session, PID, auth, dan tail log Baileys.</p>
                            </div>
                            <button type="button" id="wa-copy-debug" class="rounded-full bg-white px-4 py-2 text-xs font-bold text-zinc-950 hover:bg-amber-200">
                                Copy JSON
                            </button>
                        </div>
                        <pre id="wa-debug" class="max-h-[360px] overflow-auto rounded-2xl bg-black/40 p-4 text-xs leading-5 text-emerald-100">{{ json_encode($debug, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    </div>
                </section>

                <section class="rounded-[2rem] border border-zinc-200 bg-zinc-950 p-8 text-white shadow-[0_24px_80px_rgba(9,9,11,0.28)]">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-[0.28em] text-amber-300">QR panel</p>
                            <h2 class="mt-2 text-2xl font-black tracking-tight">Scan dengan akun pembeli/admin</h2>
                        </div>
                        <div class="rounded-full bg-white/10 px-4 py-2 text-xs font-semibold text-zinc-200">Polling otomatis</div>
                    </div>

                    <div class="mt-8 flex min-h-[420px] items-center justify-center rounded-[2rem] border border-white/10 bg-white/5 p-6">
                        <div id="wa-qr-empty" class="max-w-sm text-center text-sm text-zinc-300 {{ filled($session['qr_image'] ?? null) ? 'hidden' : '' }}">
                            <p class="text-lg font-bold text-white">QR belum tersedia</p>
                            <p class="mt-2">Klik <span class="font-semibold text-amber-300">Mulai login WA</span> lalu tunggu QR muncul di sini.</p>
                        </div>

                        <img
                            id="wa-qr-image"
                            class="w-full max-w-sm rounded-3xl bg-white p-4 shadow-2xl {{ filled($session['qr_image'] ?? null) ? '' : 'hidden' }}"
                            src="{{ $session['qr_image'] ?? '' }}"
                            alt="QR login WhatsApp"
                        >
                    </div>
                </section>
            </div>
        </div>

        <script>
            const statusUrl = @json(route('admin.whatsapp.status'));
            const qrImage = document.getElementById('wa-qr-image');
            const qrEmpty = document.getElementById('wa-qr-empty');
            const statusLabel = document.getElementById('wa-status');
            const runningLabel = document.getElementById('wa-running');
            const updatedLabel = document.getElementById('wa-updated');
            const messageLabel = document.getElementById('wa-message');
            const debugPanel = document.getElementById('wa-debug');
            const copyDebugButton = document.getElementById('wa-copy-debug');

            let latestDebug = @json($debug);

            const formatTime = (value) => {
                if (!value) {
                    return '-';
                }

                const date = new Date(value);
                if (Number.isNaN(date.getTime())) {
                    return value;
                }

                return new Intl.DateTimeFormat('id-ID', {
                    dateStyle: 'medium',
                    timeStyle: 'medium',
                }).format(date);
            };

            const refreshSession = async () => {
                try {
                    const response = await fetch(statusUrl, {
                        headers: {
                            'Accept': 'application/json',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const session = await response.json();

                    if (statusLabel) {
                        statusLabel.textContent = session.status ?? '-';
                    }

                    if (runningLabel) {
                        runningLabel.textContent = session.running ? 'Ya' : 'Tidak';
                    }

                    if (updatedLabel) {
                        updatedLabel.textContent = formatTime(session.updated_at);
                    }

                    if (messageLabel) {
                        messageLabel.textContent = session.message ?? '-';
                    }

                    if (session.debug) {
                        latestDebug = session.debug;

                        if (debugPanel) {
                            debugPanel.textContent = JSON.stringify(session.debug, null, 2);
                        }

                        console.groupCollapsed('[WA DEBUG]', session.debug.status, session.debug.checked_at);
                        console.table({
                            status: session.debug.status,
                            running: session.debug.running,
                            pid: session.debug.pid,
                            auth_file_count: session.debug.auth_file_count,
                            qr_present: session.debug.qr_present,
                            qr_image_present: session.debug.qr_image_present,
                            updated_at: session.debug.updated_at,
                        });
                        console.log(session.debug);
                        console.groupEnd();
                    }

                    if (session.qr_image) {
                        qrImage.src = session.qr_image;
                        qrImage.classList.remove('hidden');
                        qrEmpty.classList.add('hidden');
                    } else {
                        qrImage.classList.add('hidden');
                        qrEmpty.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error(error);
                }
            };

            if (copyDebugButton) {
                copyDebugButton.addEventListener('click', async () => {
                    const debugText = JSON.stringify(latestDebug, null, 2);

                    try {
                        await navigator.clipboard.writeText(debugText);
                        copyDebugButton.textContent = 'Copied';
                        setTimeout(() => {
                            copyDebugButton.textContent = 'Copy JSON';
                        }, 1200);
                    } catch (error) {
                        console.error('[WA DEBUG] copy failed', error);
                    }
                });
            }

            refreshSession();
            setInterval(refreshSession, 3000);
        </script>
    </body>
</html>
