<?php

namespace App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\Pages;

use App\Filament\Resources\Penerima\PenerimaBuktiKotakDiterimas\PenerimaBuktiKotakDiterimaResource;
use App\Models\BuktiKotakDiterima;
use App\Models\KotakMBG;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; // <-- Tambahkan ini
use Illuminate\Support\Facades\Log;  // <-- Tambahkan ini

class ListPenerimaBuktiKotakDiterimas extends ListRecords
{
    protected static ?string $title = "Daftar Bukti Kotak Diterima";
    protected static string $resource = PenerimaBuktiKotakDiterimaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambahkan Bukti Kotak Diterima')
                ->modalHeading('Konfirmasi Penerimaan Kotak MBG')
                ->mutateFormDataUsing(function (array $data, CreateAction $action): array {
                    
                    // 1. Ambil data Kotak MBG berdasarkan kode EKSAK (Strict Matching)
                    $kotak = KotakMBG::where('code', $data['code'])->first();

                    // 2. Validasi: Jika kode tidak ada di database, batalkan penyimpanan
                    if (!$kotak) {
                        Notification::make()
                            ->title('Kode Tidak Valid')
                            ->body("Maaf, kode '{$data['code']}' tidak terdaftar dalam sistem kami.")
                            ->danger()
                            ->persistent()
                            ->send();

                        $action->halt();
                    }

                    // 3. Jika valid, isi foreign keys secara otomatis
                    $data['vendor_id'] = Auth::user()->vendor_id;
                    $data['user_id'] = Auth::id();
                    $data['kotak_mbg_id'] = $kotak->id;

                    // 4. Generate Local Hash sementara (sebagai Local Chain of Trust)
                    $timestamp = now()->timestamp;
                    $payload = $data['user_id'] . $data['kotak_mbg_id'] . $data['code'] . ($data['feedback'] ?? '') . $timestamp;
                    $data['blockchainHash'] = hash('sha256', $payload);

                    return $data;
                })
                // 5. EKSEKUSI UPDATE & INTEGRASI HYPERLEDGER FABRIC SETELAH BUKTI TERSIMPAN
                ->after(function ($record) {
                    // a. Simpan representasi array dari KotakMBG LAMA sebelum statusnya diubah
                    $oldKotakMbgData = $record->kotakMbg ? $record->kotakMbg->toArray() : null;

                    // b. Update status KotakMBG
                    if ($record->kotakMbg) {
                        $record->kotakMbg->update([
                            'status' => 'Diterima'
                        ]);
                    }

                    // c. Susun new_data dan old_data
                    $record->load('kotakMbg');
                    $newData = $record->toArray();
                    $oldData = [
                        'kotak_mbg' => $oldKotakMbgData
                    ];

                    // d. INTEGRASI BLOCKCHAIN: Push Bukti Penerimaan ke Fabric Gateway
                    try {
                        $fabricResponse = Http::timeout(5)->post('http://10.40.3.109:3000/api/invoke/RecordActivity', [
                            'user_id'          => $record->user_id,
                            'subject_id'       => $record->id,
                            'title'            => 'Penerbitan Bukti Penerimaan',
                            'action'           => 'created',
                            'description'      => "Bukti Kotak Diterima valid disubmit oleh user untuk kotak {$record->kotakMbg->code}.",
                            'table_name'       => $record->getTable(),
                            'old_data'         => null, // Null karena ini record insert baru
                            'new_data'         => $newData,
                            'ip_address'       => request()->ip() ?? '127.0.0.1',
                            'user_agent'       => request()->userAgent() ?? 'Filament/System',
                            'location'         => session('user_gps_lat') ? session('user_gps_lat') . ', ' . session('user_gps_lng') : null,
                            'transaction_hash' => $record->blockchainHash // Kirim local hash untuk diverifikasi & disimpan Fabric
                        ]);

                        // Jika Fabric merespons sukses, timpa Local Hash dengan Fabric TxID
                        if ($fabricResponse->successful() && isset($fabricResponse->json()['fabricTxId'])) {
                            $fabricTxId = $fabricResponse->json()['fabricTxId'];
                            
                            // Update record 
                            BuktiKotakDiterima::where('id', $record->id)->update([
                                'blockchainHash' => $fabricTxId
                            ]);
                        } else {
                            Log::warning('Fabric Gateway merespons dengan error saat penerbitan bukti: ' . $fabricResponse->body());
                        }
                    } catch (\Exception $e) {
                        Log::error('Gagal terhubung ke Fabric Gateway: ' . $e->getMessage());
                    }

                    // e. Catat Log Sistem Aktifitas General (akan memicu trigger kedua ke Fabric untuk log audit)
                    KotakMBG::catatLogAktifitas(
                        $record, 
                        'created',
                        'Penambahan Bukti Kotak Diterima',
                        "Bukti Kotak Diterima dengan kode kotak MBG {$record->kotakMbg->code} telah diterima.",
                        $oldData,
                        $newData
                    );
                })
                ->successNotificationTitle('Bukti Penerimaan Berhasil Diverifikasi & Dicatat ke Blockchain'),
        ];
    }
}