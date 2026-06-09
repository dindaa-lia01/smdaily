<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    // KUNCI MATI: Mengarahkan langsung ke nama tabel asli lu di phpMyAdmin!
    protected $table = 'tb_berita'; 

    // KUNCI PRIMARY KEY: Menyesuaikan field primary key database lu!
    protected $primaryKey = 'id_berita'; 

    // KONVERSI OTOMATIS: Memaksa created_at dibaca sebagai objek Carbon Datetime resmi
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke tabel kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // MATIKAN auto-timestamp Laravel sepenuhnya.
    // Dengan ini Laravel tidak akan pernah menyentuh created_at atau updated_at secara otomatis.
    // Kedua kolom ini dikelola 100% secara manual via controller dan DB::table().
    public $timestamps = false;

    protected $fillable = [
        'judul_berita', 'slug_berita', 'id_kategori', 'id_user', 'status',
        'penulis', 'isi_berita', 'gambar_thumbnail', 'views',
        // created_at dan updated_at SENGAJA dikeluarkan dari fillable
        // agar tidak bisa diisi secara tidak sengaja oleh Eloquent create/update
    ];
}