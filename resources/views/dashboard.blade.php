<div class="p-6 bg-white rounded-lg shadow-lg">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">📋 Absen Karyawan</h1>

    @if (session()->has('message'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-gray-100 p-4 rounded-lg mb-4 text-center">
        ⏰ Waktu sekarang: <span wire:poll.60s class="font-semibold">{{ $currentTime }}</span>
    </div>

    <div class="bg-gray-50 p-4 rounded-lg shadow-sm">
        @if (is_null($attendance))
            @if ($currentTime <= '23:30')
                <form wire:submit.prevent="absenMasuk">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">📍 Lokasi Masuk:</label>
                        <input type="text" wire:model="location" id="location" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" readonly required>
                        <p id="coordinate" class="text-sm text-gray-500">🔄 Mengambil lokasi...</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">📷 Foto Masuk:</label>
                        <input type="file" wire:model="photo" accept="image/*" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" required>
                    </div>

                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg shadow hover:bg-green-700">✅ Absen Masuk</button>
                </form>
            @else
                <div class="text-center text-red-600 font-semibold">⚠️ Absen masuk sudah ditutup! (Batas: 08:30 pagi)</div>
            @endif
        @elseif ($attendance && !$attendance->check_out)
            @if ($currentTime >= '17:00' || $currentTime <= '23:59')
                <form wire:submit.prevent="absenKeluar">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">📍 Lokasi Keluar:</label>
                        <input type="text" wire:model="location" id="location" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" readonly required>
                        <p id="coordinate" class="text-sm text-gray-500">📍 Mengambil lokasi...</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold">📷 Foto Keluar:</label>
                        <input type="file" wire:model="photo" accept="image/*" class="w-full border rounded-lg p-2 mt-1 bg-gray-100" required>
                    </div>

                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg shadow hover:bg-red-700">❌ Absen Keluar</button>
                </form>
            @else
                <div class="text-center text-yellow-600 font-semibold">⚠️ Absen keluar hanya bisa dilakukan antara jam 17:00 - 00:00!</div>
            @endif
        @else
            <div class="text-center text-green-600 font-semibold">✅ Anda sudah menyelesaikan absen hari ini. Silakan absen besok lagi. ⏳</div>
        @endif
    </div>
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
                    coordinateText.innerHTML = `📍 Koordinat: <b>${latitude}, ${longitude}</b>`;
                },
                function (error) {
                    console.error("Error mendapatkan lokasi:", error);
                    document.getElementById("location").value = "Tidak dapat mengambil lokasi";
                    document.getElementById("coordinate").innerText = "⚠️ Gagal mendapatkan lokasi.";
                }
            );
        }
    }

    window.onload = getLocation;
</script>
