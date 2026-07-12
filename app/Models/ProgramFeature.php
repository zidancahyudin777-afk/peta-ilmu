<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramFeature extends Model
{
    protected $table = 'program_features';

    protected $fillable = [
        'program_id',
        'feature_text'
    ];

    public $timestamps = false;

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }
}
