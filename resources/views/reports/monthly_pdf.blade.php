<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Bulanan Absensi</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Bulanan Absensi</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Lokasi Masuk</th>
                <th>Jam Keluar</th>
                <th>Lokasi Keluar</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendances as $index => $attendance)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $attendance->user->name ?? 'Tidak Diketahui' }}</td>
                <td>{{ $attendance->check_in ? $attendance->check_in->format('Y-m-d') : '-' }}</td>
                <td>{{ $attendance->check_in ? $attendance->check_in->format('H:i:s') : '-' }}</td>
                <td>{{ $attendance->check_in_location ?? '-' }}</td>
                <td>{{ $attendance->check_out ? $attendance->check_out->format('H:i:s') : '-' }}</td>
                <td>{{ $attendance->check_out_location ?? '-' }}</td>
                <td>
                    @if ($attendance->check_in && $attendance->check_out)
                        Lengkap
                    @elseif ($attendance->check_in)
                        Belum Checkout
                    @else
                        Tidak Hadir
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
