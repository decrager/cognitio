<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitKerja extends Model
{
    use HasFactory;
    protected $table = 'unit_kerja';
    protected $fillable = [
        'nama_unit',
        'lokasi',
        'status',
        'anggaran',
    ];

    public function pegawai()
    {
        return $this->hasMany(Pegawai::class, 'id_unit_kerja', 'id');
    }
}
