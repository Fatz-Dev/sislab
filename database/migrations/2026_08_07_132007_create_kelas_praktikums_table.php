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
        Schema::create('kelas_praktikums', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas');
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->unsignedInteger('kapasitas')->default(30);
            $table->enum('status', ['draft', 'open', 'closed'])->default('draft')->index();
            $table->foreignId('dosen_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('laboran_id')->unique()->constrained('users')->cascadeOnDelete();
            // unique() -> memastikan aturan bisnis 1 laboran hanya 1 kelas aktif
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
        Schema::dropIfExists('kelas_praktikums');
    }
};
