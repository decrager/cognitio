<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $table = 'assignment';
    protected $fillable = [
        'id_program',
        'id_pegawai',
        'status',
        'assigned_by_id',
        'assigned_by_name',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id');
    }
}
