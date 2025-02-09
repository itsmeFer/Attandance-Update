<div class="p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6">Dashboard Admin</h1>
    <!-- Tombol Logout -->
    <form action="{{ route('logout') }}" method="POST" class="absolute top-0 right-0 mt-4 mr-4">
        @csrf
        <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700">
            Logout
        </button>
    </form>

    <div class="p-6 bg-white rounded-lg shadow-lg relative">


    <!-- Tabel Absen Karyawan -->
    <div class="overflow-x-auto bg-gray-50 p-4 rounded-lg shadow-sm">
        <table class="min-w-full border-collapse border border-gray-200">
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
                    <tr class="text-center border-t">
                        <td class="px-4 py-3">{{ $attendance->user->name }}</td>
                        <td class="px-4 py-3">{{ $attendance->check_in }}</td>
                        <td class="px-4 py-3">{{ $attendance->check_out ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $attendance->check_in_location }}</td>
                        <td class="px-4 py-3">{{ $attendance->check_out_location ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <img src="{{ asset('storage/' . $attendance->check_in_photo) }}" alt="Foto Masuk" class="w-16 h-16 rounded cursor-pointer"
                                 onclick="openModal('{{ asset('storage/' . $attendance->check_in_photo) }}')">
                        </td>
                        <td class="px-4 py-3">
                            @if ($attendance->check_out_photo)
                                <img src="{{ asset('storage/' . $attendance->check_out_photo) }}" alt="Foto Keluar" class="w-16 h-16 rounded cursor-pointer"
                                     onclick="openModal('{{ asset('storage/' . $attendance->check_out_photo) }}')">
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk memperbesar gambar -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white p-4 rounded-lg shadow-xl relative max-w-4xl">
            <img id="modalImage" src="" alt="Foto Karyawan" class="w-full h-auto max-h-[80vh] object-contain">
            <button onclick="closeModal()" class="absolute top-0 right-0 mt-2 mr-2 text-white bg-red-600 p-2 rounded-full">X</button>
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
