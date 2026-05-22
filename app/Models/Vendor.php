<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vendor extends Model
{
    //
    use HasFactory;
    protected $table = 'vendor';
    protected $fillable = ['user_id', 'instansi_penerima_id','name', 'singkatan','lokasi', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


