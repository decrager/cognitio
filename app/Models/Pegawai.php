<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'pegawai';
    protected $guarded = [];

    public function standar_kompetensi()
    {
        return $this->belongsToMany(StandarKompetensi::class, 'kompetensi_pegawai', 'id_standar_kompetensi','id_pegawai')->withPivot('kpi');
    }
}
