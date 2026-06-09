<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil semua input filter dari URL/Request
        $search = $request->input('search');
        $kategori = $request->input('kategori');
        $tanggal = $request->input('tanggal');

        // 2. Buat query dasar beserta eager loading relasi kategori
        $query = Berita::query()->with('kategori');

        // 3. JIKA ADA SEARCH: Saring berdasarkan Judul Berita
        if ($search) {
            $query->where('judul_berita', 'LIKE', '%' . $search . '%');
        }

        // 4. JIKA ADA FILTER KATEGORI: Saring berdasarkan id_kategori
        if ($kategori) {
            $query->where('id_kategori', $kategori);
        }

        // 5. JIKA ADA FILTER TANGGAL: Saring berdasarkan tanggal di created_at (mengabaikan jamnya)
        if ($tanggal) {
            $query->whereDate('created_at', $tanggal);
        }

        // 6. Eksekusi dengan paginate() dan simpan parameter filter agar tidak hilang saat pindah halaman
        $listBerita = $query->latest()->paginate(6)->withQueryString();

        // 7. Data pendukung untuk statistik dan komponen dropdown filter
        $totalBerita = Berita::count();
        $kategoriData = Kategori::withCount('berita')->get();
        $kategoriList = Kategori::all();

        return view('admin.man-berita', compact('listBerita', 'totalBerita', 'kategoriData', 'kategoriList'));
    }

    public function store(Request $request)
    {
        // Gunakan Validator::make agar bisa mengembalikan JSON error ke AJAX
        // tanpa melakukan redirect/refresh halaman
        $validator = Validator::make($request->all(), [
            'judul'    => 'required|max:255',
            'penulis'  => 'required|regex:/^[a-zA-Z\s]+$/',
            'kategori' => 'required',
            'status'   => 'required',
            'tanggal'  => 'required|date|after_or_equal:today',
            'gambar'   => 'required|image|mimes:jpeg,png,webp|max:2048',
            'konten'   => 'required'
        ], [
            'judul.required'    => 'Judul berita tidak boleh kosong.',
            'judul.max'         => 'Judul maksimal 255 karakter.',
            'penulis.required'  => 'Nama penulis tidak boleh kosong.',
            'penulis.regex'     => 'Nama penulis hanya boleh huruf dan spasi.',
            'kategori.required' => 'Kategori harus dipilih.',
            'status.required'   => 'Status harus dipilih.',
            'tanggal.required'  => 'Tanggal rilis tidak boleh kosong.',
            'tanggal.after_or_equal' => 'Tanggal rilis tidak boleh di masa lalu.',
            'gambar.required'   => 'Gambar thumbnail wajib diunggah.',
            'gambar.image'      => 'File harus berupa gambar.',
            'gambar.mimes'      => 'Format gambar harus jpeg, png, atau webp.',
            'gambar.max'        => 'Ukuran gambar maksimal 2MB.',
            'konten.required'   => 'Isi berita tidak boleh kosong.',
        ]);

        // Jika validasi gagal dan request dari AJAX, kembalikan JSON daftar error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // --- Logika simpan data tidak diubah sama sekali ---
        $gambarNama = time() . '.' . $request->gambar->extension();
        $request->gambar->move(public_path('assets/images/berita'), $gambarNama);

        Berita::create([
            'judul_berita'     => $request->judul,
            'slug_berita'      => Str::slug($request->judul),
            'id_kategori'      => $request->kategori,
            'id_user'          => 1,
            'status'           => $request->status,
            'penulis'          => $request->penulis,
            'isi_berita'       => $request->konten,
            'gambar_thumbnail' => $gambarNama,
            'created_at'       => now(), // now() mengikuti timezone app (Asia/Makassar) dari app.php — konsisten dengan update()
            // updated_at TIDAK diisi → tetap NULL, berita baru belum pernah diedit
        ]);

        // Kembalikan JSON sukses agar AJAX bisa redirect dengan bersih
        return response()->json([
            'success'  => true,
            'message'  => 'Berita berhasil diterbitkan!',
            'redirect' => route('berita.index')
        ]);
    }

    public function update(Request $request, $id)
    {
        // Gunakan Validator::make agar bisa mengembalikan JSON error ke AJAX
        // tanpa melakukan redirect/refresh halaman
        $validator = Validator::make($request->all(), [
            'judul'    => 'required|max:255',
            'penulis'  => 'required|regex:/^[a-zA-Z\s]+$/',
            'kategori' => 'required',
            'status'   => 'required',
            'konten'   => 'required',
            'tanggal'  => 'required|date',
            'gambar'   => 'nullable|image|mimes:jpeg,png,webp|max:2048'
        ], [
            'judul.required'    => 'Judul berita tidak boleh kosong.',
            'judul.max'         => 'Judul maksimal 255 karakter.',
            'penulis.required'  => 'Nama penulis tidak boleh kosong.',
            'penulis.regex'     => 'Nama penulis hanya boleh huruf dan spasi.',
            'kategori.required' => 'Kategori harus dipilih.',
            'status.required'   => 'Status harus dipilih.',
            'tanggal.required'  => 'Tanggal rilis tidak boleh kosong.',
            'konten.required'   => 'Isi berita tidak boleh kosong.',
            'gambar.image'      => 'File harus berupa gambar.',
            'gambar.mimes'      => 'Format gambar harus jpeg, png, atau webp.',
            'gambar.max'        => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Jika validasi gagal dan request dari AJAX, kembalikan JSON daftar error
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        // --- Logika update data tidak diubah sama sekali ---
        $berita = Berita::findOrFail($id);

        // Petakan data input HTML secara spesifik ke nama kolom database agar tidak mengacaukan field lain
        $data = [
            'judul_berita' => $request->judul,
            'slug_berita'  => Str::slug($request->judul),
            'id_kategori'  => $request->kategori,
            'status'       => $request->status,
            'penulis'      => $request->penulis,
            'isi_berita'   => $request->konten,
            'created_at'   => $request->tanggal . ' ' . \Carbon\Carbon::parse($berita->created_at)->format('H:i:s'),
            // updated_at diisi waktu real saat update dilakukan
            // agar dashboard menampilkan kapan berita terakhir diedit
            'updated_at'   => now(),
        ];

        // Kelola pembaruan gambar thumbnail hanya jika user mengunggah file baru
        if ($request->hasFile('gambar')) {
            $gambarNama = time() . '.' . $request->gambar->extension();
            $request->gambar->move(public_path('assets/images/berita'), $gambarNama);
            $data['gambar_thumbnail'] = $gambarNama;
        }

        // Gunakan DB::table() langsung — bukan $berita->update() — agar Eloquent tidak bisa
        // menimpa nilai updated_at yang sudah kita set secara eksplisit di atas.
        \Illuminate\Support\Facades\DB::table('tb_berita')
            ->where('id_berita', $id)
            ->update($data);

        // Kembalikan JSON sukses agar AJAX bisa redirect dengan bersih
        return response()->json([
            'success'  => true,
            'message'  => 'Berita berhasil diupdate!',
            'redirect' => route('berita.index')
        ]);
    }

    public function destroy($id)
    {
        // Cari data berita berdasarkan ID-nya
        $berita = Berita::findOrFail($id);

        // Hapus file gambar thumbnail fisiknya di folder public agar tidak memenuhi storage
        $pathGambar = public_path('assets/images/berita/' . $berita->gambar_thumbnail);
        if (file_exists($pathGambar) && !empty($berita->gambar_thumbnail)) {
            @unlink($pathGambar);
        }

        // Hapus data berita dari database
        $berita->delete();

        // Kembalikan ke halaman manajemen berita dengan pesan sukses
        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus secara permanen!');
    }
}