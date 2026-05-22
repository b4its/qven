<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalonPenerima extends Model
{
    //
    protected $table = 'calon_penerima';
    protected $fillable = ['vendor_id','instansi_penerima_id','name', 'email', 'nik', 'alamat','status'];
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
