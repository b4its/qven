<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
class BuktiKotakDiterima extends Model
{
    //
    protected $table = 'bukti_kotak_diterima';
    protected $fillable = ['vendor_id','instansi_penerima_id','user_id', 'kotak_mbg_id', 'code', 'imageUrl','feedback', 'json_analyze_feedback','blockchainHash'];

    public function user(): BelongsTo
    {
        // Eksplisit definisikan kunci
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kotakMbg(): BelongsTo
    {
        // Eksplisit definisikan kunci
        return $this->belongsTo(KotakMBG::class, 'kotak_mbg_id', 'id');
    }

    public function vendor(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(Vendor::class); 
    }
    public function instansiPenerima(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(InstansiPenerima::class); 
    }

}
