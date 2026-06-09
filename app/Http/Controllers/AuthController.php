<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input Form
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usernameClean = trim($request->username);

        // 2. Ambil data user dari database
        $user = User::where('username', $usernameClean)->first();

        // 3. Cek keberadaan akun dan validasi password
        if ($user && Hash::check($request->password, $user->password)) {

            // 4. Daftarkan session manual (bypass Laravel Auth
            // karena primary key kolom adalah 'id_user', bukan 'id')
            $request->session()->put('admin_logged_in', $user->id_user);
            $request->session()->put('user_nama', $user->nama_lengkap);

            // Update last_login LANGSUNG via DB::table
            // agar Eloquent tidak membawa dirty state yang bisa
            // menimpa password saat save() dipanggil
            DB::table('tb_user')
                ->where('id_user', $user->id_user)
                ->update(['last_login' => now()]);

            // Bersihkan token session lama dan buat yang baru demi keamanan
            $request->session()->regenerate();

            return redirect()->to('/admin/dashboard')
                ->with('success', 'Login Berhasil! Selamat Datang Kembali.');
        }

        // Jika salah password/username
        return back()->withErrors([
            'loginError' => 'Kombinasi Username atau Password salah!',
        ])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        // Bersihkan seluruh session kustom saat admin keluar
        $request->session()->forget(['admin_logged_in', 'user_nama']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}