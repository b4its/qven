<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aktifitas extends Model
{
    //
    protected $table = 'aktifitas';
    protected $fillable = [
        'vendor_id',
        'user_id', 'subject_id',
        'title', 'action', 
        'description', 'table_name', 
        'old_data', 'new_data', 
        'ip_address', 'user_agent', 
        'location', 'transaction_hash'
        ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        // Jika nama kolom di tabel users adalah vendor_id
        return $this->belongsTo(Vendor::class); 
    }
}


            // $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // $table->string('title')->nullable();
            // $table->string('action')->nullable();
            // $table->text('description')->nullable();
            // $table->string('table_name')->nullable();
            // $table->json('old_data')->nullable();
            // $table->json('new_data')->nullable();
            // $table->string('ip_address')->nullable();
            // $table->string('user_agent')->nullable();
            // $table->string('location')->nullable();
            // $table->text('transaction_hash')->nullable();