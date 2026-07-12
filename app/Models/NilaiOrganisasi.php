<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiOrganisasi extends Model
{
    protected $table = 'nilai_organisasi';

    protected $fillable = [
        'icon',
        'nama',
        'deskripsi'
    ];

    public $timestamps = false;
}
