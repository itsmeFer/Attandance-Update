<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Izin Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<!-- Navbar -->
<nav class="bg-white shadow-md py-4 px-6 flex justify-between items-center">
    <div class="text-xl font-bold text-gray-800">
        📝 Admin Panel - Izin Karyawan
    </div>
    <div class="space-x-4">
        <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 font-medium">Dashboard</a>
        <a href="{{ route('admin.monthly-report') }}" class="text-gray-700 hover:text-blue-600 font-medium">Laporan Bulanan</a>
        <a href="{{ route('admin.izin.index') }}" class="text-gray-700 hover:text-blue-600 font-medium">Lihat Izin</a>
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
    <h2 class="text-2xl font-semibold mb-4">📝 Daftar Pengajuan Izin</h2>

    <!-- Input Pencarian -->
    <div class="mb-4 flex items-center space-x-2">
        <input type="text" id="searchInput" placeholder="Cari nama..." class="border rounded px-3 py-2 w-1/3 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <input type="date" id="startDate" class="border rounded px-3 py-2 focus:outline-none">
        <input type="date" id="endDate" class="border rounded px-3 py-2 focus:outline-none">
        <button id="searchButton" class="bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700">Cari</button>
        <button id="clearSearch" class="bg-gray-400 text-white px-3 py-2 rounded hover:bg-gray-500">Clear</button>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table id="attendanceTable" class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
        <thead class="bg-gray-200 text-black">
    <tr>
        <th class="py-2 px-4 border-b">ID</th>
        <th class="py-2 px-4 border-b">User ID</th>
        <th class="py-2 px-4 border">Nama</th>
        <th class="py-2 px-4 border-b">Alasan</th>
        <th class="py-2 px-4 border-b">Dokumen</th>
        <th class="py-2 px-4 border-b">Izin Dari</th>
        <th class="py-2 px-4 border-b">Izin Sampai</th>
        <th class="py-2 px-4 border-b">Status</th>
        <th class="py-2 px-4 border-b">Disetujui Oleh</th>
        <th class="py-2 px-4 border-b">ID Admin</th>
        <th class="py-2 px-4 border-b">Aksi</th>
    </tr>
</thead>
<tbody>
    @forelse ($izin as $data)
        <tr class="hover:bg-gray-100">
            <td class="py-2 px-4 border-b text-center">{{ $data->id }}</td>
            <td class="py-2 px-4 border-b text-center">{{ $data->user_id }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ $data->user->name }}</td>
            <td class="py-2 px-4 border-b">{{ $data->alasan }}</td>
            <td class="py-2 px-4 border-b text-center">
                <a href="{{ Storage::url($data->dokumen) }}" target="_blank">Lihat Dokumen</a>
            </td>
            <td class="py-2 px-4 border-b text-center">{{ $data->izin_dari }}</td>
            <td class="py-2 px-4 border-b text-center">{{ $data->izin_sampai }}</td>
            <td class="py-2 px-4 border-b text-center font-semibold">
                @if($data->status == 'disetujui')
                    <span class="text-green-600">Disetujui</span>
                @elseif($data->status == 'ditolak')
                    <span class="text-red-600">Ditolak</span>
                @else
                    <span class="text-gray-600">Draft</span>
                @endif
            </td>
            <td class="py-2 px-4 border-b text-center">{{ $data->disetujui_oleh ?? '-' }}</td>
<td class="py-2 px-4 border-b text-center">{{ $data->disetujui_oleh_id ?? '-' }}</td>


            <td class="py-2 px-4 border-b text-center">
                <form method="POST" action="{{ route('admin.izin.update', $data->id) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                        ✅ Setujui
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.izin.update', $data->id) }}" class="mt-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="ditolak">
                    <!-- <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                        ❌ Tolak
                    </button> -->
                </form>

                <form method="POST" action="{{ route('admin.izin.update', $data->id) }}" class="mt-1">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="draft">
                    <button type="submit" class="bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">
                        📝 Draft
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr class="no-data">
            <td colspan="11" class="text-center py-3">Belum ada pengajuan izin.</td>
        </tr>
    @endforelse
</tbody>
        </table>
    </div>
</div>

<!-- JavaScript Pencarian -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.getElementById("searchInput");
    let startDate = document.getElementById("startDate");
    let endDate = document.getElementById("endDate");
    let searchButton = document.getElementById("searchButton");

    function filterTable() {
        let filter = searchInput.value.toLowerCase();
        let start = startDate.value ? new Date(startDate.value) : null;
        let end = endDate.value ? new Date(endDate.value) : null;
        let rows = document.querySelectorAll("#attendanceTable tbody tr:not(.no-data)");

        rows.forEach(row => {
            let nameCell = row.cells[2]; // Kolom Nama
            let izinDariCell = row.cells[5]; // Kolom Izin Dari
            let izinDate = new Date(izinDariCell.textContent);

            let nameMatch = nameCell.textContent.toLowerCase().includes(filter);
            let dateMatch = true;

            if (start && end) {
                dateMatch = izinDate >= start && izinDate <= end;
            } else if (start) {
                dateMatch = izinDate >= start;
            } else if (end) {
                dateMatch = izinDate <= end;
            }

            if (nameMatch && dateMatch) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("keyup", filterTable);
    searchButton.addEventListener("click", filterTable);
    
    document.getElementById("clearSearch").addEventListener("click", function () {
        searchInput.value = "";
        startDate.value = "";
        endDate.value = "";
        document.querySelectorAll("#attendanceTable tbody tr").forEach(row => {
            row.style.display = "";
        });
    });
});
</script>

</body>
</html>
