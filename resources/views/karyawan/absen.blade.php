<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absen Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #video {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            display: block;
        }
        #canvas {
            display: none;
        }
        #photo-preview {
            width: 100%;
            max-width: 500px;
            margin: 10px auto;
            display: none;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-center fw-bold mb-3">📋 Absen Karyawan</h1>

            @php
                $user = Auth::user();
                $today = now()->toDateString();
                $currentTime = now()->format('H:i');
                $attendance = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereDate('check_in', $today)
                    ->first();
            @endphp

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary">🚪 Logout</button>
            </form>

            <div class="alert alert-secondary text-center">
                ⏰ Waktu sekarang: <span id="current-time" class="fw-bold">{{ $currentTime }}</span>
            </div>

            @if (!$attendance)
                @if ($currentTime <= '15.00')
                <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" onsubmit="enableLocationInput(event)" novalidate>
                @csrf
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Masuk:</label>
                            <input type="text" id="location" name="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">🔄 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Masuk:</label>
                            <div class="text-center mb-2">
                                <video id="video" autoplay playsinline></video>
                                <canvas id="canvas"></canvas>
                                <img id="photo-preview" class="img-fluid rounded">
                            </div>
                            <input type="hidden" name="photo" id="photo-input">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-secondary" id="startCamera">🎥 Buka Kamera</button>
                                <button type="button" class="btn btn-info" id="capturePhoto" disabled>📸 Ambil Foto</button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">✅ Absen Masuk</button>
                    </form>
                @else
                    <div class="alert alert-danger text-center">
                        ⚠️ Absen masuk sudah ditutup!
                    </div>
                @endif
            @elseif ($attendance && !$attendance->check_out)
                @if ($currentTime >= '17:00' || $currentTime <= '23:59')
                <form action="{{ route('absen.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" onsubmit="enableLocationInput(event)" novalidate>
                @csrf
                        <div class="mb-3">
                            <label class="form-label">📍 Lokasi Keluar:</label>
                            <input type="text" id="location" name="location" class="form-control" readonly required>
                            <div class="form-text" id="coordinate">📍 Mengambil lokasi...</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">📷 Foto Keluar:</label>
                            <div class="text-center mb-2">
                                <video id="video" autoplay playsinline></video>
                                <canvas id="canvas"></canvas>
                                <img id="photo-preview" class="img-fluid rounded">
                            </div>
                            <input type="hidden" name="photo" id="photo-input">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-secondary" id="startCamera">🎥 Buka Kamera</button>
                                <button type="button" class="btn btn-info" id="capturePhoto" disabled>📸 Ambil Foto</button>
                            </div>
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
                    ✅ Anda sudah menyelesaikan absen masuk dan keluar.
                </div>
            @endif
        </div>
        <div class="text-center mt-3">
        <a href="{{ route('karyawan.izin.create') }}" class="btn btn-warning">📝 Ajukan Izin Tambah Kehadiran</a>
        </div>

    </div>
</div>

<script>
    let stream = null;
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photo-preview');
    const photoInput = document.getElementById('photo-input');
    const startButton = document.getElementById('startCamera');
    const captureButton = document.getElementById('capturePhoto');

    // Fungsi untuk memulai kamera
    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    facingMode: 'environment' // Menggunakan kamera belakang jika ada
                } 
            });
            video.srcObject = stream;
            video.style.display = 'block';
            photoPreview.style.display = 'none';
            startButton.textContent = '🔄 Ganti Kamera';
            captureButton.disabled = false;
        } catch (err) {
            alert('Error: ' + err.message);
            console.error('Error:', err);
        }
    }

    // Fungsi untuk mengambil foto
    function capturePhoto() {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        
        // Konversi canvas ke base64
        const photoData = canvas.toDataURL('image/jpeg');
        photoInput.value = photoData;
        
        // Tampilkan preview
        photoPreview.src = photoData;
        photoPreview.style.display = 'block';
        video.style.display = 'none';
        
        // Hentikan kamera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        
        startButton.textContent = '🎥 Buka Kamera';
        captureButton.disabled = true;
    }

    // Fungsi untuk mendapatkan lokasi
    function getLocation() {
    let locationInput = document.getElementById("location");
    let coordinateText = document.getElementById("coordinate");

    if (navigator.geolocation) {
        coordinateText.innerText = "🔄 Mengambil lokasi...";
        navigator.geolocation.getCurrentPosition(
            function (position) {
                let latitude = position.coords.latitude;
                let longitude = position.coords.longitude;
                locationInput.value = latitude + ", " + longitude;
                coordinateText.innerHTML = `📍 Koordinat: <b>${latitude}, ${longitude}</b>`;
            },
            function (error) {
                console.error("Error mendapatkan lokasi:", error);
                locationInput.value = "Tidak dapat mengambil lokasi";
                coordinateText.innerText = "⚠️ Gagal mendapatkan lokasi.";
            }
        );
    } else {
        locationInput.value = "Geolocation tidak didukung";
        coordinateText.innerText = "⚠️ Geolocation tidak didukung oleh perangkat ini.";
    }
}


function enableLocationInput(event) {
    let locationInput = document.getElementById("location");
    
    if (!locationInput.value || locationInput.value.includes("Mengambil lokasi") || locationInput.value.includes("Tidak dapat mengambil lokasi")) {
        alert("⚠️ Mohon Tunggu, Lokasi sedang diambil!");
        event.preventDefault(); // Mencegah form submit
    }
}



    function enableLocationInput() {
        document.getElementById("location").disabled = false;
    }

    function updateTime() {
        let now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        if (minutes < 10) minutes = "0" + minutes;
        let currentTime = hours + ":" + minutes;
        document.getElementById("current-time").innerText = currentTime;
    }

    // Event listeners
    if (startButton && captureButton) {
        startButton.addEventListener('click', startCamera);
        captureButton.addEventListener('click', capturePhoto);
    }

    // Inisialisasi
    window.onload = function() {
        getLocation();
        updateTime();
        setInterval(updateTime, 60000);
    };
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>