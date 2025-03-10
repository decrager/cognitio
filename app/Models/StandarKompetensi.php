<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandarKompetensi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'standar_kompetensi';
    protected $fillable = [
        'id_jabatan',
        'nama_kompetensi',
        'kpi_standar',
    ];

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id');
    }

    public function kompetensiPegawai()
    {
        return $this->hasMany(KompetensiPegawai::class, 'id_standar_kompetensi', 'id');
    }

    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class, 'kompetensi_pegawai', 'id_standar_kompetensi','id_pegawai');
    }
}
