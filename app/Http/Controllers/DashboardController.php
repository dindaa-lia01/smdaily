<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBerita = DB::table('tb_berita')->count();

        // PERBAIKAN Bug 2: hitung hanya kategori yang aktif
        $totalKategori = DB::table('tb_kategori')->where('status', 'aktif')->count();

        $totalPublish = DB::table('tb_berita')->where('status', 'publish')->count();

        // Akumulasi total views HANYA dari berita berstatus publish
        $totalViews = DB::table('tb_berita')
            ->where('status', 'publish')
            ->sum('views');

        // PERBAIKAN Bug 1: select eksplisit agar kolom 'status' dari tb_berita
        // tidak tertimpa kolom lain saat join
        $berita = DB::table('tb_berita')
            ->join('tb_kategori', 'tb_berita.id_kategori', '=', 'tb_kategori.id_kategori')
            ->select(
                'tb_berita.id_berita',
                'tb_berita.judul_berita',
                'tb_berita.status',           // eksplisit ambil dari tb_berita
                'tb_berita.created_at',
                'tb_berita.updated_at',
                'tb_berita.gambar_thumbnail', // untuk preview thumbnail di dashboard
                'tb_kategori.nama_kategori'
            )
            ->orderBy('tb_berita.updated_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('totalBerita', 'totalKategori', 'totalPublish', 'totalViews', 'berita'));
    }
}