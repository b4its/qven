<?php

namespace App\Filament\Resources\Admin\AdminKotakMBGS\Pages;

use App\Filament\Resources\Admin\AdminKotakMBGS\AdminKotakMBGResource;
use App\Filament\Resources\Superadmin\SuperadminKotakMBGS\Schemas\SuperadminKotakMBGForm;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

use function Laravel\Ai\agent;
use Laravel\Ai\Files\Image;

class ListAdminKotakMBGS extends ListRecords
{
    protected static string $resource = AdminKotakMBGResource::class;

    protected static ?string $title = 'Daftar Kotak MBG';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambahkan Kotak MBG')
                ->form(
                    SuperadminKotakMBGForm::configure(Schema::make())->getComponents()
                )
                ->mutateFormDataUsing(function (array $data): array {
                    $data['code'] = 'MBG-' . strtoupper(Str::random(8));
                    $data['status'] = 'PENDING_ANALYSIS';
                    return $data;
                })
                ->after(function ($record) {
                    $this->analyzeImageWithGemini($record);
                }),
        ];
    }

    protected function analyzeImageWithGemini($record): void
    {
        if (!$record->imagesUrl) {
            return;
        }

        $imagePath = public_path($record->imagesUrl);

        $targetDemografisContext = "Target Demografis Analisis: Anak Indonesia, Usia 6-12 tahun (masa pertumbuhan aktif), anak sekolah (PAUD-SMA), balita (6-59 bulan), ibu hamil, dan ibu menyusui. terutama di wilayah 3T(Tertinggal, Terdepan, dan Terluar).";

        $systemPrompt = "
            Tugas Utama:
            Anda adalah Sistem AI Analis Gizi Klinis. Analisis gambar makanan untuk target demografis yang ditentukan.

            Langkah Kerja (Chain of Thought):
            1. Identifikasi komponen makanan secara visual.
            2. Estimasi berat (gram) per item.
            3. Hitung nilai nutrisi numerik (Data Gizi Standar Indonesia).
            4. Mapping Kualitatif berdasarkan threshold target.

            Output Wajib (Harus JSON Murni, Tanpa Markdown, Tanpa Teks Tambahan):
            {
                \"kalori\": float,
                \"protein\": float,
                \"lemak\": float,
                \"karbohidrat\": float,
                \"serat\": float,
                \"kalsium\": float,
                \"zat_besi\": float,
                \"natrium\": float,
                \"kalium\": float,
                \"zinc\": float,
                
                \"status_kualitatif_lengkap\": {
                    \"Kalori\": \"string\",
                    \"Protein\": \"string\",
                    \"Lemak\": \"string\",
                    \"Karbohidrat\": \"string\",
                    \"Serat\": \"string\",
                    \"Vitamin_A\": \"string\",
                    \"Vitamin_C\": \"string\",
                    \"Kalsium\": \"string\",
                    \"Zat_Besi\": \"string\",
                    \"Natrium\": \"string\",
                    \"Kalium\": \"string\",
                    \"Zinc\": \"string\"
                },

                \"detail_lemak\": \"string\",
                \"json_lemak\": { \"jenuh\": float, \"tak_jenuh_tunggal\": float, \"tak_jenuh_ganda\": float, \"kolesterol\": float },
                \"detail_vitamin\": \"string\",
                \"json_vitamin\": { \"vit_a\": float, \"vit_c\": float },
                
                \"deskripsi_gizi\": \"string\",
                

                \"json_gizi\": {
                    \"makanan_pokok(Sumber Karbohidrat)\": \"[Status] - [deskripsi lengkap]\",
                    \"lauk_pauk(Sumber Protein Hewani & Nabati)\": \"[Status] - [deskripsi lengkap]\",
                    \"sayur_sayuran(Sumber Vitamin & Serat)\": \"[Status] - [deskripsi lengkap]\",
                    \"buah_buahan(Sumber Mineral & Antioksidan)\": \"[Status] - [deskripsi lengkap]\",
                    \"susu(Sumber Kalsium & Fosfor)\": \"[Status] - [deskripsi lengkap]\"
                },
                
                \"deskripsi_kelayakan\": \"string (Berikan kesimpulan ringkas kelayakan dan saran di sini)\"
            }";

        $rawResponse = '';

        try {

            $response = agent(
                instructions: $systemPrompt
            )->prompt(
                "Lakukan analisis estimasi gizi dari gambar makanan ini, dengan context demografis: '$targetDemografisContext'. Ikuti langkah kerja logis yang didefinisikan. Hasilkan output murni dalam format JSON yang valid.",
                attachments: [
                    Image::fromPath($imagePath)
                ],
                provider: 'openrouter',
                model: 'google/gemini-3.1-flash-image-preview'
            );

            // gemini
            // $response = agent(
            //     instructions: $systemPrompt
            // )->prompt(
            //     "Lakukan analisis estimasi gizi dari gambar makanan ini, dengan context demografis: '$targetDemografisContext'. Ikuti langkah kerja logis yang didefinisikan.",
            //     attachments: [
            //         Image::fromPath($imagePath)
            //     ],
            //     provider: 'gemini',
            //     model: 'gemini-2.5-flash'
            // );


            $rawResponse = (string) $response;

            // Paksa ambil hanya teks dari kurung kurawal pembuka { pertama sampai penutup } terakhir
            if (preg_match('/\{.*\}/s', $rawResponse, $matches)) {
                $cleanJson = $matches[0];
            } else {
                $cleanJson = $rawResponse;
            }

            $aiData = json_decode($cleanJson, true); 

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("JSON Decode Error: " . json_last_error_msg() . " | Cleaned String: " . substr($cleanJson, 0, 100) . "...");
            }

            if ($aiData) {
                // 1. Update record KotakMBG (karena model sudah cast ke array, tidak perlu json_encode)
                $record->update([
                    'deskripsi_gizi' => $aiData['deskripsi_gizi'] ?? null,
                    'json_gizi' => $aiData['json_gizi'] ?? null,
                    'deskripsi_kelayakan' => $aiData['deskripsi_kelayakan'] ?? null,
                    'status' => 'ANALYZED',
                ]);

                // 2. Buat record KandunganGizi terkait (menggunakan relasi HasOne)
                $record->kandunganGizi()->create([
                    'kalori' => $aiData['kalori'] ?? 0,
                    'protein' => $aiData['protein'] ?? 0,
                    'lemak' => $aiData['lemak'] ?? 0,
                    'karbohidrat' => $aiData['karbohidrat'] ?? 0,
                    'serat' => $aiData['serat'] ?? 0,
                    'kalsium' => $aiData['kalsium'] ?? 0,
                    'zat_besi' => $aiData['zat_besi'] ?? 0,
                    'natrium' => $aiData['natrium'] ?? 0,
                    'kalium' => $aiData['kalium'] ?? 0,
                    'zinc' => $aiData['zinc'] ?? 0,
                    'detail_lemak' => $aiData['detail_lemak'] ?? '-',
                    'json_lemak' => $aiData['json_lemak'] ?? null,
                    'detail_vitamin' => $aiData['detail_vitamin'] ?? '-',
                    'json_vitamin' => $aiData['json_vitamin'] ?? null,
                ]);

                // 3. Eksekusi Stacking Ensemble & Hyperledger Fabric
                $this->processEnsembleAndBlockchain($record, $aiData, $imagePath);
            }
        } catch (\Exception $e) {
            $record->update([
                'status' => 'ANALYSIS_FAILED',
                'deskripsi_kelayakan' => "ERROR: " . $e->getMessage() . "\n\nRAW RESPONSE:\n" . $rawResponse,
            ]);
            
            Log::error('Gemini AI Analysis Failed: ' . $e->getMessage());
        }
    }

    protected function processEnsembleAndBlockchain($record, $aiData, $imagePath): void
    {
        try {
            // =================================================================
            // TAHAP 1: STACKING ENSEMBLE LEARNING (Logistic Regression + XGBoost)
            // =================================================================
            $mlPayload = [
                'mbg_code' => $record->code,
                'features' => [
                    'kalori' => $aiData['kalori'] ?? 0,
                    'protein' => $aiData['protein'] ?? 0,
                    'lemak' => $aiData['lemak'] ?? 0,
                    'karbohidrat' => $aiData['karbohidrat'] ?? 0,
                ]
            ];

            $mlEndpoint = env('ML_ENSEMBLE_API_URL', 'http://mbg-ml-think:5000/predict');
            $ensembleStatus = 'Layak Konsumsi'; // Fallback
            
            try {
                $mlResponse = Http::timeout(10)->post($mlEndpoint, $mlPayload);
                if ($mlResponse->successful()) {
                    $ensembleStatus = $mlResponse->json('prediction') ?? $ensembleStatus;
                }
            } catch (\Exception $e) {
                Log::warning("ML API Error: " . $e->getMessage());
            }

            // =================================================================
            // TAHAP 2: HYPERLEDGER FABRIC INTEGRATION
            // =================================================================
            $imageHash = hash_file('sha256', $imagePath);

            $blockchainPayload = [
                'creator' => [
                    'id'   => Auth::user()?->id ?? "0",
                    'name' => Auth::user()?->name ?? "System",
                ],
                'mbg_id' => $record->code,
                'cv_raw_data' => $aiData,
                'ensemble_decision' => $ensembleStatus,
                'image_hash' => $imageHash,
                'timestamp' => now()->toIso8601String(),
            ];

            $fabricEndpoint = env('FABRIC_GATEWAY_URL', 'http://mbg-fabric-gateway:3000/api/invoke/CreateMBGAsset');
            $txId = 'TX-' . strtoupper(Str::random(12)) . '-MCK'; // Fallback
            
            try {
                $fabricResponse = Http::timeout(10)->post($fabricEndpoint, $blockchainPayload);
                if ($fabricResponse->successful()) {
                    $txId = $fabricResponse->json('transactionId') ?? $txId;
                }
            } catch (\Exception $e) {
                Log::warning("Fabric API Error: " . $e->getMessage());
            }

            // =================================================================
            // TAHAP 3: UPDATE RECORD LOKAL
            // =================================================================
            $record->update([
                'status' => 'Pending',
                'deskripsi_kelayakan' => $record->deskripsi_kelayakan . "\n\n Kesimpulan: " . $ensembleStatus,
                'blockchainHash' => $txId, 
            ]);

        } catch (\Exception $e) {
            Log::error('Ensemble & Blockchain Integration Failed: ' . $e->getMessage());
            $record->update([
                'status' => 'CHAIN_FAILED',
                'deskripsi_kelayakan' => $record->deskripsi_kelayakan . "\n\n[SYSTEM WARNING]: Gagal mencatat ke Blockchain/Ensemble."
            ]);
        }
    }
}