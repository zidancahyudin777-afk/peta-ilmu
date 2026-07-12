@extends('layouts.app-student')

@section('title', 'Detail Rekomendasi Belajar - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <style>
        /* ── Layout ─────────────────────────────────── */
        .rek-container {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            padding: 30px;
        }
        .rek-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .rek-header h2 {
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .rek-header p { color: #64748b; font-size: 14px; margin: 4px 0 0; }

        /* ── Hero Card (kategori utama) ──────────────── */
        .hero-card {
            border-radius: 14px;
            padding: 24px 28px;
            margin-bottom: 28px;
            border: 1px solid;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .hero-icon {
            font-size: 2.4rem;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .hero-body h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 6px;
        }
        .hero-body p { margin: 0; font-size: 14px; opacity: .85; }

        /* ── Stats row ───────────────────────────────── */
        .stat-row {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat-card {
            flex: 1;
            min-width: 140px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px 18px;
            text-align: center;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .stat-card .stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #94a3b8;
            margin-bottom: 6px;
        }
        .stat-card .stat-value {
            font-size: 18px;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }
        .stat-card .stat-sub {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 3px;
        }

        /* ── Section ─────────────────────────────────── */
        .section-block {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px 22px;
            margin-bottom: 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        }
        .section-block h5 {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin: 0 0 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-block h5 i { color: #2563eb; }

        /* ── Materi pills ────────────────────────────── */
        .materi-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .materi-pill {
            background: #eff6ff;
            color: #1e40af;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 5px 14px;
            font-size: 13px;
            font-weight: 500;
        }

        /* ── Saran list ──────────────────────────────── */
        .saran-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .saran-list li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
            color: #475569;
            line-height: 1.5;
        }
        .saran-list li:last-child { border-bottom: none; }
        .saran-list li .saran-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: #2563eb;
            flex-shrink: 0;
            margin-top: 6px;
        }

        /* ── Data siswa ──────────────────────────────── */
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }
        .data-item .data-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .data-item .data-val {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-top: 2px;
        }

        /* ── Confidence bar ──────────────────────────── */
        .confidence-bar-wrap { margin-top: 6px; }
        .confidence-bar {
            height: 8px;
            border-radius: 99px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .confidence-bar-fill {
            height: 100%;
            border-radius: 99px;
            background: #2563eb;
            transition: width 1s ease;
        }

        /* ── Buttons ─────────────────────────────────── */
        .btn-back {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            color: #475569;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-back:hover { background: #f1f5f9; color: #0f172a; }
        .btn-new {
            background: #2563eb;
            color: #fff;
            border: 1px solid #2563eb;
            border-radius: 8px;
            padding: 9px 20px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
        }
        .btn-new:hover { background: #1d4ed8; color: #fff; border-color: #1d4ed8; }
    </style>
@endsection

@section('content')
    <div class="rek-container container-fluid">

        {{-- Header --}}
        <div class="rek-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-chart-line me-2" style="color:#2563eb;"></i>Hasil Rekomendasi Belajar</h2>
                <p>Rekomendasi personal berdasarkan analisis model <strong>Random Forest</strong></p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('siswa.riwayat') }}" class="btn-back">
                    <i class="fas fa-arrow-left me-1"></i> Riwayat
                </a>
                <a href="{{ route('siswa.input') }}" class="btn-new">
                    <i class="fas fa-plus me-1"></i> Input Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" style="border-radius:8px;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @php
            $p = $personalization;
        @endphp

        {{-- Hero — Kategori Rekomendasi --}}
        @if ($record->recommendation_result && $p)
            <div class="hero-card" style="background:{{ $p['warna_bg'] }}; border-color:{{ $p['warna_border'] }}; color:{{ $p['warna_text'] }};">
                <div class="hero-icon"><i class="{{ $p['icon'] }}"></i></div>
                <div class="hero-body">
                    <h3>{{ $record->recommendation_result }}</h3>
                    <p>Prioritas: <strong>{{ $record->priority ?? '-' }}</strong> &nbsp;·&nbsp; Tingkat: <strong>{{ $p['tingkat'] }}</strong></p>
                </div>
            </div>
        @else
            <div class="alert alert-warning mb-4" style="border-radius:10px;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Prediksi belum tersedia. Kemungkinan Flask API tidak aktif saat data diinput.
                <a href="{{ route('siswa.input') }}" class="ms-2 fw-semibold">Coba lagi</a>
            </div>
        @endif

        {{-- Stats Row --}}
        <div class="stat-row">
            <div class="stat-card">
                <div class="stat-label">Confidence Score</div>
                <div class="stat-value">{{ $record->confidence ? number_format($record->confidence, 1) . '%' : '-' }}</div>
                @if ($record->confidence)
                    <div class="confidence-bar-wrap">
                        <div class="confidence-bar">
                            <div class="confidence-bar-fill" id="conf-bar" style="width:0%"></div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="stat-card">
                <div class="stat-label">Tingkat Kepercayaan</div>
                <div class="stat-value" style="font-size:16px; color:#3b82f6;">{{ $record->tingkat_kepercayaan }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Frekuensi Belajar</div>
                <div class="stat-value" style="font-size:15px;">{{ $record->study_frequency ?? '-' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Durasi Belajar</div>
                <div class="stat-value" style="font-size:15px;">{{ $record->study_duration ?? '-' }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Prioritas Belajar</div>
                <div class="stat-value" style="font-size:15px;">{{ $record->priority ?? '-' }}</div>
            </div>
        </div>

        {{-- Data Siswa --}}
        <div class="section-block">
            <h5><i class="fas fa-user-graduate"></i> Data Siswa</h5>
            <div class="data-grid">
                <div class="data-item">
                    <div class="data-label">Nama Siswa</div>
                    <div class="data-val">{{ auth()->user()->name }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Mata Pelajaran</div>
                    <div class="data-val">{{ $record->mata_pelajaran }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Nilai</div>
                    <div class="data-val">
                        <span class="fw-bold {{ $record->nilai >= 80 ? 'text-success' : ($record->nilai >= 60 ? 'text-warning' : 'text-danger') }}">
                            {{ $record->nilai }}
                        </span> / 100
                    </div>
                </div>
                <div class="data-item">
                    <div class="data-label">Tingkat Kesulitan</div>
                    <div class="data-val">{{ $record->tingkat_kesulitan }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Gaya Belajar</div>
                    <div class="data-val">{{ $record->gaya_belajar ?? '-' }}</div>
                </div>
                <div class="data-item">
                    <div class="data-label">Tanggal Prediksi</div>
                    <div class="data-val">
                        {{ $record->prediction_date ? $record->prediction_date->translatedFormat('d M Y, H:i') : ($record->created_at ? $record->created_at->translatedFormat('d M Y, H:i') : '-') }}
                    </div>
                </div>
            </div>
            @if ($record->catatan)
                <div class="mt-3 pt-3" style="border-top:1px solid #e2e8f0;">
                    <div class="data-label" style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;">Catatan</div>
                    <div class="data-val" style="font-size:14px;font-weight:normal;color:#475569;margin-top:4px;">{{ $record->catatan }}</div>
                </div>
            @endif
        </div>

        {{-- Alasan Rekomendasi --}}
        <div class="section-block">
            <h5><i class="fas fa-comment-dots"></i> Alasan Rekomendasi</h5>
            <p class="text-secondary mb-0" style="font-size: 14px; line-height: 1.6;">
                {{ $record->alasan_rekomendasi }}
            </p>
        </div>

        {{-- Materi yang Direkomendasikan --}}
        @if (count($record->recommended_materials) > 0)
            <div class="section-block">
                <h5><i class="fas fa-book"></i> Materi yang Direkomendasikan</h5>
                <p class="text-muted mb-3" style="font-size:13px;">
                    Materi berikut dipilih berdasarkan mata pelajaran <strong>{{ $record->mata_pelajaran }}</strong>
                </p>
                <div class="materi-list">
                    @foreach ($record->recommended_materials as $materi)
                        <span class="materi-pill"><i class="fas fa-bookmark me-1"></i>{{ $materi }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Saran Belajar --}}
        @if (count($record->study_suggestions) > 0)
            <div class="section-block">
                <h5><i class="fas fa-lightbulb"></i> Saran Belajar</h5>
                <ul class="saran-list">
                    @foreach ($record->study_suggestions as $saran)
                        <li>
                            <div class="saran-dot"></div>
                            <span>{{ $saran }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Footer actions --}}
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <a href="{{ route('siswa.riwayat') }}" class="btn-back">
                <i class="fas fa-list me-1"></i> Lihat Semua Riwayat
            </a>
            <a href="{{ route('siswa.input') }}" class="btn-new">
                <i class="fas fa-redo me-1"></i> Input Data Baru
            </a>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Animate confidence bar on load
        window.addEventListener('load', function () {
            const bar = document.getElementById('conf-bar');
            if (bar) {
                setTimeout(function () {
                    bar.style.width = '{{ $record->confidence ?? 0 }}%';
                }, 200);
            }
        });
    </script>
@endsection
