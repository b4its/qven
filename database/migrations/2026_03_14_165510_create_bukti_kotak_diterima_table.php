<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bukti_kotak_diterima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendor')->onDelete('cascade');
            $table->foreignId('instansi_penerima_id')->constrained('instansi_penerima')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kotak_mbg_id')->constrained('kotak_mbg')->onDelete('cascade');
            $table->string('code')->nullable();
            $table->text('feedback')->nullable();
            $table->json('json_analyze_feedback')->nullable();
            $table->text('imageUrl')->nullable();
            $table->string('blockchainHash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_kotak_diterima');
    }
};
