@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-2">Daftar Karyawan yang Sudah Absen</h2>

        <table class="w-full border-collapse border border-gray-300 mt-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">Nama</th>
                    <th class="border p-2">Waktu Masuk</th>
                    <th class="border p-2">Waktu Keluar</th>
                    <th class="border p-2">Lokasi Masuk</th>
                    <th class="border p-2">Lokasi Keluar</th>
                    <th class="border p-2">Foto Masuk</th>
                    <th class="border p-2">Foto Keluarsss</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr>
                        <td class="border p-2">{{ $attendance->user->name }}</td>
                        <td class="border p-2">{{ $attendance->check_in }}</td>
                        <td class="border p-2">{{ $attendance->check_out ?? '-' }}</td>
                        <td class="border p-2">{{ $attendance->check_in_location }}</td>
                        <td class="border p-2">{{ $attendance->check_out_location ?? '-' }}</td>
                        <td class="border p-2">
                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Foto Masuk" width="50">
                        </td>
                        <td class="border p-2">
                            @if ($attendance->check_out_photo)
                                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" alt="Foto Keluar" width="50">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
