<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationSubject extends Model
{
    protected $table = 'registration_subjects';

    protected $fillable = [
        'registration_id',
        'subject_name'
    ];

    public $timestamps = false;

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
