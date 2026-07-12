<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SejarahOrganisasi extends Model
{
    protected $table = 'sejarah_organisasi';

    protected $fillable = [
        'paragraf',
        'urutan'
    ];

    public $timestamps = false;
}
