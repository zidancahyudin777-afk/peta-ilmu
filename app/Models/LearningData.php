<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningData extends Model
{
    protected $table = 'learning_data';

    protected $fillable = [
        'user_id',
        'program_id',
        'mata_pelajaran',
        'nilai',
        'nilai_tugas',
        'nilai_kuis',
        'tingkat_kesulitan',
        'kehadiran',
        'gaya_belajar',
        'catatan',
        // Hasil prediksi Random Forest (dari Flask)
        'recommendation_result',
        'confidence',
        'prediction_time',
        // Rekomendasi personal yang dibangun oleh Laravel
        'study_frequency',
        'study_duration',
        'priority',
        'recommended_material',  // JSON string
        'study_suggestion',      // JSON string
        'prediction_date',
    ];

    protected $casts = [
        'confidence' => 'double',
        'prediction_date' => 'datetime',
        'prediction_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Decode recommended_material dari JSON string ke array.
     */
    public function getRecommendedMaterialsAttribute(): array
    {
        if (empty($this->recommended_material)) {
            return [];
        }
        $decoded = json_decode($this->recommended_material, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Decode study_suggestion dari JSON string ke array.
     */
    public function getStudySuggestionsAttribute(): array
    {
        if (empty($this->study_suggestion)) {
            return [];
        }
        $decoded = json_decode($this->study_suggestion, true);
        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Mengambil interpretasi tingkat kepercayaan confidence score.
     */
    public function getTingkatKepercayaanAttribute(): string
    {
        if ($this->confidence === null) {
            return '-';
        }
        $c = (float) $this->confidence;
        if ($c >= 95) return 'Sangat Tinggi';
        if ($c >= 85) return 'Tinggi';
        if ($c >= 70) return 'Sedang';
        return 'Rendah';
    }

    /**
     * Mengambil alasan rekomendasi berdasarkan kategori prediksi.
     */
    public function getAlasanRekomendasiAttribute(): string
    {
        return match ($this->recommendation_result) {
            'Program Remedial Intensif', 'Dasar' => 'Nilai belajar dan pemahaman materi dasar menunjukkan performa yang membutuhkan perhatian ekstra, sehingga sistem merekomendasikan tingkat Dasar / Remedial.',
            'Pendampingan Akademik', 'Menengah' => 'Nilai belajar menunjukkan performa yang masih perlu ditingkatkan sehingga sistem merekomendasikan tingkat Menengah / Pendampingan.',
            'Program Reguler'           => 'Nilai belajar menunjukkan performa stabil dan baik sehingga sistem merekomendasikan Program Reguler.',
            'Program Pengayaan', 'Mahir' => 'Hasil belajar menunjukkan performa yang sangat baik sehingga sistem merekomendasikan tingkat Mahir / Pengayaan.',
            default                     => 'Data belajar belum dianalisis oleh sistem.',
        };
    }

    /**
     * Mengembalikan data personalisasi visual (warna, label) berdasarkan kategori prediksi.
     * Digunakan oleh riwayat.blade.php and rekomendasi.blade.php.
     */
    public function getPersonalizationAttribute(): ?array
    {
        return self::buildPersonalization($this->recommendation_result);
    }

    /**
     * Static helper — bangun data personalisasi berdasarkan kategori.
     * Dipisah agar bisa dipanggil dari Controller tanpa instance model.
     */
    public static function buildPersonalization(?string $category): ?array
    {
        return match ($category) {
            'Program Remedial Intensif', 'Dasar' => [
                'tingkat'      => 'Dasar',
                'warna'        => 'Merah',
                'warna_hex'    => '#dc2626',
                'warna_bg'     => '#fee2e2',
                'warna_text'   => '#991b1b',
                'warna_border' => '#fca5a5',
                'icon'         => 'fas fa-exclamation-triangle',
            ],
            'Pendampingan Akademik', 'Menengah' => [
                'tingkat'      => 'Menengah',
                'warna'        => 'Oranye',
                'warna_hex'    => '#ea580c',
                'warna_bg'     => '#ffedd5',
                'warna_text'   => '#9a3412',
                'warna_border' => '#fdba74',
                'icon'         => 'fas fa-hands-helping',
            ],
            'Program Reguler' => [
                'tingkat'      => 'Sedang',
                'warna'        => 'Biru',
                'warna_hex'    => '#2563eb',
                'warna_bg'     => '#dbeafe',
                'warna_text'   => '#1e40af',
                'warna_border' => '#93c5fd',
                'icon'         => 'fas fa-book-open',
            ],
            'Program Pengayaan', 'Mahir' => [
                'tingkat'      => 'Mahir',
                'warna'        => 'Hijau',
                'warna_hex'    => '#16a34a',
                'warna_bg'     => '#dcfce7',
                'warna_text'   => '#14532d',
                'warna_border' => '#86efac',
                'icon'         => 'fas fa-star',
            ],
            default => null,
        };
    }
}
