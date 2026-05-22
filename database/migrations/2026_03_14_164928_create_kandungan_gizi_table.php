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
        Schema::create('kandungan_gizi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kotak_mbg_id')->nullable()->constrained('kotak_mbg')->onDelete('cascade');
            $table->decimal('kalori', 8, 2)->nullable();
            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('lemak', 8, 2)->nullable();
            $table->decimal('karbohidrat', 8, 2)->nullable();
            $table->decimal('serat', 8, 2)->nullable();
            $table->decimal('kalsium', 8, 2)->nullable();
            $table->decimal('zat_besi', 8, 2)->nullable();
            $table->decimal('natrium', 8, 2)->nullable();
            $table->decimal('kalium', 8, 2)->nullable();
            $table->decimal('zinc', 8, 2)->nullable();
            $table->text('detail_lemak')->nullable();
            $table->json('json_lemak')->nullable();
            $table->text('detail_vitamin')->nullable();
            $table->json('json_vitamin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kandungan_gizi');
    }
};
