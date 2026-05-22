<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KandunganGizi extends Model
{
    //
    protected $table = 'kandungan_gizi';
    protected $fillable = [
        'kotak_mbg_id', 'kalori', 'protein', 'lemak', 'karbohidrat', 
        'serat', 'kalsium', 'zat_besi', 'natrium', 'kalium', 'zinc', 
        'detail_lemak', 'detail_vitamin', 'json_lemak', 'json_vitamin'
    ];

    protected $casts = [
        'json_lemak' => 'array',
        'json_vitamin' => 'array',
        // Jika di table kotak_mbgs ada json_gizi, lakukan hal sama di model KotakMBG
    ];
    

    public function kotakMbg(): BelongsTo
    {
        return $this->belongsTo(KotakMbg::class);
    }
}
