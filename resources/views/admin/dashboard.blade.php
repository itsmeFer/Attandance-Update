<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        button {
            padding: 10px 20px;
            background-color: red;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <h1>Welcome to Admin Dashboard</h1>

    <!-- Tombol Logout -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>
