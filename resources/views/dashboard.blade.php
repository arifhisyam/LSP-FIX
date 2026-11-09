@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold">Halo, {{ auth()->user()->nama_user }}</h1>
            <p class="text-gray-600">Selamat datang di aplikasi penjualan.</p>
        </div>

        <a href="{{ route('penjualan.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ Transaksi Baru</a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Transaksi hari ini</p>
            <p class="text-2xl font-bold mt-1">{{ $transaksiHariIni }}</p>
        </div>

        <div class="bg-white rounded-xl shadow p-5">
            <p class="text-sm text-gray-500">Penjualan hari ini</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($omzetHariIni, 0, ',', '.') }}</p>
        </div>

        @if (auth()->user()->level === 'admin')
            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">Jumlah produk</p>
                <p class="text-2xl font-bold mt-1">{{ $jumlahProduk }}</p>
            </div>

            <div class="bg-white rounded-xl shadow p-5">
                <p class="text-sm text-gray-500">Stok menipis</p>
                <p class="text-2xl font-bold mt-1">{{ $stokMenipis }}</p>
            </div>
        @endif
    </div>
@endsection