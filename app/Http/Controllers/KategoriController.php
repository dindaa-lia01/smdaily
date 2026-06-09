<?php

namespace App\Http\Controllers;

use App\Models\Kategori; // Pastikan memanggil model Kategori kapital
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    // 1. TAMPILKAN DATA (Read)
   // 1. TAMPILKAN DATA (Read)
public function index(Request $request)
{
    // Ambil input pencarian
    $search = $request->input('search');

    // Mulai query dengan menghitung jumlah berita
    $listKategori = Kategori::withCount('berita')->paginate(4);

    // Jika ada input search, tambahkan filter WHERE
    if ($search) {
        $query->where('nama_kategori', 'LIKE', '%' . $search . '%');
    }

    // Jalankan paginate dengan menambahkan withQueryString() 
    // agar hasil pencarian tetap ada saat berpindah halaman
    $listKategori = Kategori::withCount('berita')->paginate(4)->withQueryString();
    return view('admin.man-kategori', compact('listKategori'));
}

    // 2. PROSES SIMPAN (Create)
    public function store(Request $request)
    {
        // Validasi input: wajib diisi, unik di tabel, hanya huruf & spasi, maksimal 100 karakter
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:tb_kategori,nama_kategori|regex:/^[a-zA-Z\s]+$/',
            'status' => 'required|in:aktif,nonaktif'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi!',
            'nama_kategori.unique' => 'Nama kategori ini sudah terdaftar!',
            'nama_kategori.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi!',
            'nama_kategori.max' => 'Nama kategori maksimal 100 karakter!'
        ]);

        // Simpan ke database
        $kategori = new Kategori();
        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->slug_kategori = Str::slug($request->nama_kategori);
        $kategori->status = $request->status;
        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // 3. PROSES UBAH (Update)
    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'nama_kategori' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/|unique:tb_kategori,nama_kategori,' . $id . ',id_kategori',
            'status' => 'required|in:aktif,nonaktif'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi!',
            'nama_kategori.unique' => 'Nama kategori ini sudah terdaftar!',
            'nama_kategori.regex' => 'Nama kategori hanya boleh berisi huruf dan spasi!'
        ]);

        $kategori->nama_kategori = $request->nama_kategori;
        $kategori->slug_kategori = Str::slug($request->nama_kategori);
        $kategori->status = $request->status;
        $kategori->save();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // 4. PROSES HAPUS (Delete)
    public function destroy($id)
    {
        $kategori = Kategori::withCount('berita')->findOrFail($id);

        // Mencegah penghapusan jika kategori masih digunakan oleh berita apa pun (Keamanan Relasi)
        if ($kategori->berita_count > 0) {
            return redirect()->route('kategori.index')->with('error', 'Gagal dihapus! Kategori ini masih digunakan oleh ' . $kategori->berita_count . ' berita.');
        }

        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}