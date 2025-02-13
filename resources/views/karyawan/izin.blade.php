<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pengajuan Izin Karyawan') }}
        </h2>
    </x-slot>

    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded-lg shadow-lg">
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded-md mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('izin.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <label class="block font-medium text-gray-700">Nama:</label>
            <input type="text" name="nama" value="{{ auth()->user()->name }}" readonly class="w-full border-gray-300 rounded-lg px-4 py-2 bg-gray-100 mb-3">

            <label class="block font-medium text-gray-700">Email:</label>
            <input type="email" name="email" value="{{ auth()->user()->email }}" readonly class="w-full border-gray-300 rounded-lg px-4 py-2 bg-gray-100 mb-3">

            <label class="block font-medium text-gray-700">Alasan:</label>
            <textarea name="alasan" required class="w-full border-gray-300 rounded-lg px-4 py-2 focus:ring-blue-500 focus:border-blue-500 mb-3"></textarea>

            <label class="block font-medium text-gray-700">Surat/Dokumen Pendukung:</label>
            <input type="file" name="dokumen" accept="image/*" required class="w-full border-gray-300 rounded-lg px-4 py-2 mb-3">

            <label class="block font-medium text-gray-700">Izin Dari:</label>
            <input type="date" name="izin_dari" required class="w-full border-gray-300 rounded-lg px-4 py-2 mb-3">

            <label class="block font-medium text-gray-700">Izin Sampai:</label>
            <input type="date" name="izin_sampai" required class="w-full border-gray-300 rounded-lg px-4 py-2 mb-5">

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-300">
                ✅ Ajukan Izin
            </button>
        </form>

        <div class="mt-4">
            <a href="{{ route('karyawan.absen') }}" class="w-full block text-center bg-gray-500 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-gray-600 transition duration-300">
                🔙 Kembali ke Absen
            </a>
        </div>
    </div>
</x-app-layout>
