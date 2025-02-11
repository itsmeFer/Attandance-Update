<div>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Tombol Logout -->
        <form action="{{ route('logout') }}" method="POST" class="absolute top-5 right-5">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-5 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                Logout
            </button>
        </form>

        <h1 class="text-4xl font-extrabold text-gray-800 mb-6">Dashboard Admin</h1>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            @php
                $stats = [
                    ['color' => 'blue', 'icon' => '📅', 'title' => 'Total Karyawan', 'value' => $totalKaryawan],
                    ['color' => 'green', 'icon' => '✅', 'title' => 'Hari Ini Absen', 'value' => $hadirHariIni],
                    ['color' => 'yellow', 'icon' => '⏳', 'title' => 'Belum Absen', 'value' => $belumAbsen],
                    ['color' => 'red', 'icon' => '❌', 'title' => 'Terlambat', 'value' => $lateEmployees],
                ];
            @endphp

            @foreach ($stats as $stat)
                <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4 border-l-4 border-{{ $stat['color'] }}-500 hover:shadow-lg transition">
                    <div class="bg-{{ $stat['color'] }}-500 text-white p-3 rounded-full text-2xl">
                        {{ $stat['icon'] }}
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">{{ $stat['title'] }}</h3>
                        <p class="text-3xl font-bold">{{ $stat['value'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Tabel Absen Karyawan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Absensi Karyawan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            @foreach (['Nama', 'Waktu Masuk', 'Waktu Keluar', 'Lokasi Masuk', 'Lokasi Keluar', 'Foto Masuk', 'Foto Keluar'] as $header)
                                <th class="px-4 py-3 text-left">{{ $header }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $attendance)
                            <tr class="border-t hover:bg-gray-100 transition">
                                <td class="px-4 py-3">{{ $attendance->user->name }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_in }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_out ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_in_location }}</td>
                                <td class="px-4 py-3">{{ $attendance->check_out_location ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" 
                                         alt="Foto Masuk" 
                                         class="w-16 h-16 rounded shadow-md cursor-pointer hover:scale-110 transition" 
                                         onclick="openModal('{{ asset('storage/' . $attendance->check_in_photo) }}')">
                                </td>
                                <td class="px-4 py-3">
                                    @if ($attendance->check_out_photo)
                                        <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" 
                                             alt="Foto Keluar" 
                                             class="w-16 h-16 rounded shadow-md cursor-pointer hover:scale-110 transition" 
                                             onclick="openModal('{{ asset('storage/' . $attendance->check_out_photo) }}')">
                                    @else
                                        <span class="text-gray-500">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal untuk memperbesar gambar -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center transition">
            <div class="bg-white p-6 rounded-lg shadow-xl relative max-w-4xl">
                <img id="modalImage" src="" alt="Foto Karyawan" class="w-full h-auto max-h-[80vh] object-contain">
                <button onclick="closeModal()" class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full">X</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>
</div>
