@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Laporan Penjualan</h1>

    {{-- Filter (disembunyikan saat dicetak) --}}
    <form method="GET" action="{{ route('laporan.index') }}"
          class="bg-white rounded-xl shadow p-4 mb-4 flex flex-wrap items-end gap-3 print:hidden">
        <div>
            <label class="block text-sm mb-1">Dari tanggal</label>
            <input type="date" name="dari" value="{{ $dari }}" class="border rounded px-3 py-2">
        </div>
        <div>
            <label class="block text-sm mb-1">Sampai tanggal</label>
            <input type="date" name="sampai" value="{{ $sampai }}" class="border rounded px-3 py-2">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tampilkan</button>
        <button type="button" onclick="window.print()" class="px-4 py-2 rounded border">Cetak</button>

        @if ($errors->any())
            <p class="w-full text-sm text-red-600">{{ $errors->first() }}</p>
        @endif
    </form>

    <p class="text-sm text-gray-600 mb-3">
        Periode: {{ \Carbon\Carbon::parse($dari)->format('d/m/Y') }}
        s/d {{ \Carbon\Carbon::parse($sampai)->format('d/m/Y') }}
    </p>

    {{-- Ringkasan --}}
    <div class="grid grid-cols-3 gap-4 mb-4">
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-gray-500">Jumlah transaksi</p>
            <p class="text-xl font-bold">{{ $jumlahTransaksi }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-gray-500">Total diskon</p>
            <p class="text-xl font-bold">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-4">
            <p class="text-sm text-gray-500">Total penjualan</p>
            <p class="text-xl font-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- Rekap --}}
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">No Nota</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Pelanggan</th>
                    <th class="p-3">Kasir</th>
                    <th class="p-3 text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penjualan as $p)
                    <tr class="border-t">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3">{{ $p->no_nota }}</td>
                        <td class="p-3">{{ $p->tgl_penjualan->format('d/m/Y H:i') }}</td>
                        <td class="p-3">{{ $p->pelanggan->nama_pelanggan ?? 'Umum' }}</td>
                        <td class="p-3">{{ $p->user->nama_user ?? '-' }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-6 text-center text-gray-500">Tidak ada transaksi pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($penjualan->isNotEmpty())
                <tfoot class="border-t font-semibold">
                    <tr>
                        <td colspan="5" class="p-3 text-right">Total</td>
                        <td class="p-3 text-right">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
@endsection