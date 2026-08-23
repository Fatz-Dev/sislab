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
        Schema::create('modul_praktikums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_praktikum_id')->constrained('kelas_praktikums')->cascadeOnDelete();
            $table->string('judul');
            $table->string('file_pdf'); // path penyimpanan file
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('tanggal_upload')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modul_praktikums');
    }
};
