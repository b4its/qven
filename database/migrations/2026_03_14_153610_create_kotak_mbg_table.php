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
        Schema::create('kotak_mbg', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendor')->onDelete('cascade');
            $table->string('code')->unique();
            $table->string('name')->nullable();
            $table->string('status')->nullable();
            $table->text('imagesUrl')->nullable();
            $table->text('deskripsi_gizi')->nullable();
            $table->json('json_gizi')->nullable();
            $table->text('deskripsi_kelayakan')->nullable();
            $table->string('blockchainHash')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kotak_mbg');
    }
};
