<div class="container mt-5">
    <div class="card shadow-lg border-0 rounded-lg">
        <div class="card-body p-4">
            <h1 class="text-center fw-bold mb-4 text-primary">📋 Absen Karyawan</h1>

            @if (session()->has('message'))
                <div class="alert alert-info text-center fw-semibold">
                    {{ session('message') }}
                </div>
            @endif

            <div class="alert alert-light text-center border rounded py-2">
                ⏰ <strong>Waktu sekarang:</strong> <span wire:poll.60s>{{ $currentTime }}</span>
            </div>

            @if (is_null($attendance))
                @if ($currentTime <= '23:30')
                    <form wire:submit.prevent="absenMasuk" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📍 Lokasi Masuk:</label>
                            <input type="text" wire:model="location" id="location" class="form-control" readonly required>
                            <div class="form-text text-muted" id="coordinate">🔄 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">📷 Foto Masuk:</label>
                            <input type="file" wire:model="photo" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-semibold">✅ Absen Masuk</button>
                    </form>
                @else
                    <div class="alert alert-danger text-center fw-semibold">
                        ⚠️ Absen masuk sudah ditutup!<br>
                        <small class="text-muted">Batas absen masuk: <b>08:30 pagi</b></small>
                    </div>
                @endif
            @elseif ($attendance && !$attendance->check_out)
                @if ($currentTime >= '17:00' || $currentTime <= '23:59')
                    <form wire:submit.prevent="absenKeluar" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">📍 Lokasi Keluar:</label>
                            <input type="text" wire:model="location" id="location" class="form-control" readonly required>
                            <div class="form-text text-muted" id="coordinate">📍 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">📷 Foto Keluar:</label>
                            <input type="file" wire:model="photo" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-semibold">❌ Absen Keluar</button>
                    </form>
                @else
                    <div class="alert alert-warning text-center fw-semibold">
                        ⚠️ Absen keluar hanya bisa dilakukan antara jam <b>17:00 hingga 00:00</b>!
                    </div>
                @endif
            @else
                <div class="alert alert-success text-center fw-semibold">
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
