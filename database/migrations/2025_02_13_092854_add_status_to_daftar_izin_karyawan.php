<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('daftar_izin_karyawan', function (Blueprint $table) {
            $table->enum('status', ['draft', 'disetujui', 'ditolak'])->default('draft')->after('izin_sampai');
        });
    }
    
    public function down()
    {
        Schema::table('daftar_izin_karyawan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
    
};
