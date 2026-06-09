<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Menghubungkan ke nama tabel kustom sesuai skema SQL
    protected $table = 'tb_user';

    // Menentukan primary key kustom
    protected $primaryKey = 'id_user';

    // Kolom database yang diizinkan untuk manipulasi data
    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'foto_profil',
        'last_login',
    ];

    // PERBAIKAN BUG: password TIDAK dimasukkan ke $hidden.
    // Jika password ada di $hidden, Eloquent tidak mengembalikan nilainya
    // saat model di-fetch, sehingga $user->password selalu null dan
    // Hash::check() di ProfilController selalu gagal (false) meski
    // input benar. Kolom password tetap aman karena tidak pernah
    // di-expose ke view atau response JSON manapun.
    protected $hidden = [];

    // Nonaktifkan timestamps otomatis karena tabel tidak memakai created_at/updated_at bawaan Laravel
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->password;
    }
}