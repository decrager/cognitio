<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KompetensiPegawai extends Model
{
    use HasFactory;
    protected $table = 'kompetensi_pegawai';
    protected $fillable = [
        'id_pegawai',
        'id_standar_kompetensi',
        'kpi',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }

    public function standarKompetensi()
    {
        return $this->belongsTo(StandarKompetensi::class, 'id_standar_kompetensi', 'id');
    }
}
