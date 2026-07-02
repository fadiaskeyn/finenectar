import {
    Browsers,
    fetchLatestBaileysVersion,
    makeWASocket,
    useMultiFileAuthState,
} from '@whiskeysockets/baileys';
import { webcrypto } from 'node:crypto';
import pino from 'pino';
import path from 'node:path';
import fs from 'node:fs/promises';
import { fileURLToPath } from 'node:url';

if (!globalThis.crypto) {
    globalThis.crypto = webcrypto;
}

const scriptDir = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(scriptDir, '..');
const authDir = path.join(projectRoot, 'storage', 'app', 'whatsapp', 'auth');
const logFile = path.join(projectRoot, 'storage', 'app', 'whatsapp', 'session.log');

const recipient = process.env.WA_TO || '';
const message = process.env.WA_MESSAGE || '';

const ensureAuthDir = async () => {
    await fs.mkdir(authDir, { recursive: true });
};

const appendLog = async (line) => {
    await fs.mkdir(path.dirname(logFile), { recursive: true });
    await fs.appendFile(logFile, `${new Date().toISOString()} [send-message] ${line}\n`);
};

const safeError = (error) => ({
    message: error?.message ?? null,
    name: error?.name ?? null,
    reason: error?.output?.payload?.reason ?? null,
    statusCode: error?.output?.statusCode ?? null,
    stack: error?.stack ? String(error.stack).split('\n').slice(0, 6).join(' | ') : null,
});

const getSocketVersion = async () => {
    try {
        const result = await fetchLatestBaileysVersion();
        await appendLog(`Using latest Baileys WA version for send. version=${JSON.stringify(result.version)} isLatest=${result.isLatest}`);

        return result.version;
    } catch (error) {
        await appendLog(`Failed to fetch latest Baileys version for send. error=${JSON.stringify(safeError(error))}`);

        return undefined;
    }
};

const mask = (value) => {
    const digits = String(value || '').replace(/\D+/g, '');

    if (digits.length <= 6) {
        return digits;
    }

    return `${digits.slice(0, 4)}${'*'.repeat(Math.max(digits.length - 8, 0))}${digits.slice(-4)}`;
};

const waitForOpen = async (sock) => new Promise((resolve, reject) => {
    const timeout = setTimeout(() => reject(new Error('Timeout membuka koneksi WhatsApp.')), 30000);

    sock.ev.on('connection.update', (update) => {
        appendLog(
            'Connection update while sending. ' +
            `connection=${update.connection ?? 'none'} ` +
            `hasQr=${Boolean(update.qr)} ` +
            `statusCode=${update.lastDisconnect?.error?.output?.statusCode ?? 'none'}`
        ).catch(() => {});

        if (update.connection === 'open') {
            clearTimeout(timeout);
            resolve();
        }

        if (update.connection === 'close') {
            clearTimeout(timeout);
            reject(update.lastDisconnect?.error || new Error('Koneksi WhatsApp tertutup.'));
        }
    });
});

const normalizeRecipient = (input) => {
    const digits = input.replace(/\D+/g, '');

    if (!digits) {
        throw new Error('Nomor WhatsApp kosong.');
    }

    let normalized = digits;

    if (normalized.startsWith('0')) {
        normalized = `62${normalized.slice(1)}`;
    }

    if (normalized.startsWith('8')) {
        normalized = `62${normalized}`;
    }

    if (!normalized.startsWith('62')) {
        throw new Error('Nomor WhatsApp harus diawali 62 atau 08.');
    }

    return `${normalized}@s.whatsapp.net`;
};

const main = async () => {
    if (!recipient || !message) {
        throw new Error('WA_TO dan WA_MESSAGE wajib diisi.');
    }

    await appendLog(
        'Send script started. ' +
        `node=${process.version} ` +
        `pid=${process.pid} ` +
        `to=${mask(recipient)} ` +
        `messageLength=${message.length}`
    );

    await ensureAuthDir();
    await appendLog(`Auth dir ready. authDir=${authDir}`);

    const { state, saveCreds } = await useMultiFileAuthState(authDir);
    await appendLog(`Auth state loaded for send. credsRegistered=${Boolean(state?.creds?.registered)}`);
    const version = await getSocketVersion();

    const sock = makeWASocket({
        auth: state,
        ...(version ? { version } : {}),
        logger: pino({ level: 'silent' }),
        printQRInTerminal: false,
        browser: Browsers.ubuntu('Fine Nectar'),
    });

    sock.ev.on('creds.update', async () => {
        await appendLog('Credentials update received while sending.');
        await saveCreds();
    });

    await waitForOpen(sock);
    await appendLog('Connection opened for send.');

    const jid = normalizeRecipient(recipient);
    await sock.sendMessage(jid, { text: message });
    await appendLog(`Message sent. jid=${mask(jid)}`);

    process.exit(0);
};

main().catch((error) => {
    appendLog(`Send script failed. error=${JSON.stringify(safeError(error))}`).catch(() => {});
    console.error(error?.message || error);
    process.exit(1);
});
