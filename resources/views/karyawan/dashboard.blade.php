<!DOCTYPE html>
<html>
<head>
    <title>Karyawan Dashboard</title>
    <style>
        button {
            padding: 10px 20px;
            background-color: blue;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
        }
        button.logout {
            background-color: red;
        }
        button:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <h1>Welcome to Karyawan Dashboard</h1>

    <!-- Tombol Absen -->
<a href="{{ route('absen.show') }}">
    <button>Absen Sekarang</button>
</a>


</body>
</html>
