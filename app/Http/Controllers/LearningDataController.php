<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LearningData;
use App\Models\Program;
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
        // Dukungan untuk Kategori Target Baru
        'Dasar' => [
            'study_frequency' => '2–3 sesi per hari',
            'study_duration'  => '90–120 menit',
            'priority'        => 'Sangat Tinggi',
            'study_suggestion' => [
                'Fokus materi dasar',
                'Minimal 20 latihan soal',
                'Disarankan belajar bersama tutor',
            ],
        ],
        'Menengah' => [
            'study_frequency' => '2 sesi per hari',
            'study_duration'  => '60–90 menit',
            'priority'        => 'Tinggi',
            'study_suggestion' => [
                'Ulangi materi yang sulit',
                'Latihan rutin',
                'Diskusi bersama tutor',
            ],
        ],
        'Mahir' => [
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
        $pendingRecords = LearningData::with('program')
            ->where('user_id', auth()->id())
            ->where('recommendation_result', 'Menunggu Input Siswa')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('siswa.input', compact('pendingRecords'));
    }

    /**
     * Simpan data belajar, panggil Flask API, bangun rekomendasi, simpan ke DB.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'learning_data_id' => 'required|exists:learning_data,id',
            'study_duration'   => 'required|numeric|min:1|max:5',
            'tingkat_kesulitan'=> 'required|string|in:Mudah,Sedang,Sulit',
            'catatan'          => 'nullable|string|max:1000',
        ]);

        // Ambil record hasil belajar yang diinput oleh Admin
        $record = LearningData::where('id', $validated['learning_data_id'])
            ->where('user_id', auth()->id())
            ->where('recommendation_result', 'Menunggu Input Siswa')
            ->firstOrFail();

        $program = Program::find($record->program_id);
        $mataPelajaran = $record->mata_pelajaran;

        // Gunakan data akademik dari Admin untuk payload Flask API
        $nilaiRerata = $record->nilai;
        $nilaiTugas = $record->nilai_tugas;
        $nilaiKuis = $record->nilai_kuis;
        $kehadiran = $record->kehadiran;

        $predictionResult = null;
        $confidence       = null;
        $flaskOffline     = false;

        // ─── Bangun Payload Flask API ─────────────────────────────────────────
        $flaskPayload = [
            'nilai_tugas'       => (float) $nilaiTugas,
            'nilai_kuis'        => (float) $nilaiKuis,
            'kehadiran'         => $kehadiran,
            'study_duration'    => (float) $validated['study_duration'],
            'tingkat_kesulitan' => $validated['tingkat_kesulitan'],
        ];

        $startTime = microtime(true);
        try {
            // timeout pendek agar jika Flask offline, aplikasi Laravel tidak hang lama
            $response = Http::timeout(3)->post('http://127.0.0.1:5000/predict', $flaskPayload);
            $responseTimeMs = round((microtime(true) - $startTime) * 1000, 2);

            $userLog = auth()->check()
                ? auth()->user()->name . ' (ID: ' . auth()->id() . ')'
                : 'Guest';

            if ($response->successful()) {
                $resData          = $response->json();
                $predictionResult = $resData['prediction'] ?? null;
                $confidence       = isset($resData['confidence']) ? round((float) $resData['confidence'], 2) : null;

                Log::channel('ml')->info('ML Prediction Success', [
                    'user'          => $userLog,
                    'input'         => $flaskPayload,
                    'prediction'    => $predictionResult,
                    'confidence'    => $confidence . '%',
                    'response_time' => $responseTimeMs . ' ms',
                ]);
            } else {
                $flaskOffline = true;
                Log::channel('ml')->error('ML Prediction Failed', [
                    'user'          => $userLog,
                    'status'        => $response->status(),
                    'body'          => $response->body(),
                    'response_time' => $responseTimeMs . ' ms',
                ]);
            }
        } catch (\Exception $e) {
            $flaskOffline = true;
            $responseTimeMs = round((microtime(true) - $startTime) * 1000, 2);
            Log::channel('ml')->error('ML Prediction Exception (Flask Offline)', [
                'error'         => $e->getMessage(),
                'response_time' => $responseTimeMs . ' ms',
            ]);
        }

        // ─── Klasifikasi Fallback jika API Flask Offline ───────────────────────
        $finalClass = $predictionResult;
        if (!$predictionResult) {
            if ($nilaiRerata >= 80 && $kehadiran !== 'Kurang') {
                $finalClass = 'Mahir';
            } elseif ($nilaiRerata >= 60) {
                $finalClass = 'Menengah';
            } else {
                $finalClass = 'Dasar';
            }
        }

        // ─── Bangun Rekomendasi Personal ──────────────────────────────────────
        $detail     = self::REKOMENDASI_DETAIL[$finalClass] ?? null;
        $materiList = $program && is_array($program->subjects) ? $program->subjects : [];

        try {
            $record->update([
                'tingkat_kesulitan'     => $validated['tingkat_kesulitan'],
                'study_duration'        => $validated['study_duration'],
                'catatan'               => $validated['catatan'] ?? null,
                // Hasil prediksi (Flask atau Fallback)
                'recommendation_result' => $finalClass,
                'confidence'            => $confidence,
                'prediction_time'       => now(),
                // Parameter rekomendasi terstruktur
                'study_frequency'       => $detail['study_frequency'] ?? null,
                'study_duration_rec'    => $detail['study_duration'] ?? null, // Kolom rekomendasi durasi
                'priority'              => $detail['priority'] ?? null,
                'recommended_material'  => $materiList ? json_encode($materiList) : null,
                'study_suggestion'      => $detail ? json_encode($detail['study_suggestion']) : null,
                'prediction_date'       => now(),
            ]);

            $successMsg = $flaskOffline
                ? 'Data belajar disimpan, namun sistem rekomendasi AI sedang offline.'
                : 'Prediksi berhasil! Lihat rekomendasi belajar Anda di bawah ini.';

            return redirect()
                ->route('siswa.rekomendasi.show', $record->id)
                ->with($flaskOffline ? 'warning' : 'success', $successMsg);
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
