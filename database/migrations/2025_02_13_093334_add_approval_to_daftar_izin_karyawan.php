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
            $table->unsignedBigInteger('disetujui_oleh_id')->nullable()->after('izin_sampai');
            $table->string('disetujui_oleh')->nullable()->after('disetujui_oleh_id');
        });
    }
    
    public function down()
    {
        Schema::table('daftar_izin_karyawan', function (Blueprint $table) {
            $table->dropColumn(['disetujui_oleh_id', 'disetujui_oleh']);
        });
    }
    
};
