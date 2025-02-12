<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bulanan Absensi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function checkData() {
            let tableRows = document.querySelectorAll("#attendanceTable tbody tr");
            if (tableRows.length === 1 && tableRows[0].classList.contains('no-data')) {
                alert("Tidak ada data absensi untuk tahun yang dipilih.");
            }
        }
    </script>
    <!-- JavaScript Pencarian -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let searchInput = document.getElementById("searchInput");

        searchInput.addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("#attendanceTable tbody tr:not(.no-data)");

            rows.forEach(row => {
                let nameCell = row.cells[1]; // Kolom Nama
                let dateCell = row.cells[2]; // Kolom Tanggal

                if (nameCell && dateCell) {
                    let nameText = nameCell.textContent.toLowerCase();
                    let dateText = dateCell.textContent.toLowerCase();

                    // Cek apakah input cocok dengan nama atau tanggal
                    if (nameText.includes(filter) || dateText.includes(filter)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                }
            });
        });

        document.getElementById("clearSearch").addEventListener("click", function () {
            searchInput.value = "";
            document.querySelectorAll("#attendanceTable tbody tr").forEach(row => {
                row.style.display = "";
            });
        });
    });
</script>

</head>
<body class="bg-gray-100" onload="checkData()">

<!-- Navbar -->
<nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="text-xl font-bold text-gray-800">
    📊 Admin Panel
    </div>
    <div class="space-x-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">Dashboard</a>
        <a href="{{ route('admin.monthly.report') }}" class="text-gray-700 hover:text-blue-600 font-medium">Laporan Bulanan</a>
    </div>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow-md hover:bg-red-700 transition">
            Logout
        </button>
    </form>
</nav>



<!-- Container -->
<div class="container mx-auto mt-8 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-semibold mb-4">📅 Laporan Bulanan Absensi</h2>

    <!-- Filter Tahun -->
    <form method="GET" action="{{ route('admin.monthly.report') }}" class="mb-4 flex items-center space-x-4">
    <label for="year" class="text-gray-700 font-medium">Pilih Tahun:</label>
    <select name="year" class="border rounded px-3 py-1">
        @forelse($years as $year)
            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
        @empty
            <option disabled>Tidak ada data tahun tersedia.</option>
        @endforelse
    </select>
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-600">
        Tampilkan
    </button>
</form>

<!-- Input Pencarian -->
<div class="mb-4 flex items-center space-x-2">
    <input type="text" id="searchInput" placeholder="Cari nama atau tanggal..." class="border rounded px-3 py-1 w-1/3">
    <button onclick="clearSearch()" class="bg-gray-400 text-white px-3 py-1 rounded">Clear</button>
</div>

    <!-- Tombol Export -->
    <div class="mb-4 flex space-x-4">
        <a href="{{ route('admin.monthly-report.pdf', ['year' => request('year')]) }}" 
           class="bg-red-500 text-white px-4 py-2 rounded-lg shadow hover:bg-red-600">
            Export PDF
        </a>
        <a href="{{ route('admin.monthly-report.excel', ['year' => request('year')]) }}" 
        class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">
            Export Excel
        </a>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table id="attendanceTable" class="min-w-full border rounded-lg">
            <thead>
                <tr class="bg-gray-200">
                    <th class="py-2 px-4 border">No</th>
                    <th class="py-2 px-4 border">Nama</th>
                    <th class="py-2 px-4 border">Tanggal</th>
                    <th class="py-2 px-4 border">Jam Masuk</th>
                    <th class="py-2 px-4 border">Lokasi Masuk</th>
                    <th class="py-2 px-4 border">Jam Keluar</th>
                    <th class="py-2 px-4 border">Lokasi Keluar</th>
                    <th class="py-2 px-4 border">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $index => $attendance)
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border">{{ $index + 1 }}</td>
                    <td class="py-2 px-4 border">{{ $attendance->user->name ?? 'Tidak Diketahui' }}</td>
                    <td class="py-2 px-4 border">{{ optional($attendance->check_in)->format('Y-m-d') ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ optional($attendance->check_in)->format('H:i:s') ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ $attendance->check_in_location ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ optional($attendance->check_out)->format('H:i:s') ?? '-' }}</td>
                    <td class="py-2 px-4 border">{{ $attendance->check_out_location ?? '-' }}</td>
                    <td class="py-2 px-4 border">
                        @if ($attendance->check_in && $attendance->check_out)
                            <span class="bg-green-500 text-white px-2 py-1 rounded">Lengkap</span>
                        @elseif ($attendance->check_in)
                            <span class="bg-yellow-500 text-white px-2 py-1 rounded">Belum Checkout</span>
                        @else
                            <span class="bg-red-500 text-white px-2 py-1 rounded">Tidak Hadir</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr class="no-data">
                    <td colspan="8" class="text-center py-4 text-gray-500">Tidak ada data untuk tahun ini</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>