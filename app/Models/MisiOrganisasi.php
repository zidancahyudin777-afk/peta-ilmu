<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MisiOrganisasi extends Model
{
    protected $table = 'misi_organisasi';

    protected $fillable = [
        'misi_text',
        'urutan'
    ];

    public $timestamps = false;
}
