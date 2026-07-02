<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Symfony\Component\Process\Process;

class WhatsAppSessionService
{
    private string $basePath;

    public function __construct()
    {
        $this->basePath = storage_path('app/whatsapp');
    }

    public function status(): array
    {
        $this->ensureDirectories();

        $state = array_merge($this->defaultState(), $this->readState());
        $pid = $this->readPid();

        if ($pid !== null && ! $this->isProcessRunning($pid)) {
            Log::debug('WhatsApp debug: stale pid detected', [
                'pid' => $pid,
                'pid_path' => $this->pidPath(),
            ]);

            $this->forgetPid();
            $pid = null;
        }

        $state['pid'] = $pid;
        $state['running'] = $pid !== null;

        return $state;
    }

    public function start(): array
    {
        $this->ensureDirectories();

        $pid = $this->readPid();
        if ($pid !== null && $this->isProcessRunning($pid)) {
            Log::debug('WhatsApp debug: start skipped because process is already running', [
                'pid' => $pid,
                'status' => $this->readState()['status'] ?? null,
            ]);

            return $this->status();
        }

        $command = sprintf(
            'nohup node %s >> %s 2>&1 & echo $!',
            escapeshellarg(base_path('whatsapp/baileys-session.mjs')),
            escapeshellarg($this->logPath())
        );

        $process = Process::fromShellCommandline($command, base_path());
        $process->setTimeout(10);
        $process->run();

        if (! $process->isSuccessful()) {
            Log::debug('WhatsApp debug: failed to start session process', [
                'exit_code' => $process->getExitCode(),
                'output' => trim($process->getOutput()),
                'error_output' => trim($process->getErrorOutput()),
                'log_path' => $this->logPath(),
            ]);

            throw new RuntimeException(trim($process->getErrorOutput() ?: $process->getOutput()) ?: 'Gagal menyalakan proses WhatsApp.');
        }

        $pid = (int) trim($process->getOutput());
        if ($pid > 0) {
            File::put($this->pidPath(), (string) $pid);
        }

        Log::debug('WhatsApp debug: session process started', [
            'pid' => $pid,
            'pid_path' => $this->pidPath(),
            'state_path' => $this->statePath(),
            'log_path' => $this->logPath(),
        ]);

        $this->writeState([
            'status' => 'starting',
            'message' => 'WhatsApp session sedang dipersiapkan.',
            'qr' => null,
            'qr_image' => null,
        ]);

        return $this->status();
    }

    public function stop(): array
    {
        $pid = $this->readPid();

        if ($pid !== null) {
            Log::debug('WhatsApp debug: stopping session process', [
                'pid' => $pid,
            ]);

            $process = Process::fromShellCommandline(sprintf('kill %d >/dev/null 2>&1 || true', $pid), base_path());
            $process->run();
        }

        $this->forgetPid();
        $this->runBaileysLogout();

        if (File::exists($this->authPath())) {
            File::deleteDirectory($this->authPath());
        }

        File::ensureDirectoryExists($this->authPath());

        Log::debug('WhatsApp debug: auth session deleted for fresh QR login', [
            'auth_path' => $this->authPath(),
        ]);

        $this->writeState([
            'status' => 'stopped',
            'message' => 'WhatsApp session dihentikan dan data login lama dihapus. Klik mulai untuk QR baru.',
            'qr' => null,
            'qr_image' => null,
        ]);

        return $this->status();
    }

    private function runBaileysLogout(): void
    {
        $script = base_path('whatsapp/logout-session.mjs');

        if (! File::exists($script)) {
            Log::debug('WhatsApp debug: Baileys logout script missing, falling back to auth delete', [
                'script' => $script,
            ]);

            return;
        }

        $process = Process::fromShellCommandline(sprintf('node %s', escapeshellarg($script)), base_path());
        $process->setTimeout(20);
        $process->run();

        Log::debug('WhatsApp debug: Baileys logout script finished', [
            'exit_code' => $process->getExitCode(),
            'successful' => $process->isSuccessful(),
            'output' => trim($process->getOutput()),
            'error_output' => trim($process->getErrorOutput()),
        ]);
    }

    public function debugSnapshot(): array
    {
        $this->ensureDirectories();

        $state = array_merge($this->defaultState(), $this->readState());
        $pid = $this->readPid();
        $running = $pid !== null && $this->isProcessRunning($pid);
        $authFiles = File::exists($this->authPath()) ? File::allFiles($this->authPath()) : [];

        return [
            'checked_at' => now()->toIso8601String(),
            'base_path' => $this->basePath,
            'auth_path' => $this->authPath(),
            'state_path' => $this->statePath(),
            'pid_path' => $this->pidPath(),
            'log_path' => $this->logPath(),
            'state_file_exists' => File::exists($this->statePath()),
            'pid_file_exists' => File::exists($this->pidPath()),
            'log_file_exists' => File::exists($this->logPath()),
            'auth_file_count' => count($authFiles),
            'pid' => $pid,
            'running' => $running,
            'status' => $state['status'] ?? null,
            'message' => $state['message'] ?? null,
            'qr_present' => filled($state['qr'] ?? null),
            'qr_image_present' => filled($state['qr_image'] ?? null),
            'qr_image_length' => isset($state['qr_image']) ? strlen((string) $state['qr_image']) : 0,
            'updated_at' => $state['updated_at'] ?? null,
            'log_tail' => $this->tailLog(),
        ];
    }

    private function ensureDirectories(): void
    {
        File::ensureDirectoryExists($this->basePath);
        File::ensureDirectoryExists($this->authPath());
    }

    private function readState(): array
    {
        if (! File::exists($this->statePath())) {
            return [];
        }

        $contents = File::get($this->statePath());
        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function writeState(array $patch): void
    {
        $state = array_merge($this->defaultState(), $this->readState(), $patch);
        $state['updated_at'] = now()->toIso8601String();

        File::put($this->statePath(), json_encode($state, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function defaultState(): array
    {
        return [
            'status' => 'idle',
            'message' => 'Klik mulai untuk menampilkan QR login WhatsApp.',
            'qr' => null,
            'qr_image' => null,
            'updated_at' => null,
            'pid' => null,
            'running' => false,
        ];
    }

    private function statePath(): string
    {
        return $this->basePath . '/session.json';
    }

    private function pidPath(): string
    {
        return $this->basePath . '/session.pid';
    }

    private function authPath(): string
    {
        return $this->basePath . '/auth';
    }

    private function logPath(): string
    {
        return $this->basePath . '/session.log';
    }

    private function tailLog(int $lines = 80): array
    {
        if (! File::exists($this->logPath())) {
            return [];
        }

        $contents = File::get($this->logPath());
        $logLines = preg_split('/\R/', trim($contents)) ?: [];

        return array_values(array_slice(array_filter($logLines), -$lines));
    }

    private function readPid(): ?int
    {
        if (! File::exists($this->pidPath())) {
            return null;
        }

        $pid = (int) trim((string) File::get($this->pidPath()));

        return $pid > 0 ? $pid : null;
    }

    private function forgetPid(): void
    {
        if (File::exists($this->pidPath())) {
            File::delete($this->pidPath());
        }
    }

    private function isProcessRunning(int $pid): bool
    {
        $process = Process::fromShellCommandline(sprintf('kill -0 %d >/dev/null 2>&1', $pid), base_path());
        $process->run();

        return $process->isSuccessful();
    }
}
