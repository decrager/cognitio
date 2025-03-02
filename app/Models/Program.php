<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $table = 'program';
    protected $fillable = [
        'nama_pelatihan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'lokasi',
        'penyelenggara',
    ];

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'id_program', 'id');
    }

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'id_program', 'id');
    }
}
