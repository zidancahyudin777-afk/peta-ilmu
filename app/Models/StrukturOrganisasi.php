<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasi';

    protected $fillable = [
        'level',
        'nama',
        'posisi',
        'deskripsi',
        'foto'
    ];

    public $timestamps = false;
}
