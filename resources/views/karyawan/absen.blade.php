@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6 mt-10">
        <h1 class="text-2xl font-bold text-center text-gray-700 mb-4">Absen Karyawan</h1>

        @php
            $user = Auth::user();
            $today = now()->toDateString();
            $attendance = \App\Models\Attendance::where('user_id', $user->id)
                ->whereDate('check_in', $today)
                ->first();
        @endphp

        @if (!$attendance)
            <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="enableLocationInput()">
                @csrf
                <div>
                    <label class="block text-gray-600 font-semibold">📍 Lokasi Masuk:</label>
                    <input type="text" id="location" name="location" required readonly disabled 
                        class="w-full border rounded-lg p-2 bg-gray-100 cursor-not-allowed">
                    <p id="coordinate" class="text-sm text-gray-500 mt-1">🔄 Mengambil lokasi...</p>
                </div>

                <div>
                    <label class="block text-gray-600 font-semibold">📷 Foto Masuk:</label>
                    <input type="file" name="photo" accept="image/*" required class="w-full border rounded-lg p-2">
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition">
                    ✅ Absen Masuk
                </button>
            </form>
        @elseif ($attendance && !$attendance->check_out)
            <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="enableLocationInput()">
                @csrf
                <div>
                    <label class="block text-gray-600 font-semibold">📍 Lokasi Keluar:</label>
                    <input type="text" id="location" name="location" required readonly disabled
                        class="w-full border rounded-lg p-2 bg-gray-100 cursor-not-allowed">
                    <p id="coordinate" class="text-sm text-gray-500 mt-1">🔄 Mengambil lokasi...</p>
                </div>

                <div>
                    <label class="block text-gray-600 font-semibold">📷 Foto Keluar:</label>
                    <input type="file" name="photo" accept="image/*" required class="w-full border rounded-lg p-2">
                </div>

                <button type="submit" class="w-full bg-red-600 text-white font-semibold py-2 rounded-lg hover:bg-red-700 transition">
                    ❌ Absen Keluar
                </button>
            </form>
        @else
            <div class="text-center text-gray-700">
                <p class="text-lg font-semibold">✅ Anda sudah menyelesaikan absen masuk dan keluar.</p>
                <p class="text-sm text-gray-500">Silakan absen besok lagi. ⏳</p>
            </div>
        @endif
    </div>

    <script>
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        let locationInput = document.getElementById("location");
                        let coordinateText = document.getElementById("coordinate");

                        let latitude = position.coords.latitude;
                        let longitude = position.coords.longitude;

                        locationInput.value = latitude + ", " + longitude;
                        locationInput.disabled = false; // Aktifkan agar bisa dikirim ke backend
                        coordinateText.innerHTML = `📍 Koordinat: <b>${latitude}, ${longitude}</b>`;
                    },
                    function (error) {
                        console.error("Error mendapatkan lokasi:", error);
                        document.getElementById("location").value = "Tidak dapat mengambil lokasi";
                        document.getElementById("coordinate").innerText = "⚠️ Gagal mendapatkan lokasi.";
                    }
                );
            } else {
                document.getElementById("location").value = "Geolocation tidak didukung";
                document.getElementById("coordinate").innerText = "⚠️ Geolocation tidak didukung oleh perangkat ini.";
            }
        }

        function enableLocationInput() {
            document.getElementById("location").disabled = false; // Aktifkan sebelum submit
        }

        // Panggil fungsi saat halaman dimuat
        window.onload = getLocation;
    </script>
@endsection
