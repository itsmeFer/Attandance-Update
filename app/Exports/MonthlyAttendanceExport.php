<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyAttendanceExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return Attendance::whereMonth('check_in', $this->month)->get();
    }

    public function headings(): array
    {
        return [
            ['Laporan Absensi Bulanan PT'], // Judul
            [''], // Baris kosong
            ['No', 'Nama', 'Tanggal', 'Jam Masuk', 'Lokasi Masuk', 'Jam Keluar', 'Lokasi Keluar', 'Status'] // Header tabel
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->id,
            $attendance->user->name ?? 'Tidak Diketahui',
            optional($attendance->check_in)->format('Y-m-d') ?? '-',
            optional($attendance->check_in)->format('H:i:s') ?? '-',
            $attendance->check_in_location ?? '-',
            optional($attendance->check_out)->format('H:i:s') ?? '-',
            $attendance->check_out_location ?? '-',
            $attendance->check_in && $attendance->check_out ? 'Lengkap' :
                ($attendance->check_in ? 'Belum Checkout' : 'Tidak Hadir'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Format judul
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Format header tabel
            3 => ['font' => ['bold' => true]],
        ];
    }
}
