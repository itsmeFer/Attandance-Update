<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('daftar_izin_karyawan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('alasan');
            $table->string('dokumen'); // Path file surat izin
            $table->date('izin_dari');
            $table->date('izin_sampai');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('daftar_izin_karyawan');
    }
};
