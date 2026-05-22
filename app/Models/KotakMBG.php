<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;
use App\Models\Aktifitas;

class KotakMBG extends Model
{
    use HasFactory;

    protected $table = 'kotak_mbg';
    
    protected $fillable = [
        'vendor_id',
        'code', 
        'name', 
        'status', 
        'imagesUrl', 
        'deskripsi_gizi', 
        'json_gizi', 
        'deskripsi_kelayakan', 
        'blockchainHash'
    ];

    protected $casts = [
        'json_gizi' => 'array',
    ];
    public function vendor(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(Vendor::class); 
    }
    public function kandunganGizi(): HasOne
    {
        return $this->hasOne(KandunganGizi::class, 'kotak_mbg_id');
    }

    public function buktiPenerimaan(): HasOne
    {
        // Tambahkan 'kotak_mbg_id' sebagai parameter kedua
        return $this->hasOne(BuktiKotakDiterima::class, 'kotak_mbg_id');
    }

    protected static function boot()
    {
        parent::boot(); 

        // 1. Log saat data dibuat (Create)
        static::created(function ($kotakMbg) {
            self::catatLogAktifitas(
                $kotakMbg,
                'created',
                'Pembuatan Kotak MBG',
                "Kotak MBG dengan code {$kotakMbg->code} telah dibuat.",
                null,
                $kotakMbg->toArray()
            );
        });

        // 2. Log saat data diubah (Update)
        static::updated(function ($kotakMbg) {
            // Hanya catat log jika benar-benar ada data yang berubah (Dirty)
            if ($kotakMbg->isDirty()) {
                // Ambil hanya kolom yang berubah untuk efisiensi JSON
                $perubahanBaru = $kotakMbg->getChanges();
                // Ekstrak data lama hanya untuk kolom yang mengalami perubahan
                $dataLama = array_intersect_key($kotakMbg->getOriginal(), $perubahanBaru);

                self::catatLogAktifitas(
                    $kotakMbg,
                    'updated',
                    'Pembaruan Kotak MBG',
                    "Data Kotak MBG dengan code {$kotakMbg->code} telah diperbarui.",
                    $dataLama,
                    $perubahanBaru
                );
            }
        });

        // 3. Log saat data dihapus (Delete)
        static::deleted(function ($kotakMbg) {
            self::catatLogAktifitas(
                $kotakMbg,
                'deleted',
                'Penghapusan Kotak MBG',
                "Kotak MBG dengan code {$kotakMbg->code} telah dihapus.",
                $kotakMbg->getOriginal(),
                null
            );
        });
    }

    /**
     * Method helper sentral untuk memproses dan mencatat log ke database
     */
/**
     * Method helper sentral untuk memproses dan mencatat log ke database
     */
    public static function catatLogAktifitas($model, $action, $title, $description, $oldData, $newData)
    {
        $ip = request()->ip() ?? '127.0.0.1';
        
        $lat = session('user_gps_lat');
        $lng = session('user_gps_lng');

        if ($lat && $lng) {
            $kordinat = "{$lat}, {$lng}";
        } else {
            $ipForLocation = ($ip === '127.0.0.1' || $ip === '::1') ? '8.8.8.8' : $ip;
            try {
                $position = Location::get($ipForLocation);
                $kordinat = $position ? "{$position->latitude}, {$position->longitude}" : null;
            } catch (\Exception $e) {
                $kordinat = null;
            }
        }

        $userId = auth()->check() ? auth()->id() : 1; 
        $vendorId = auth()->check() ? Auth::user()->vendor_id : null; 
        $timestamp = now()->timestamp;
        
        $oldDataJson = $oldData ? json_encode($oldData) : 'null';
        $newDataJson = $newData ? json_encode($newData) : 'null';

        // Chain of Trust: Local Hash
        $transactionHash = hash('sha256', $userId . $model->id . $action . $oldDataJson . $newDataJson . $timestamp);

        // 1. Simpan ke database MySQL lokal
        $aktifitas = Aktifitas::create([
            'vendor_id'        => $vendorId,
            'user_id'          => $userId,
            'subject_id'       => $model->id,
            'title'            => $title,
            'action'           => $action,
            'description'      => $description,
            'table_name'       => $model->getTable(),
            'old_data'         => $oldData,
            'new_data'         => $newData,
            'ip_address'       => $ip,
            'user_agent'       => request()->userAgent() ?? 'System/CLI',
            'location'         => $kordinat,
            'transaction_hash' => $transactionHash,
        ]);

        // 2. Push ke API Gateway Hyperledger Fabric (Bun Server)
        try {
            $fabricResponse = Http::timeout(5)->post('http://10.40.3.109:3000/api/invoke/RecordActivity', [
                'user_id'          => $userId,
                'subject_id'       => $model->id,
                'title'            => $title,
                'action'           => $action,
                'description'      => $description,
                'table_name'       => $model->getTable(),
                'old_data'         => $oldData,
                'new_data'         => $newData,
                'ip_address'       => $ip,
                'user_agent'       => request()->userAgent() ?? 'System/CLI',
                'location'         => $kordinat,
                'transaction_hash' => $transactionHash,
            ]);

            if (!$fabricResponse->successful()) {
                Log::error('Gagal mem-push data ke Blockchain: ' . $fabricResponse->body());
            }
        } catch (\Exception $e) {
            Log::error('Fabric Gateway Unreachable: ' . $e->getMessage());
        }
    }
}