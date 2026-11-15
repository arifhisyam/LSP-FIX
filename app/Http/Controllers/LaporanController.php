<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'dari'   => 'nullable|date',
            'sampai' => 'nullable|date|after_or_equal:dari',
            'jenis'  => 'nullable|in:periode,produk,kategori',
        ]);

        $jenis = $request->jenis ?? 'periode';

        $dari   = $request->dari ?? now()->startOfMonth()->toDateString();
        $sampai = $request->sampai ?? today()->toDateString();

        $penjualan = Penjualan::with(['pelanggan', 'user'])
            ->whereDate('tgl_penjualan', '>=', $dari)
            ->whereDate('tgl_penjualan', '<=', $sampai)
            ->orderBy('tgl_penjualan')
            ->orderBy('id_penjualan')
            ->get();

        $jumlahTransaksi = $penjualan->count();
        $totalDiskon     = $penjualan->sum('diskon');
        $totalPenjualan  = $penjualan->sum('total');

        $perProduk = $jenis !== 'produk' ? collect() : DB::table('detail_penjualan as d')
            ->join('penjualan as p', 'p.id_penjualan', '=', 'd.id_penjualan')
            ->join('produk as pr', 'pr.id_produk', '=', 'd.id_produk')
            ->join('kategori as k', 'k.id_kategori', '=', 'pr.id_kategori')
            ->whereDate('p.tgl_penjualan', '>=', $dari)
            ->whereDate('p.tgl_penjualan', '<=', $sampai)
            ->groupBy('pr.id_produk', 'pr.kode_produk', 'pr.nama_produk', 'pr.satuan', 'k.nama_kategori')
            ->selectRaw('pr.kode_produk, pr.nama_produk, pr.satuan, k.nama_kategori,
                SUM(d.jumlah) as qty,
                COUNT(DISTINCT d.id_penjualan) as jumlah_transaksi,
                SUM(d.diskon_item) as diskon_item,
                SUM(d.subtotal) as omzet')
            ->orderByDesc('omzet')
            ->get();


        $perKategori = $jenis !== 'kategori' ? collect() : DB::table('detail_penjualan as d')
            ->join('penjualan as p', 'p.id_penjualan', '=', 'd.id_penjualan')
            ->join('produk as pr', 'pr.id_produk', '=', 'd.id_produk')
            ->join('kategori as k', 'k.id_kategori', '=', 'pr.id_kategori')
            ->whereDate('p.tgl_penjualan', '>=', $dari)
            ->whereDate('p.tgl_penjualan', '<=', $sampai)
            ->groupBy('k.id_kategori', 'k.nama_kategori')
            ->selectRaw('k.nama_kategori,
                COUNT(DISTINCT d.id_produk) as jumlah_produk,
                SUM(d.jumlah) as qty,
                SUM(d.diskon_item) as diskon_item,
                SUM(d.subtotal) as omzet')
            ->orderByDesc('omzet')
            ->get();

        $totalOmzetItem = $perProduk->sum('omzet');

        return view('laporan.index', compact(
            'jenis',
            'perProduk',
            'perKategori',
            'totalOmzetItem',
            'penjualan',
            'dari',
            'sampai',
            'jumlahTransaksi',
            'totalDiskon',
            'totalPenjualan'
        ));
    }
}