<div>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-gray-100 min-h-screen">
        <!-- Tombol Logout -->
        <form action="{{ route('logout') }}" method="POST" class="absolute top-5 right-5">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                Logout
            </button>
        </form>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-500 text-white p-3 rounded-full">
                    📅
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Karyawan</h3>
                    <p class="text-2xl font-bold">{{ $totalKaryawan }}</p>
                    </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-500 text-white p-3 rounded-full">
                    ✅
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Hari Ini Absen</h3>
                    <p class="text-2xl font-bold">{{ $hadirHariIni }}</p>
                    </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-yellow-500 text-white p-3 rounded-full">
                    ⏳
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Belum Absen</h3>
                    <p class="text-2xl font-bold">{{ $belumAbsen }}</p>

                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-red-500 text-white p-3 rounded-full">
                    ❌
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Terlambat</h3>
                    <p class="text-2xl font-bold">{{ $lateEmployees }}</p>
                    </div>
            </div>
        </div>

        <!-- Tabel Absen Karyawan -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Absensi Karyawan</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-200 rounded-lg overflow-hidden shadow-md">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="px-4 py-3 text-left">Nama</th>
                            <th class="px-4 py-3 text-left">Waktu Masuk</th>
                            <th class="px-4 py-3 text-left">Waktu Keluar</th>
                            <th class="px-4 py-3 text-left">Lokasi Masuk</th>
                            <th class="px-4 py-3 text-left">Lokasi Keluar</th>
                            <th class="px-4 py-3 text-left">Foto Masuk</th>
                            <th class="px-4 py-3 text-left">Foto Keluar</th>
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
                                    <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Foto Masuk" class="w-16 h-16 rounded cursor-pointer shadow-lg hover:scale-110 transition"
                                         onclick="openModal('{{ asset('storage/' . $attendance->check_in_photo) }}')">
                                </td>
                                <td class="px-4 py-3">
                                    @if ($attendance->check_out_photo)
                                        <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" alt="Foto Keluar" class="w-16 h-16 rounded cursor-pointer shadow-lg hover:scale-110 transition"
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
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center transition">
            <div class="bg-white p-4 rounded-lg shadow-xl relative max-w-4xl">
                <img id="modalImage" src="" alt="Foto Karyawan" class="w-full h-auto max-h-[80vh] object-contain">
                <button onclick="closeModal()" class="absolute top-2 right-2 text-white bg-red-600 p-2 rounded-full">X</button>
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
