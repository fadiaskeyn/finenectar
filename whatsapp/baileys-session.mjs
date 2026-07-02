import {
    Browsers,
    DisconnectReason,
    fetchLatestBaileysVersion,
    makeWASocket,
    useMultiFileAuthState,
} from '@whiskeysockets/baileys';
import { webcrypto } from 'node:crypto';
import pino from 'pino';
import qrcode from 'qrcode';
import fs from 'node:fs/promises';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

if (!globalThis.crypto) {
    globalThis.crypto = webcrypto;
}

const scriptDir = path.dirname(fileURLToPath(import.meta.url));
const projectRoot = path.resolve(scriptDir, '..');
const stateDir = path.join(projectRoot, 'storage', 'app', 'whatsapp');
const authDir = path.join(stateDir, 'auth');
const stateFile = path.join(stateDir, 'session.json');
const logFile = path.join(stateDir, 'session.log');

const defaultState = {
    status: 'idle',
    message: 'Klik mulai untuk menampilkan QR login WhatsApp.',
    qr: null,
    qr_image: null,
    updated_at: null,
};

const ensureDirectory = async (dirPath) => {
    await fs.mkdir(dirPath, { recursive: true });
};

const appendLog = async (line) => {
    await ensureDirectory(stateDir);
    await fs.appendFile(logFile, `${new Date().toISOString()} ${line}\n`);
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
        await appendLog(`Using latest Baileys WA version. version=${JSON.stringify(result.version)} isLatest=${result.isLatest}`);

        return result.version;
    } catch (error) {
        await appendLog(`Failed to fetch latest Baileys version, using package default. error=${JSON.stringify(safeError(error))}`);

        return undefined;
    }
};

const writeState = async (patch) => {
    await ensureDirectory(stateDir);

    let current = defaultState;

    try {
        const raw = await fs.readFile(stateFile, 'utf8');
        current = {
            ...defaultState,
            ...JSON.parse(raw),
        };
    } catch (error) {
        if (error.code !== 'ENOENT') {
            throw error;
        }
    }

    const nextState = {
        ...current,
        ...patch,
        updated_at: new Date().toISOString(),
    };

    await fs.writeFile(stateFile, `${JSON.stringify(nextState, null, 2)}\n`);
};

const writeSessionState = async (socketState) => {
    const qrImage = socketState.qr ? await qrcode.toDataURL(socketState.qr) : null;

    await writeState({
        status: socketState.status,
        message: socketState.message,
        qr: socketState.qr ?? null,
        qr_image: qrImage,
    });

    if (socketState.status === 'qr') {
        await appendLog('QR updated in session state.');
    }

    if (socketState.status === 'connected') {
        await appendLog('WhatsApp connection opened.');
    }
};

let reconnectAttempts = 0;
let credsRegistered = false;

const connectToWhatsApp = async () => {
    await ensureDirectory(authDir);
    await appendLog(`Preparing auth state. authDir=${authDir}`);

    const { state, saveCreds } = await useMultiFileAuthState(authDir);
    credsRegistered = Boolean(state?.creds?.registered);
    await appendLog(`Auth state loaded. credsRegistered=${credsRegistered}`);
    const version = await getSocketVersion();

    const sock = makeWASocket({
        auth: state,
        ...(version ? { version } : {}),
        logger: pino({ level: 'silent' }),
        printQRInTerminal: false,
        browser: Browsers.ubuntu('Fine Nectar'),
    });

    await appendLog('Baileys socket created.');

    sock.ev.on('creds.update', async () => {
        await appendLog('Credentials update received.');
        await saveCreds();
    });

    sock.ev.on('connection.update', async (update) => {
        const { connection, lastDisconnect, qr } = update;
        const statusCode = lastDisconnect?.error?.output?.statusCode;

        await appendLog(
            'Connection update. ' +
            `connection=${connection ?? 'none'} ` +
            `hasQr=${Boolean(qr)} ` +
            `statusCode=${statusCode ?? 'none'} ` +
            `receivedKeys=${Object.keys(update).join(',')}`
        );

        if (qr) {
            await writeSessionState({
                status: 'qr',
                message: 'Scan QR ini dari WhatsApp di HP.',
                qr,
            });
        }

        if (connection === 'open') {
            reconnectAttempts = 0;
            credsRegistered = true;

            await writeSessionState({
                status: 'connected',
                message: 'WhatsApp sudah terhubung.',
                qr: null,
            });
        }

        if (connection === 'close') {
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;
            const closedBeforeLogin = !credsRegistered && statusCode === 405;

            await appendLog(
                'Connection closed. ' +
                `statusCode=${statusCode ?? 'unknown'} ` +
                `shouldReconnect=${shouldReconnect} ` +
                `closedBeforeLogin=${closedBeforeLogin} ` +
                `reconnectAttempts=${reconnectAttempts} ` +
                `error=${JSON.stringify(safeError(lastDisconnect?.error))}`
            );

            if (closedBeforeLogin) {
                await writeSessionState({
                    status: 'error',
                    message: 'WhatsApp menolak koneksi sebelum QR muncul (405). Reset session lalu mulai login lagi.',
                    qr: null,
                });

                await appendLog('Stopped reconnect loop because 405 happened before creds were registered.');
                process.exit(1);
            }

            if (shouldReconnect) {
                reconnectAttempts += 1;

                if (reconnectAttempts > 5) {
                    await writeSessionState({
                        status: 'error',
                        message: 'Reconnect WhatsApp gagal lebih dari 5 kali. Reset session untuk membuat QR baru.',
                        qr: null,
                    });

                    await appendLog('Stopped reconnect loop after more than 5 attempts.');
                    process.exit(1);
                }

                await writeSessionState({
                    status: 'reconnecting',
                    message: 'Koneksi terputus, mencoba sambung ulang.',
                    qr: null,
                });

                await connectToWhatsApp();
                return;
            }

            await writeSessionState({
                status: 'logged_out',
                message: 'Sesi WhatsApp logout. Jalankan login lagi untuk scan QR baru.',
                qr: null,
            });

            await appendLog('WhatsApp session logged out.');
        }
    });
};

await writeState(defaultState);
await appendLog(
    'WhatsApp session script started. ' +
    `node=${process.version} ` +
    `pid=${process.pid} ` +
    `cwd=${process.cwd()} ` +
    `projectRoot=${projectRoot}`
);

process.on('SIGINT', async () => {
    await writeState({
        status: 'stopped',
        message: 'Session dihentikan manual.',
        qr: null,
        qr_image: null,
    });

    await appendLog('Received SIGINT.');

    process.exit(0);
});

process.on('SIGTERM', async () => {
    await writeState({
        status: 'stopped',
        message: 'Session dihentikan manual.',
        qr: null,
        qr_image: null,
    });

    await appendLog('Received SIGTERM.');

    process.exit(0);
});

connectToWhatsApp().catch(async (error) => {
    await writeState({
        status: 'error',
        message: error?.message || 'Gagal menjalankan session WhatsApp.',
        qr: null,
        qr_image: null,
    });

    await appendLog(`Unhandled error: ${JSON.stringify(safeError(error))}`);

    process.exit(1);
});
