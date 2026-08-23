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
        Schema::table('tugas_laporans', function (Blueprint $table) {
            $table->unsignedBigInteger('jadwal_id')->nullable()->after('kelas_praktikum_id');
            $table->foreign('jadwal_id')->references('id')->on('jadwals')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tugas_laporans', function (Blueprint $table) {
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');
        });
    }
};
