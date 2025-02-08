<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-center fw-bold mb-3">📋 Absen Karyawan</h1>

            @if (session()->has('message'))
                <div class="alert alert-info text-center">
                    {{ session('message') }}
                </div>
            @endif  {{-- <== DITUTUP DI SINI --}}

            <div class="alert alert-secondary text-center">
                ⏰ Waktu sekarang: <span wire:poll.60s>{{ $currentTime }}</span>
            </div>

            @if (is_null($attendance))  {{-- <== DIPINDAHKAN KE SINI --}}
                @if ($currentTime <= '23:30')
                    <form wire:submit.prevent="absenMasuk">
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Masuk:</label>
                            <input type="text" wire:model="location" id="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">🔄 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Masuk:</label>
                            <input type="file" wire:model="photo" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">✅ Absen Masuk</button>
                    </form>
                @else
                    <div class="alert alert-danger text-center">
                        ⚠️ Absen masuk sudah ditutup!<br>
                        <small class="text-muted">Batas absen masuk: <b>08:30 pagi</b></small>
                    </div>
                @endif
            @elseif ($attendance && !$attendance->check_out)
                @if ($currentTime >= '17:00' || $currentTime <= '23:59')
                    <form wire:submit.prevent="absenKeluar">
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Keluar:</label>
                            <input type="text" wire:model="location" id="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">📍 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Keluar:</label>
                            <input type="file" wire:model="photo" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">❌ Absen Keluar</button>
                    </form>
                @else
                    <div class="alert alert-warning text-center">
                        ⚠️ Absen keluar hanya bisa dilakukan antara jam <b>17:00 hingga 00:00</b>!
                    </div>
                @endif
            @else
                <div class="alert alert-success text-center">
                    ✅ Anda sudah menyelesaikan absen hari ini.<br>
                    <small class="text-muted">Silakan absen besok lagi. ⏳</small>
                </div>
            @endif
        </div>
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
