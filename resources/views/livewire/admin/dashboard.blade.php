<div class="min-h-screen flex flex-col bg-gray-100">
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight text-center my-4">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="flex items-center space-x-3">
        <span class="text-xl font-bold text-gray-800">📊 Admin Panel</span>
    </div>
    <div class="space-x-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">Dashboard</a>
        <a href="{{ route('admin.monthly.report') }}" class="text-gray-700 hover:text-blue-600 font-medium">Laporan Bulanan</a>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="ml-4">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
            Logout
        </button>
    </form>
</nav>


    <div class="p-6 flex flex-col items-center w-full">
        <!-- Tombol Logout -->


        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6 w-full max-w-6xl">
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="text-3xl">👥</div>
        <div>
            <h3 class="text-lg font-semibold">Total Karyawan</h3>
            <p class="text-2xl font-bold">{{ $totalKaryawan }}</p>
        </div>
    </div>
    <div class="bg-green-500 text-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="text-3xl">✅</div>
        <div>
            <h3 class="text-lg font-semibold">Hari Ini Absen</h3>
            <p class="text-2xl font-bold">{{ $hadirHariIni }}</p>
        </div>
    </div>
    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow-md flex items-center space-x-4">
        <div class="text-3xl">⏳</div>
        <div>
            <h3 class="text-lg font-semibold">Belum Absen</h3>
            <p class="text-2xl font-bold">{{ $belumAbsen }}</p>
        </div>
    </div>
</div>


        <!-- Tabel Daftar Absen Hari Ini -->
        <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-6xl mt-6">
            <h2 class="text-xl font-semibold mb-4">Daftar Absen Hari Ini</h2>
            <table class="w-full border-collapse border border-gray-300 text-center">
                <thead class="bg-gray-200">
                    <tr>
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
                    <tr class="hover:bg-gray-100">
                        <td class="border border-gray-300 px-4 py-2">{{ $attendance->user->name }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in }}</td>
                        <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_in_location }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if ($attendance->check_in_photo)
                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" class="w-16 h-16 object-cover rounded-md cursor-pointer" onclick="openZoom(this)">
                            @else
                            -
                            @endif
                        </td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $attendance->check_out ?? 'Belum Check Out' }}
                        </td>
                        <td class="border border-gray-300 px-4 py-2">{{ $attendance->check_out_location ?? '-' }}</td>
                        <td class="border border-gray-300 px-4 py-2">
                            @if ($attendance->check_out_photo)
                            <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" class="w-16 h-16 object-cover rounded-md cursor-pointer" onclick="openZoom(this)">
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
        document.getElementById("zoomModal").addEventListener("click", function(event) {
            if (event.target === this) {
                closeZoom();
            }
        });
    </script>
</div>