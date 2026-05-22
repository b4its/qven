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
        Schema::create('instansi_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->nullable()->constrained('vendor')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('type')->default(1)->comment('1: Pemerintah, 2: Swasta, 3: Lainnya');
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instansi_penerima');
    }
};
