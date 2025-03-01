<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    use HasFactory;
    protected $table = 'jabatan';
    protected $fillable = [
        'nama_jabatan',
        'tipe_jabatan',
        'kelas_jabatan',
        'golongan',
    ];

    public function kriteria()
    {
        return $this->hasMany(Kriteria::class, 'id_jabatan', 'id');
    }

    public function standarKompetensi()
    {
        return $this->hasMany(StandarKompetensi::class, 'id_jabatan', 'id');
    }

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_jabatan', 'id');
    }
}
