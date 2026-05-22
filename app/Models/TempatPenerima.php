<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
class TempatPenerima extends Model
{
    //
    use HasFactory;
    protected $table = 'tempat_penerima';
    protected $fillable = ['nama', 'alamat'];

    public function buktiPenerimaan(): HasMany
    {
        return $this->hasMany(BuktiKotakDiterima::class);
    }
}
