<div class="min-h-screen flex flex-col">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="flex-1 p-6 bg-gray-100 flex flex-col items-center">
        <!-- Tombol Logout -->
        <form action="{{ route('logout') }}" method="POST" class="absolute top-5 right-5">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
                Logout
            </button>
        </form>

        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

        <!-- Tombol Laporan Bulanan -->
        <a href="{{ route('admin.monthly.report') }}">Laporan Bulanan</a>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 w-full max-w-6xl">
            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-blue-500 text-white p-3 rounded-full">📅</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Total Karyawan</h3>
                    <p class="text-2xl font-bold">{{ $totalKaryawan }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-green-500 text-white p-3 rounded-full">✅</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Hari Ini Absen</h3>
                    <p class="text-2xl font-bold">{{ $hadirHariIni }}</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-md flex items-center space-x-4">
                <div class="bg-yellow-500 text-white p-3 rounded-full">⏳</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-700">Belum Absen</h3>
                    <p class="text-2xl font-bold">{{ $belumAbsen }}</p>
                </div>
            </div>
        </div>

        <!-- Tabel Daftar Absen Hari Ini -->
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-6xl">
            <h2 class="text-xl font-semibold mb-4">Daftar Absen Hari Ini</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2">Nama</th>
                        <th class="border border-gray-300 px-4 py-2">Check In</th>
                        <th class="border border-gray-300 px-4 py-2">Lokasi Check In</th>
                        <th class="border border-gray-300 px-4 py-2">Foto Check In</th>
                        <th class="border border-gray-300 px-4 py-2">Check Out</th>
                        <th class="border border-gray-300 px-4 py-2">Lokasi Check Out</th>
                        <th class="border border-gray-300 px-4 py-2">Foto Check Out</th>
                        <th class="border border-gray-300 px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $attendance)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $attendance->user->name }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in_location }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($attendance->check_in_photo)
                                    <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" 
                                         alt="Check-in Photo" 
                                         class="w-16 h-16 object-cover rounded-md cursor-pointer"
                                         onclick="openZoom(this)">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($attendance->check_out)
                                    {{ $attendance->check_out }}
                                @else
                                    Belum Check Out
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                {{ $attendance->check_out_location ?? '-' }}
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($attendance->check_out_photo)
                                    <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" 
                                         alt="Check-out Photo" 
                                         class="w-16 h-16 object-cover rounded-md cursor-pointer"
                                         onclick="openZoom(this)">
                                @else
                                    -
                                @endif
                            </td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($attendance->check_in && $attendance->check_out)
                                    <span class="text-green-600 font-bold">Lengkap</span>
                                @else
                                    <span class="text-red-600 font-bold">Belum Lengkap</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="border border-gray-300 px-4 py-2 text-center">Belum ada data absen hari ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Zoom -->
    <div id="zoomModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center hidden">
        <div class="relative">
            <span class="absolute top-2 right-2 text-white text-3xl cursor-pointer p-2" onclick="closeZoom()">&times;</span>
            <img id="zoomImage" class="max-w-full max-h-screen rounded-lg shadow-lg">
        </div>
    </div>

    <!-- JavaScript Zoom -->
    <script>
    function openZoom(imgElement) {
        document.getElementById("zoomImage").src = imgElement.src;
        document.getElementById("zoomModal").classList.remove("hidden");
    }

    function closeZoom() {
        document.getElementById("zoomModal").classList.add("hidden");
    }

    // Tutup modal saat klik di luar gambar
    document.getElementById("zoomModal").addEventListener("click", function(event) {
        if (event.target === this) {
            closeZoom();
        }
    });
    </script>
</div>
