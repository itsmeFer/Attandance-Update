<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Izin; // Pastikan ini benar

class Izin extends Model
{
    use HasFactory;

    protected $table = 'daftar_izin_karyawan';

    protected $fillable = [
        'user_id',
        'alasan',
        'dokumen',
        'izin_dari',
        'izin_sampai',
        'status',
        'disetujui_oleh_id', 
        'disetujui_oleh',

    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
