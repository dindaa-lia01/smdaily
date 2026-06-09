<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ProfilController extends Controller
{
    public function updatePassword(Request $request)
    {
        $request->validate([
            'passLama' => 'required',
            'passBaru' => 'required|min:8|confirmed',
        ], [
            'passLama.required'  => 'Password lama tidak boleh kosong.',
            'passBaru.required'  => 'Password baru tidak boleh kosong.',
            'passBaru.min'       => 'Password baru minimal 8 karakter.',
            'passBaru.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        // Ambil ID user dari session manual — BUKAN Auth::user()
        // karena sistem login proyek ini menggunakan session kustom,
        // bukan Laravel Auth standar
        $idUser = $request->session()->get('admin_logged_in');

        if (!$idUser) {
            return redirect('/admin/login')
                ->withErrors(['loginError' => 'Sesi Anda telah berakhir. Silakan login kembali.']);
        }

        // Ambil user langsung dari DB tanpa cache Eloquent
        // untuk memastikan kita membaca password hash yang benar-benar tersimpan
        $user = User::where('id_user', $idUser)->first();

        if (!$user) {
            return redirect('/admin/login')
                ->withErrors(['loginError' => 'Akun tidak ditemukan.']);
        }

        // Verifikasi password lama terhadap hash di database
        if (!Hash::check($request->passLama, $user->password)) {
            return back()->withErrors(['passLama' => 'Password lama yang Anda masukkan salah.']);
        }

        // Update password LANGSUNG via DB::table
        // Menghindari Eloquent model yang bisa membawa dirty state
        // dan berpotensi menimpa kolom lain saat save()
        DB::table('tb_user')
            ->where('id_user', $idUser)
            ->update(['password' => Hash::make($request->passBaru)]);

        return back()->with('success', 'Password berhasil diperbarui. Gunakan password baru untuk login berikutnya.');
    }
}