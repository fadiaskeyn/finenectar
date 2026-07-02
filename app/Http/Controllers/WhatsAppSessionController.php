<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WhatsAppSessionController extends Controller
{
    public function __construct(private readonly WhatsAppSessionService $session)
    {
    }

    public function index(): View
    {
        return view('admin.whatsapp', [
            'session' => $this->session->status(),
            'debug' => $this->session->debugSnapshot(),
        ]);
    }

    public function start(): RedirectResponse
    {
        $this->session->start();

        return redirect()
            ->route('admin.whatsapp.index')
            ->with('success', 'WhatsApp session dimulai. Scan QR yang tampil untuk login.');
    }

    public function stop(): RedirectResponse
    {
        $this->session->stop();

        return redirect()
            ->route('admin.whatsapp.index')
            ->with('success', 'WhatsApp session dihentikan.');
    }

    public function status(): JsonResponse
    {
        return response()->json([
            ...$this->session->status(),
            'debug' => $this->session->debugSnapshot(),
        ]);
    }
}
