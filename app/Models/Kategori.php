<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model 
{
    use HasFactory;

    protected $table = 'tb_kategori'; 
    protected $primaryKey = 'id_kategori';

    // TAMBAHKAN BARIS INI COK UNTUK MATIIN TIMESTAMP OTOMATIS
    public $timestamps = false; 

    public function berita()
    {
        return $this->hasMany(Berita::class, 'id_kategori', 'id_kategori');
    }
}