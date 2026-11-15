@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
    <h1 class="text-2xl font-bold mb-4">
        Laporan Penjualan
        {{ ['periode' => 'per Periode', 'produk' => 'per Produk', 'kategori' => 'per Kategori'][$jenis] }}
    </h1>

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
        <div>
            <label class="block text-sm mb-1">Jenis laporan</label>
            <select name="jenis" class="border rounded px-3 py-2">
                <option value="periode"  @selected($jenis === 'periode')>Per Periode (Transaksi)</option>
                <option value="produk"   @selected($jenis === 'produk')>Per Produk</option>
                <option value="kategori" @selected($jenis === 'kategori')>Per Kategori</option>
            </select>
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

    @if ($jenis === 'periode')
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

    @endif

    @if ($jenis === 'kategori')
    <h2 class="text-lg font-bold mt-8 mb-2">Penjualan per Kategori</h2>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3 text-right">Jenis Produk Terjual</th>
                    <th class="p-3 text-right">Qty Terjual</th>
                    <th class="p-3 text-right">Diskon Item</th>
                    <th class="p-3 text-right">Penjualan</th>
                    <th class="p-3 text-right">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perKategori as $k)
                    <tr class="border-t">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3">{{ $k->nama_kategori }}</td>
                        <td class="p-3 text-right">{{ $k->jumlah_produk }}</td>
                        <td class="p-3 text-right">{{ rtrim(rtrim(number_format($k->qty, 2, ',', '.'), '0'), ',') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($k->diskon_item, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($k->omzet, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">{{ $totalOmzetItem > 0 ? number_format($k->omzet / $totalOmzetItem * 100, 1, ',', '.') : 0 }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">Tidak ada penjualan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($perKategori->isNotEmpty())
                <tfoot class="border-t font-semibold">
                    <tr>
                        <td colspan="5" class="p-3 text-right">Total</td>
                        <td class="p-3 text-right">Rp {{ number_format($totalOmzetItem, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>

    @endif

    @if ($jenis === 'produk')
    <h2 class="text-lg font-bold mt-8 mb-2">Penjualan per Produk</h2>
    <div class="bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Produk</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3 text-right">Transaksi</th>
                    <th class="p-3 text-right">Qty Terjual</th>
                    <th class="p-3 text-right">Diskon Item</th>
                    <th class="p-3 text-right">Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perProduk as $pr)
                    <tr class="border-t">
                        <td class="p-3">{{ $loop->iteration }}</td>
                        <td class="p-3">{{ $pr->kode_produk }}</td>
                        <td class="p-3">{{ $pr->nama_produk }}</td>
                        <td class="p-3">{{ $pr->nama_kategori }}</td>
                        <td class="p-3 text-right">{{ $pr->jumlah_transaksi }}</td>
                        <td class="p-3 text-right">{{ rtrim(rtrim(number_format($pr->qty, 2, ',', '.'), '0'), ',') }} {{ $pr->satuan }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($pr->diskon_item, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($pr->omzet, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-6 text-center text-gray-500">Tidak ada penjualan pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
            @if ($perProduk->isNotEmpty())
                <tfoot class="border-t font-semibold">
                    <tr>
                        <td colspan="7" class="p-3 text-right">Total</td>
                        <td class="p-3 text-right">Rp {{ number_format($totalOmzetItem, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            @endif
        </table>
    </div>
    @endif

@endsection