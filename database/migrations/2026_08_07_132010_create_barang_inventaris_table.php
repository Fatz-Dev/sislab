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
        Schema::create('barang_inventaris', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->nullable()->unique();
            $table->string('nama_barang');
            $table->string('merk')->nullable();
            $table->string('foto_barang')->nullable();
            $table->foreignId('kategori_id')->constrained('kategori_barangs')->cascadeOnDelete();
            $table->unsignedInteger('stok_baik')->default(0);
            $table->unsignedInteger('stok_rusak_ringan')->default(0);
            $table->unsignedInteger('stok_rusak_berat')->default(0);
            $table->unsignedInteger('stok_hilang')->default(0);
            $table->foreignId('ruangan_id')->nullable()->constrained('ruangans')->nullOnDelete();
            $table->date('tanggal_pengadaan')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_inventaris');
    }
};
