<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kategori;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{
    \Illuminate\Support\Facades\View::composer('partials.admin.modal-tambah-berita', function ($view) {
        $view->with('kategoriList', \App\Models\Kategori::where('status', 'aktif')->get());
    });

    // Komposer untuk modal admin
    View::composer('partials.frontend.navbar', function ($view) {
    // 1. Ambil Kategori Navbar
    $kategori = \App\Models\Kategori::where('status', 'aktif')->get();

    // 2. Ambil Berita: Terbaru + Terakhir di-publish sebelumnya
    // Kita ambil misalnya 10 berita terbaru supaya marquee-nya tidak pendek
    $beritaMarquee = \App\Models\Berita::where('status', 'publish')
        ->latest() // Mengurutkan dari yang paling baru
        ->take(10) // Ambil 10 berita terakhir
        ->get();

    $newsTitles = $beritaMarquee->pluck('judul_berita')->toArray();
    $marqueeText = !empty($newsTitles) ? implode('  •  ', $newsTitles) . '  •  ' : 'Belum ada berita terbaru.';

    $view->with([
        'kategoriNavbar' => $kategori,
        'marqueeText' => $marqueeText
    ]);

    });

    // Mengambil 5 berita terbaru untuk semua halaman
    view()->composer('*', function ($view) {
        $view->with('breakingNews', \App\Models\Berita::latest()->limit(5)->get());
    });

}
}