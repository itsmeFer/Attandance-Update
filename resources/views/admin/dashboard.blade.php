<div class="p-6 bg-white rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-4">Dashboard Admin</h1>

    <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Waktu Masuk</th>
                    <th class="border px-4 py-2">Waktu Keluar</th>
                    <th class="border px-4 py-2">Lokasi Masuk</th>
                    <th class="border px-4 py-2">Lokasi Keluar</th>
                    <th class="border px-4 py-2">Foto Masuk</th>
                    <th class="border px-4 py-2">Foto Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendances as $attendance)
                    <tr class="text-center">
                        <td class="border px-4 py-2">{{ $attendance->user->name }}</td>
                        <td class="border px-4 py-2">{{ $attendance->check_in }}</td>
                        <td class="border px-4 py-2">{{ $attendance->check_out ?? '-' }}</td>
                        <td class="border px-4 py-2">{{ $attendance->check_in_location }}</td>
                        <td class="border px-4 py-2">{{ $attendance->check_out_location ?? '-' }}</td>
                        <td class="border px-4 py-2">
                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Foto Masuk" class="w-12 h-12 rounded">
                        </td>
                        <td class="border px-4 py-2">
                            @if ($attendance->check_out_photo)
                                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" alt="Foto Keluar" class="w-12 h-12 rounded">
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
