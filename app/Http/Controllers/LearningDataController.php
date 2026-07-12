<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningData;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LearningDataController extends Controller
{
    // ─── Materi per Mata Pelajaran (ditentukan Laravel, bukan ML) ─────────────
    private const MATERI_PER_MAPEL = [
        'Matematika'       => ['Aljabar', 'Fungsi', 'Persamaan Linear'],
        'Bahasa Indonesia' => ['Teks Eksposisi', 'EYD', 'Paragraf'],
        'Bahasa Inggris'   => ['Vocabulary', 'Grammar', 'Reading'],
        'IPA'              => ['Ekosistem', 'Sistem Pencernaan', 'Gaya'],
        'IPS'              => ['Geografi', 'Ekonomi', 'Sejarah'],
        'PKN'              => ['Pancasila', 'Demokrasi', 'UUD 1945'],
        'Komputer'         => ['Algoritma', 'Flowchart', 'Pemrograman Dasar'],
    ];

    // ─── Detail Rekomendasi per Kategori (ditentukan Laravel, bukan ML) ───────
    private const REKOMENDASI_DETAIL = [
        'Program Remedial Intensif' => [
            'study_frequency' => '2–3 sesi per hari',
            'study_duration'  => '90–120 menit',
            'priority'        => 'Sangat Tinggi',
            'study_suggestion' => [
                'Fokus materi dasar',
                'Minimal 20 latihan soal',
                'Disarankan belajar bersama tutor',
            ],
        ],
        'Pendampingan Akademik' => [
            'study_frequency' => '2 sesi per hari',
            'study_duration'  => '60–90 menit',
            'priority'        => 'Tinggi',
            'study_suggestion' => [
                'Ulangi materi yang sulit',
                'Latihan rutin',
                'Diskusi bersama tutor',
            ],
        ],
        'Program Reguler' => [
            'study_frequency' => '1 sesi per hari',
            'study_duration'  => 'sekitar 60 menit',
            'priority'        => 'Sedang',
            'study_suggestion' => [
                'Pertahankan konsistensi belajar',
                'Review materi mingguan',
            ],
        ],
        'Program Pengayaan' => [
            'study_frequency' => '1 sesi per hari',
            'study_duration'  => '45–60 menit',
            'priority'        => 'Pengembangan',
            'study_suggestion' => [
                'Pelajari materi lanjutan',
                'Kerjakan soal tingkat tinggi',
            ],
        ],
    ];

    /**
     * Tampilkan form input data belajar.
     */
    public function create()
    {
        $mataPelajaranList = array_keys(self::MATERI_PER_MAPEL);
        return view('siswa.input', compact('mataPelajaranList'));
    }

    /**
     * Simpan data belajar, panggil Flask API, bangun rekomendasi, simpan ke DB.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mata_pelajaran'   => 'required|string|in:' . implode(',', array_keys(self::MATERI_PER_MAPEL)),
            'nilai'            => 'required|numeric|min:0|max:100',
            'tingkat_kesulitan'=> 'required|string|in:Mudah,Sedang,Sulit',
            'gaya_belajar'     => 'required|string|in:Visual,Audio,Kinestetik',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        $predictionResult = null;
        $confidence       = null;

        // ─── Panggil Flask API ──────────────────────────────────────────────
        $gayaBelajarFlask = $validated['gaya_belajar'] === 'Audio' ? 'Auditori' : $validated['gaya_belajar'];
        $flaskPayload = [
            'nilai'             => (float) $validated['nilai'],
            'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
            'jam_belajar'       => 3.0,   // Default representatif
            'kehadiran'         => 'Baik', // Default representatif
            'gaya_belajar'      => $gayaBelajarFlask,
        ];

        $startTime = microtime(true);
        try {
            $response = Http::timeout(5)->post('http://127.0.0.1:5000/predict', $flaskPayload);
            $responseTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            $userLog = auth()->check()
                ? auth()->user()->name . ' (ID: ' . auth()->id() . ')'
                : 'Guest';

            if ($response->successful()) {
                $resData         = $response->json();
                $predictionResult = $resData['prediction'] ?? null;
                $confidence       = $resData['confidence'] ?? null;

                Log::channel('ml')->info('ML Prediction Success', [
                    'user'          => $userLog,
                    'input'         => $flaskPayload,
                    'prediction'    => $predictionResult,
                    'confidence'    => $confidence . '%',
                    'response_time' => $responseTimeMs . ' ms',
                ]);
            } else {
                Log::channel('ml')->error('ML Prediction Failed', [
                    'user'          => $userLog,
                    'status'        => $response->status(),
                    'body'          => $response->body(),
                    'response_time' => $responseTimeMs . ' ms',
                ]);
            }
        } catch (\Exception $e) {
            $responseTimeMs = round((microtime(true) - $startTime) * 1000, 2);
            Log::channel('ml')->error('ML Prediction Exception', [
                'error'         => $e->getMessage(),
                'response_time' => $responseTimeMs . ' ms',
            ]);
        }

        // ─── Bangun Rekomendasi Personal (oleh Laravel) ────────────────────
        $detail   = self::REKOMENDASI_DETAIL[$predictionResult] ?? null;
        $materiList = self::MATERI_PER_MAPEL[$validated['mata_pelajaran']] ?? [];

        try {
            $record = LearningData::create([
                'user_id'             => auth()->id(),
                'mata_pelajaran'      => $validated['mata_pelajaran'],
                'nilai'               => $validated['nilai'],
                'tingkat_kesulitan'   => $validated['tingkat_kesulitan'],
                'gaya_belajar'        => $validated['gaya_belajar'],
                'catatan'             => $validated['catatan'] ?? null,
                // Hasil Random Forest dari Flask
                'recommendation_result' => $predictionResult,
                'confidence'            => $confidence,
                'prediction_time'       => now(),
                // Rekomendasi personal yang dibangun Laravel
                'study_frequency'     => $detail['study_frequency'] ?? null,
                'study_duration'      => $detail['study_duration'] ?? null,
                'priority'            => $detail['priority'] ?? null,
                'recommended_material'=> $materiList ? json_encode($materiList) : null,
                'study_suggestion'    => $detail ? json_encode($detail['study_suggestion']) : null,
                'prediction_date'     => now(),
            ]);

            // Redirect ke halaman detail rekomendasi
            return redirect()
                ->route('siswa.rekomendasi.show', $record->id)
                ->with('success', 'Prediksi berhasil! Lihat rekomendasi belajar Anda di bawah ini.');
        } catch (\Exception $e) {
            Log::error('Error saving learning data: ' . $e->getMessage());
            return back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tampilkan detail rekomendasi untuk satu record.
     */
    public function show($id)
    {
        $record = LearningData::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $personalization = LearningData::buildPersonalization($record->recommendation_result);

        return view('siswa.rekomendasi', compact('record', 'personalization'));
    }

    /**
     * Tampilkan daftar semua rekomendasi (riwayat ringkas).
     * Route: GET /siswa/rekomendasi — redirect ke riwayat agar tidak tumpang tindih.
     */
    public function recommendation()
    {
        return redirect()->route('siswa.riwayat');
    }
}
