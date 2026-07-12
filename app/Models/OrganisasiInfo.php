<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganisasiInfo extends Model
{
    protected $table = 'organisasi_info';

    protected $fillable = [
        'visi',
        'tahun_berdiri',
        'jumlah_siswa_awal'
    ];

    public $timestamps = false;
}
