<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawai';
    protected $fillable = [
        'id_user',
        'id_jabatan',
        'id_unit',
        'nama',
        'nip',
        'nik',
        'telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'tipe',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id');
    }

    public function unit()
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit', 'id');
    }

    public function kompetensi()
    {
        return $this->hasMany(KompetensiPegawai::class, 'id_pegawai', 'id');
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'id_pegawai', 'id');
    }

    public function standar_kompetensi()
    {
        return $this->belongsToMany(StandarKompetensi::class, 'kompetensi_pegawai', 'id_standar_kompetensi','id_pegawai')->withPivot('kpi');
    }

    public function kompetensi_pegawai()
    {
        return $this->hasMany(KompetensiPegawai::class, 'id_pegawai', 'id')
        ->join('standar_kompetensi', 'kompetensi_pegawai.id_standar_kompetensi', 'standar_kompetensi.id')
        ->select('kompetensi_pegawai.*', 'standar_kompetensi.nama_kompetensi', 'standar_kompetensi.kpi_standar');
    }

    public function scopeIsPegawai()
    {
       // Where By users table
         return $this->whereHas('user', function($query){
              $query->where('role', 'pegawai');
         });
    }
}
