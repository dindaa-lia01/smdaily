<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FrontendController extends Controller
{
    
   public function kategoriDinamis($slug_kategori, Request $request)
{
    // 1. Ambil kategori yang aktif
    $kategori = Kategori::where('slug_kategori', $slug_kategori)->where('status', 'aktif')->firstOrFail();

    // --- FITUR BARU: Tangkap Filter ---
    $order = $request->query('order', 'terbaru') === 'terlama' ? 'asc' : 'desc';
    $viewMode = $request->query('view', 'grid');

    // 2. Jika kategori kosong
    $beritaCek = Berita::where('id_kategori', $kategori->id_kategori)->where('status', 'publish')->get();

    if ($beritaCek->isEmpty()) {
        $berita = collect([]);
        return view('user.kategori', compact('kategori', 'berita', 'viewMode', 'order'));
    }

    // 3. Logika Layout Tetap (Ditambahkan OrderBy)
    if ($kategori->id_kategori == 1 || $slug_kategori == 'pembangunan') {
        $heroBerita = Berita::where('id_kategori', $kategori->id_kategori)->where('status', 'publish')->latest()->first();
        $listBerita = Berita::where('id_kategori', $kategori->id_kategori)
                            ->where('status', 'publish')
                            ->orderBy('created_at', $order) // Filter waktu aktif
                            ->paginate(6)->withQueryString();
        return view('user.pembangunan', compact('kategori', 'heroBerita', 'listBerita', 'viewMode', 'order'));
            
    } elseif ($kategori->id_kategori == 2 || $slug_kategori == 'pemerintah') {
        $heroBerita = Berita::where('id_kategori', $kategori->id_kategori)->where('status', 'publish')->latest()->first();
        $listBerita = Berita::where('id_kategori', $kategori->id_kategori)
                            ->where('status', 'publish')
                            ->orderBy('created_at', $order) // Filter waktu aktif
                            ->paginate(6)->withQueryString();
        return view('user.pemerintahan', compact('kategori', 'heroBerita', 'listBerita', 'viewMode', 'order'));
            
    } elseif ($kategori->id_kategori == 3 || $slug_kategori == 'kegiatan-masyarakat') {
        $heroBerita = Berita::where('id_kategori', $kategori->id_kategori)->where('status', 'publish')->latest()->take(3)->get();
        $listBerita = Berita::where('id_kategori', $kategori->id_kategori)
                            ->where('status', 'publish')
                            ->orderBy('created_at', $order) // Filter waktu aktif
                            ->paginate(6)->withQueryString();
        return view('user.kegiatan-masyarakat', compact('kategori', 'heroBerita', 'listBerita', 'viewMode', 'order'));
            
    } else {
        $berita = Berita::where('id_kategori', $kategori->id_kategori)
                            ->where('status', 'publish')
                            ->orderBy('created_at', $order) // Filter waktu aktif
                            ->paginate(6)->withQueryString();
        return view('user.kategori', compact('kategori', 'berita', 'viewMode', 'order'));
    }
}

    // =========================================================================
    // 4. FITUR SEARCH GLOBAL
    // =========================================================================
    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $id_kategori = $request->id_kategori;

        $query = Berita::where('status', 'publish');

        if ($keyword) {
            $query->where('judul_berita', 'like', "%" . $keyword . "%");
        }

        if ($id_kategori) {
            $query->where('id_kategori', $id_kategori);
        }

        $listBerita = $query->latest()->paginate(6);

        return view('user.search', compact('listBerita', 'keyword', 'id_kategori'));
    }

    // =========================================================================
    // 5. HALAMAN DETAIL BERITA
    // =========================================================================
    public function detailBerita($slug_berita)
    {
        // 1. Tambahkan filter status 'publish' di sini
        $berita = Berita::where('slug_berita', $slug_berita)
                        ->where('status', 'publish') // <--- INI KUNCI UTAMANYA
                        ->first();

        // 2. Jika berita tidak ditemukan (atau statusnya bukan publish),
        // maka $berita akan bernilai null dan masuk ke blok if ini
        if (!$berita) {
            abort(404, "Berita tidak ditemukan atau belum dipublikasikan.");
        }

        // Increment views DIHAPUS dari sini.
        // Sekarang ditangani secara AJAX oleh method incrementView() di bawah,
        // dipanggil dari JS setelah halaman selesai dimuat tanpa refresh.

        return view('user.detail-berita', compact('berita'));
    }

    // =========================================================================
    // 6. AJAX: INCREMENT VIEWS BERITA (dipanggil di background, tanpa refresh)
    // =========================================================================
    public function incrementView($id_berita)
    {
        // Cari berita berdasarkan ID, hanya yang berstatus publish
        $berita = Berita::where('id_berita', $id_berita)
                        ->where('status', 'publish')
                        ->first();

        // Jika tidak ditemukan, kembalikan error tanpa crash halaman
        if (!$berita) {
            return response()->json(['success' => false, 'message' => 'Berita tidak ditemukan.'], 404);
        }

        // Increment kolom views di database
        $berita->increment('views');

        // Kembalikan nilai views terbaru ke JS agar bisa diupdate di halaman tanpa reload
        return response()->json([
            'success' => true,
            'views'   => $berita->fresh()->views,
        ]);
    }

    // =========================================================================
    // 7. AJAX: AMBIL VIEWS TERKINI TANPA INCREMENT
    // Dipakai saat navigasi back — halaman dari cache, views perlu di-refresh
    // via fetch ringan tanpa reload seluruh halaman
    // =========================================================================
    public function getViews($id_berita)
    {
        $berita = Berita::where('id_berita', $id_berita)
                        ->where('status', 'publish')
                        ->first();

        if (!$berita) {
            return response()->json(['success' => false], 404);
        }

        return response()->json([
            'success' => true,
            'views'   => $berita->views,
        ]);
    }
}