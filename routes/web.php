<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController; 
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| 🌐 RUTE FRONTEND USER
|--------------------------------------------------------------------------
*/

// Halaman Beranda Utama
Route::get('/', [HomeController::class, 'index']);

// Fitur Pencarian Berita Global
Route::get('/search', [FrontendController::class, 'search'])->name('berita.search');

// Detail Berita Dinamis
Route::get('/berita/{slug_berita}', [FrontendController::class, 'detailBerita'])->name('berita.detail');

// Navigasi Kategori Dinamis
Route::get('/kategori/{slug_kategori}', [FrontendController::class, 'kategoriDinamis'])->name('kategori.view');

// AJAX: Increment jumlah tayangan berita (dipanggil di background tanpa refresh halaman)
Route::post('/berita/{id_berita}/increment-view', [FrontendController::class, 'incrementView'])->name('berita.incrementView');

// AJAX: Ambil jumlah views terkini tanpa increment (dipakai saat navigasi back dari cache)
Route::get('/berita/{id_berita}/get-views', [FrontendController::class, 'getViews'])->name('berita.getViews');


/*
|--------------------------------------------------------------------------
| 🔐 RUTE AUTENTIKASI ADMIN (Sudah Bersih & Terkunci Aman)
|--------------------------------------------------------------------------
*/

// Menampilkan Halaman Login
Route::get('/admin/login', function () { 
    return view('auth.login'); 
})->name('login');

// Memproses Data Login Admin (Menghubungkan ke fungsi loginProses di AuthController)
// SESUDAH (Ubah menjadi seperti ini)
Route::post('/admin/login-proses', [AuthController::class, 'login'])->name('login.proses');

// Memproses Fungsi Keluar Sistem
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| 💻 RUTE DASHBOARD ADMIN (Seluruh Fungsi CRUD Berita & Kategori)
|--------------------------------------------------------------------------
*/

// PASTIKAN BARIS INI BERSIH TANPA EMBEL-EMBEL ->middleware('auth')
Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// --- CRUD Manajemen Berita ---
Route::get('/admin/man-berita', [BeritaController::class, 'index'])->name('berita.index');
Route::post('/admin/berita/store', [BeritaController::class, 'store'])->name('berita.store');
Route::match(['post', 'put'], '/admin/berita/update/{id}', [BeritaController::class, 'update'])->name('berita.update');
Route::delete('/admin/berita/delete/{id}', [BeritaController::class, 'destroy'])->name('berita.delete');

// --- CRUD Kategori ---
Route::get('/admin/kategori', [KategoriController::class, 'index'])->name('kategori.index');
Route::post('/admin/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
Route::post('/admin/kategori/update/{id}', [KategoriController::class, 'update'])->name('kategori.update');
Route::delete('/admin/kategori/delete/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

// --- Manajemen Profil & Password ---
Route::get('/admin/profil', function () { 
    return view('admin.profile'); 
})->name('admin.profile');

Route::post('/admin/profile/update-password', [ProfilController::class, 'updatePassword'])->name('admin.update-password');

Route::get('/debug-password', function() {
    $user = \App\Models\User::find(1);
    $user->password = \Illuminate\Support\Facades\Hash::make('admin123');
    $user->save();
    return "Password Admin Utama Berhasil Di-hash Sempurna Menjadi: admin123";
});

Route::get('/debug-timezone', function() {
    return [
        'app_timezone' => config('app.timezone'),
        'now_local'    => \Carbon\Carbon::now()->toDateTimeString(),
        'now_utc'      => \Carbon\Carbon::now('UTC')->toDateTimeString(),
    ];
});