<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramPackage extends Model
{
    protected $table = 'program_packages';

    protected $fillable = [
        'program_id',
        'package_type',
        'description',
        'package_icon',
        'info',
        'extra_info'
    ];

    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function prices()
    {
        return $this->hasMany(PackagePrice::class, 'package_id');
    }
}
