import fs from "fs";
import path from "path";
import crypto from "crypto";

const PORT = 3000;
const LEDGER_FILE = path.join(import.meta.dir, 'ledger_mbg_audit.json');

// ============================================================================
// INISIALISASI LEDGER BLOCKCHAIN 
// ============================================================================
if (!fs.existsSync(LEDGER_FILE)) {
    fs.writeFileSync(LEDGER_FILE, JSON.stringify([]));
    console.log('[SYSTEM] Ledger blockchain baru berhasil diinisialisasi.');
}

function generateTxId() {
    return crypto.randomBytes(16).toString('hex');
}

console.log(`===================================================`);
console.log(`🚀 API Gateway Hyperledger Fabric (BUN) berjalan di port ${PORT}`);
console.log(`Mendengarkan event Aktifitas & Mutasi Kotak MBG dari Laravel...`);
console.log(`===================================================`);

Bun.serve({
    port: PORT,
    async fetch(req) {
        const url = new URL(req.url);

        const corsHeaders = {
            "Access-Control-Allow-Origin": "*",
            "Access-Control-Allow-Methods": "POST, GET, OPTIONS",
            "Access-Control-Allow-Headers": "Content-Type",
            "Content-Type": "application/json",
        };

        if (req.method === "OPTIONS") {
            return new Response(null, { headers: corsHeaders });
        }

        // --------------------------------------------------------------------
        // ENDPOINT: TRANSAKSI SMART CONTRACT (MENCATAT LOG AKTIFITAS)
        // --------------------------------------------------------------------
        if (req.method === 'POST' && url.pathname === '/api/invoke/RecordActivity') {
            try {
                const payload = await req.json();
                
                console.log(`\n[FABRIC GATEWAY] Menerima transaksi [${payload.action.toUpperCase()}] untuk tabel: ${payload.table_name}`);

                // Validasi data krusial dari Laravel
                if (!payload.transaction_hash || !payload.table_name) {
                    return new Response(JSON.stringify({ error: 'Payload tidak memenuhi standar konsensus (Hash/Table missing)' }), { 
                        status: 400, headers: corsHeaders 
                    });
                }

                const rawLedger = fs.readFileSync(LEDGER_FILE, 'utf-8');
                const ledger = JSON.parse(rawLedger);

                // Verifikasi apakah local hash dari Laravel sudah pernah diinput (Double Spend/Replay Attack prevention)
                const isDuplicate = ledger.some(item => item.asset_data.local_transaction_hash === payload.transaction_hash);
                if (isDuplicate) {
                    console.warn(`[FABRIC GATEWAY] Peringatan: Hash transaksi duplikat terdeteksi!`);
                    return new Response(JSON.stringify({ error: 'Transaksi sudah ada di dalam Ledger' }), { 
                        status: 409, headers: corsHeaders 
                    });
                }

                const fabricTxId = generateTxId();
                const timestamp = new Date().toISOString();
                
                // Menghitung hash dari block sebelumnya untuk rantai kriptografi
                const prevHash = ledger.length > 0 
                    ? crypto.createHash('sha256').update(JSON.stringify(ledger[ledger.length - 1])).digest('hex') 
                    : 'GENESIS BLOCK';

                // Format Block Data
                const newAsset = {
                    fabric_transaction_id: fabricTxId,
                    timestamp: timestamp,
                    actor: {
                        user_id: payload.user_id,
                        ip_address: payload.ip_address,
                        user_agent: payload.user_agent,
                        location: payload.location
                    },
                    asset_data: {
                        subject_id: payload.subject_id,
                        table_name: payload.table_name,
                        action: payload.action,
                        title: payload.title,
                        description: payload.description,
                        state_transition: {
                            old_data: payload.old_data,
                            new_data: payload.new_data
                        },
                        local_transaction_hash: payload.transaction_hash // Hash asli dari Laravel
                    },
                    previous_block_hash: prevHash
                };

                ledger.push(newAsset);
                fs.writeFileSync(LEDGER_FILE, JSON.stringify(ledger, null, 2));

                console.log(`[FABRIC GATEWAY] Transaksi sukses di-commit! Fabric TxID: ${fabricTxId}`);

                return new Response(JSON.stringify({
                    message: 'Successfully committed to Fabric Ledger',
                    fabricTxId: fabricTxId,
                    localHashVerified: payload.transaction_hash,
                    timestamp: timestamp
                }), { status: 200, headers: corsHeaders });

            } catch (error) {
                console.error('[FABRIC GATEWAY] Error memproses transaksi:', error);
                return new Response(JSON.stringify({ error: 'Internal Server Error' }), { 
                    status: 500, headers: corsHeaders 
                });
            }
        }

        // --------------------------------------------------------------------
        // ENDPOINT: QUERY ALL ASSETS (Audit Trail Lengkap)
        // --------------------------------------------------------------------
        if (req.method === 'GET' && url.pathname === '/api/query/GetAllAssets') {
            try {
                const rawLedger = fs.readFileSync(LEDGER_FILE, 'utf-8');
                return new Response(rawLedger, { status: 200, headers: corsHeaders });
            } catch (error) {
                return new Response(JSON.stringify({ error: 'Gagal membaca ledger' }), { 
                    status: 500, headers: corsHeaders 
                });
            }
        }

        // --------------------------------------------------------------------
        // ENDPOINT: QUERY BY LOCAL HASH (Cari menggunakan Hash dari MySQL Laravel)
        // --------------------------------------------------------------------
        if (req.method === 'GET' && url.pathname === '/api/query/VerifyHash') {
            try {
                const localHash = url.searchParams.get('hash');
                
                if (!localHash) {
                    return new Response(JSON.stringify({ error: 'Parameter hash harus disertakan' }), { 
                        status: 400, headers: corsHeaders 
                    });
                }

                const rawLedger = fs.readFileSync(LEDGER_FILE, 'utf-8');
                const ledger = JSON.parse(rawLedger);

                const asset = ledger.find(item => item.asset_data.local_transaction_hash === localHash);

                if (!asset) {
                    return new Response(JSON.stringify({ verified: false, error: 'Data tidak ditemukan atau telah dimanipulasi di lokal' }), { 
                        status: 404, headers: corsHeaders 
                    });
                }

                return new Response(JSON.stringify({ verified: true, data: asset }), { status: 200, headers: corsHeaders });
            } catch (error) {
                return new Response(JSON.stringify({ error: 'Gagal mencari data' }), { 
                    status: 500, headers: corsHeaders 
                });
            }
        }

        // --------------------------------------------------------------------
        // ENDPOINT: QUERY BY FABRIC TXID (Cari menggunakan Hash dari Log Console)
        // --------------------------------------------------------------------
        if (req.method === 'GET' && url.pathname === '/api/query/GetAssetByTxId') {
            try {
                const txId = url.searchParams.get('txid');
                
                if (!txId) {
                    return new Response(JSON.stringify({ error: 'Parameter txid harus disertakan' }), { 
                        status: 400, headers: corsHeaders 
                    });
                }

                const rawLedger = fs.readFileSync(LEDGER_FILE, 'utf-8');
                const ledger = JSON.parse(rawLedger);

                // Mencari berdasarkan fabric_transaction_id yang dicetak di console log
                const asset = ledger.find(item => item.fabric_transaction_id === txId);

                if (!asset) {
                    return new Response(JSON.stringify({ error: 'Transaksi dengan Fabric TxID tersebut tidak ditemukan' }), { 
                        status: 404, headers: corsHeaders 
                    });
                }

                return new Response(JSON.stringify(asset), { status: 200, headers: corsHeaders });
            } catch (error) {
                return new Response(JSON.stringify({ error: 'Gagal mencari data' }), { 
                    status: 500, headers: corsHeaders 
                });
            }
        }

        return new Response(JSON.stringify({ error: "Endpoint Not Found" }), { 
            status: 404, headers: corsHeaders 
        });
    },
});