<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
   public function index()
{
    // Tambahkan where('status', 'publish') di semua query
    $sliderBerita = Berita::where('status', 'publish')
                      ->with('kategori')
                      ->orderBy('created_at', 'desc') // Mengunci urutan ke tanggal rilis asli
                      ->take(5)
                      ->get();

    $berita = Berita::where('status', 'publish')
                    ->with('kategori')
                    ->latest()
                    ->paginate(4);

    $beritaPopuler = Berita::where('status', 'publish')
                           ->with('kategori')
                           ->orderBy('views', 'desc')
                           ->take(5)
                           ->get();

    return view('user.home', compact('sliderBerita', 'berita', 'beritaPopuler'));
}
}