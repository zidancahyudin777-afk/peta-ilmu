<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimPengajar extends Model
{
    protected $table = 'tim_pengajar';

    protected $fillable = [
        'nama',
        'mata_pelajaran_id',
        'deskripsi',
        'foto',
        'status'
    ];

    public $timestamps = false;

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
