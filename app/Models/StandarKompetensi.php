<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StandarKompetensi extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'standar_kompetensi';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsToMany(Pegawai::class, 'kompetensi_pegawai', 'id_standar_kompetensi','id_pegawai');
    }
}
