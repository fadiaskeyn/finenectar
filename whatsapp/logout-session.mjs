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
const stateDir = path.join(projectRoot, 'storage', 'app', 'whatsapp');
const authDir = path.join(stateDir, 'auth');
const logFile = path.join(stateDir, 'session.log');

const appendLog = async (line) => {
    await fs.mkdir(stateDir, { recursive: true });
    await fs.appendFile(logFile, `${new Date().toISOString()} [logout] ${line}\n`);
};

const getSocketVersion = async () => {
    try {
        const result = await fetchLatestBaileysVersion();
        await appendLog(`Using latest Baileys WA version for logout. version=${JSON.stringify(result.version)} isLatest=${result.isLatest}`);

        return result.version;
    } catch (error) {
        await appendLog(`Failed to fetch latest Baileys version for logout. error=${error?.message || error}`);

        return undefined;
    }
};

const waitForOpenOrClose = async (sock) => new Promise((resolve) => {
    const timeout = setTimeout(() => resolve({ type: 'timeout' }), 12000);

    sock.ev.on('connection.update', (update) => {
        appendLog(
            'Connection update during logout. ' +
            `connection=${update.connection ?? 'none'} ` +
            `hasQr=${Boolean(update.qr)} ` +
            `statusCode=${update.lastDisconnect?.error?.output?.statusCode ?? 'none'}`
        ).catch(() => {});

        if (update.connection === 'open') {
            clearTimeout(timeout);
            resolve({ type: 'open' });
        }

        if (update.connection === 'close') {
            clearTimeout(timeout);
            resolve({
                type: 'close',
                error: update.lastDisconnect?.error?.message ?? null,
                statusCode: update.lastDisconnect?.error?.output?.statusCode ?? null,
            });
        }
    });
});

const main = async () => {
    await fs.mkdir(authDir, { recursive: true });
    await appendLog(`Logout script started. node=${process.version} pid=${process.pid}`);

    const { state, saveCreds } = await useMultiFileAuthState(authDir);
    await appendLog(`Auth state loaded for logout. credsRegistered=${Boolean(state?.creds?.registered)}`);

    if (!state?.creds?.registered) {
        await appendLog('Baileys logout skipped because creds are not registered.');
        return;
    }

    const version = await getSocketVersion();

    const sock = makeWASocket({
        auth: state,
        ...(version ? { version } : {}),
        logger: pino({ level: 'silent' }),
        printQRInTerminal: false,
        browser: Browsers.ubuntu('Fine Nectar'),
    });

    sock.ev.on('creds.update', saveCreds);

    const result = await waitForOpenOrClose(sock);
    await appendLog(`Logout wait result: ${JSON.stringify(result)}`);

    if (result.type !== 'open') {
        await appendLog('Baileys logout could not open socket; auth delete fallback will continue.');
        return;
    }

    await sock.logout('Fine Nectar reset session');
    await appendLog('Baileys sock.logout() completed.');
};

main().catch(async (error) => {
    await appendLog(`Logout script failed. error=${error?.stack || error?.message || error}`);
    console.error(error?.message || error);
    process.exit(1);
});
