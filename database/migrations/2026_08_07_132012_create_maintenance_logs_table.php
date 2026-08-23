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
        Schema::create('maintenance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_inventaris')->cascadeOnDelete();
            $table->foreignId('laboran_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal');
            $table->text('deskripsi');
            $table->enum('status', ['dilaporkan', 'diproses', 'selesai'])->default('dilaporkan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
