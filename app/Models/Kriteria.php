<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria';
    protected $fillable = [
        'id_program',
        'id_jabatan',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }

    public function jabatan()
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id');
    }
}
