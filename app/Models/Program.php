<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';

    protected $fillable = [
        'program_code',
        'category',
        'icon',
        'title',
        'description',
        'duration',
        'frequency',
        'subjects'
    ];

    protected $casts = [
        'subjects' => 'array',
    ];

    public function features()
    {
        return $this->hasMany(ProgramFeature::class, 'program_id');
    }

    public function packages()
    {
        return $this->hasMany(ProgramPackage::class, 'program_id');
    }
}
