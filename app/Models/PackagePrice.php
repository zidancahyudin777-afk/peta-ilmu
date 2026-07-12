<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackagePrice extends Model
{
    protected $table = 'package_prices';

    protected $fillable = [
        'package_id',
        'price_label',
        'price'
    ];

    public $timestamps = false;

    public function package()
    {
        return $this->belongsTo(ProgramPackage::class, 'package_id');
    }
}
