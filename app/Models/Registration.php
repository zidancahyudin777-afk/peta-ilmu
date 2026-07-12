<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'nama_lengkap',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telepon',
        'email',
        'jenjang',
        'kelas',
        'sekolah',
        'package_id',
        'package_type',
        'durasi',
        'jumlah_hari',
        'nama_ortu',
        'pekerjaan_ortu',
        'telepon_ortu',
        'motivasi',
        'referensi',
        'total_price',
        'status'
    ];

    const UPDATED_AT = null;

    public function subjects()
    {
        return $this->hasMany(RegistrationSubject::class, 'registration_id');
    }
}
