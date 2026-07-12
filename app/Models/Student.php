<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'username',
        'nama',
        'email',
        'password',
        'jenjang',
        'kelas'
    ];

    const UPDATED_AT = null;
}
