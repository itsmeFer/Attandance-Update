<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absen Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-center fw-bold mb-3">📋 Absen Karyawan</h1>

            @php
                $user = Auth::user();
                $today = now()->toDateString();
                $currentTime = now()->format('H:i'); // Format waktu sekarang (24 jam)
                $attendance = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', $today)
                    ->first();
            @endphp

            <div class="alert alert-secondary text-center">
                ⏰ Waktu sekarang: <span id="current-time" class="fw-bold">{{ $currentTime }}</span>
            </div>

            @if (!$attendance)
                @if ($currentTime <= '23:30')
                    <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" onsubmit="enableLocationInput()" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Masuk:</label>
                            <input type="text" id="location" name="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">🔄 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Masuk:</label>
                            <input type="file" name="photo" accept="image/*" class="form-control" required>
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
                    <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" onsubmit="enableLocationInput()" novalidate>
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Keluar:</label>
                            <input type="text" id="location" name="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">📍 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Keluar:</label>
                            <input type="file" name="photo" accept="image/*" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">❌ Absen Keluar</button>
                    </form>
                @else
                    <div class="alert alert-warning text-center">
                        ⚠️ Absen keluar hanya bisa dilakukan antara jam <b>17:00 hingga 00:00</b>!<br>
                        <small class="text-muted">Silakan kembali nanti.</small>
                    </div>
                @endif
            @else
                <div class="alert alert-success text-center">
                    ✅ Anda sudah menyelesaikan absen masuk dan keluar.<br>
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

    function updateTime() {
        let now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        if (minutes < 10) minutes = "0" + minutes;
        let currentTime = hours + ":" + minutes;
        document.getElementById("current-time").innerText = currentTime;
    }

    // Panggil fungsi saat halaman dimuat
    window.onload = function() {
        getLocation();
        updateTime();
        setInterval(updateTime, 60000); // Update setiap 1 menit
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
