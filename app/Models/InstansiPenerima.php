<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstansiPenerima extends Model
{
    use HasFactory;
    
    protected $table = 'instansi_penerima';
    protected $fillable = ['vendor_id', 'user_id', 'name', 'location', 'status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}   