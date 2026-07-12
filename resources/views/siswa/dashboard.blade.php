@extends('layouts.app-student')

@section('title', 'Dashboard Siswa - Bimbingan Belajar Peta Ilmu')

@section('styles')
<style>
    /* ── Welcome banner ───────────────────────────────── */
    .welcome-banner {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px 28px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 28px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        animation: slideInUp 0.5s cubic-bezier(0.25,1,0.5,1) forwards;
    }
    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(16px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .welcome-avatar {
        width: 52px; height: 52px;
        background: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .welcome-text h2 {
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0 0 4px;
        color: #0f172a;
    }
    .welcome-text p {
        margin: 0;
        font-size: 0.9rem;
        color: #475569;
    }
    .welcome-meta {
        margin-left: auto;
        text-align: right;
        flex-shrink: 0;
    }
    .welcome-meta .badge-jenjang {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #475569;
        padding: 5px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }
    @media (max-width: 575px) {
        .welcome-banner { flex-direction: column; text-align: center; }
        .welcome-meta   { margin-left: 0; text-align: center; }
    }

    /* ── Stat cards ───────────────────────────────────── */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: all 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }

    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .stat-card.blue  .stat-icon { background: #eff6ff; color: #2563eb; }
    .stat-card.green .stat-icon { background: #ecfdf5; color: #10b981; }
    .stat-card.amber .stat-icon { background: #fffbeb; color: #f59e0b; }
    .stat-card.indigo .stat-icon { background: #eef2ff; color: #6366f1; }

    .stat-info .stat-label {
        font-size: 11.5px;
        font-weight: 500;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 3px;
    }
    .stat-info .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* ── Menu cards ───────────────────────────────────── */
    .section-label {
        font-size: 13px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 14px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 28px;
    }

    .menu-card {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .menu-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
        color: inherit;
        text-decoration: none;
    }

    .menu-card-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.25rem;
    }
    .menu-card.blue  .menu-card-icon { background: #eff6ff; color: #2563eb; }
    .menu-card.green .menu-card-icon { background: #ecfdf5; color: #10b981; }
    .menu-card.amber .menu-card-icon { background: #fffbeb; color: #f59e0b; }
    .menu-card.slate .menu-card-icon { background: #f1f5f9; color: #64748b; }

    .menu-card-body h5 {
        font-size: 15px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px;
    }
    .menu-card-body p {
        font-size: 12.5px;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }
    .menu-card-arrow {
        margin-left: auto;
        align-self: center;
        color: #cbd5e1;
        font-size: 13px;
        transition: transform 0.2s ease, color 0.2s ease;
    }
    .menu-card:hover .menu-card-arrow {
        transform: translateX(4px);
        color: #94a3b8;
    }

    /* Disabled card */
    .menu-card.disabled-card {
        cursor: not-allowed;
        opacity: 0.6;
        pointer-events: none;
    }

    /* ── Info section ─────────────────────────────────── */
    .info-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        padding: 24px 28px;
        border: 1px solid #e2e8f0;
    }
    .info-card-title {
        display: flex; align-items: center; gap: 10px;
        font-size: 15px; font-weight: 700; color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 1px solid #e2e8f0;
    }
    .info-card-title i { color: #2563eb; }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child { border-bottom: none; padding-bottom: 0; }
    .info-label { font-size: 13.5px; color: #64748b; font-weight: 500; }
    .info-value { font-size: 13.5px; color: #1e293b; font-weight: 600; }

    /* ── Pesan Bimbingan panel ────────────────────────── */
    .bimbingan-panel {
        background: #eff6ff;
        border-radius: 16px;
        border: 1px solid #bfdbfe;
        padding: 22px 26px;
        margin-bottom: 28px;
        display: flex;
        align-items: flex-start;
        gap: 18px;
    }
    .bimbingan-icon-wrap {
        width: 48px; height: 48px;
        background: #2563eb;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        color: #fff;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .bimbingan-body { flex: 1; min-width: 0; }
    .bimbingan-body h6 {
        font-size: 14px;
        font-weight: 700;
        color: #1e3a8a;
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bimbingan-body p {
        font-size: 13px;
        color: #475569;
        margin: 0 0 14px;
        line-height: 1.6;
    }
    .bimbingan-tips {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }
    .bimbingan-tip {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        color: #334155;
    }
    .bimbingan-tip i {
        color: #10b981;
        font-size: 11px;
        flex-shrink: 0;
    }
    @media (max-width: 575px) {
        .bimbingan-panel { flex-direction: column; gap: 14px; }
        .bimbingan-icon-wrap { width: 40px; height: 40px; font-size: 1rem; }
    }
</style>
@endsection

@section('content')

    {{-- ── Welcome banner ─────────────────────────────── --}}
    <div class="welcome-banner">
        <div class="welcome-avatar">
            <i class="fas fa-user-graduate"></i>
        </div>
        <div class="welcome-text">
            <h2>Selamat datang, {{ $student->name ?? $student->nama ?? 'Siswa' }}! 👋</h2>
            <p>Semangat belajar hari ini — setiap langkah kecil membawamu lebih dekat ke tujuanmu.</p>
        </div>
        <div class="welcome-meta">
            <div class="badge-jenjang">
                <i class="fas fa-graduation-cap me-1"></i>
                @if($student->jenjang)
                    {{ strtoupper($student->jenjang) }}
                    @if($student->kelas)
                        &nbsp;·&nbsp; Kelas {{ $student->kelas }}
                    @endif
                @else
                    Siswa
                @endif
            </div>
        </div>
    </div>

    {{-- ── Stats row ─────────────────────────────────────── --}}
    <div class="stats-row">

        {{-- Status: always real (user is authenticated) --}}
        <div class="stat-card blue">
            <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
            <div class="stat-info">
                <div class="stat-label">Status</div>
                <div class="stat-value" style="color:#10b981;">Aktif</div>
            </div>
        </div>

        {{-- Jenjang: only show real DB value --}}
        <div class="stat-card green">
            <div class="stat-icon"><i class="fas fa-graduation-cap"></i></div>
            <div class="stat-info">
                <div class="stat-label">Jenjang</div>
                <div class="stat-value">
                    @if($student->jenjang)
                        {{ strtoupper($student->jenjang) }}
                    @else
                        <span style="font-size:12px; font-weight:500; color:#94a3b8;">Belum diatur</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kelas: only show real DB value --}}
        <div class="stat-card amber">
            <div class="stat-icon"><i class="fas fa-chalkboard-teacher"></i></div>
            <div class="stat-info">
                <div class="stat-label">Kelas</div>
                <div class="stat-value">
                    @if($student->kelas)
                        {{ $student->kelas }}
                    @else
                        <span style="font-size:12px; font-weight:500; color:#94a3b8;">Belum diatur</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Learning data count: real from DB --}}
        <div class="stat-card indigo">
            <div class="stat-icon"><i class="fas fa-book-open"></i></div>
            <div class="stat-info">
                <div class="stat-label">Data Belajar</div>
                <div class="stat-value">
                    @if($learningCount > 0)
                        {{ $learningCount }} <span style="font-size:12px; font-weight:500; color:#94a3b8;">entri</span>
                    @else
                        <span style="font-size:12px; font-weight:500; color:#94a3b8;">Belum ada</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ── Pesan Bimbingan ────────────────────────────── --}}
    <div class="bimbingan-panel">
        <div class="bimbingan-icon-wrap">
            <i class="fas fa-comment-dots"></i>
        </div>
        <div class="bimbingan-body">
            <h6>
                <i class="fas fa-circle" style="font-size:7px; color:#3b82f6;"></i>
                Pesan Bimbingan
            </h6>
            <p>
                Selamat datang di sistem <strong>Peta Ilmu</strong>. Gunakan fitur
                <strong>Input Data Belajar</strong> untuk mendapatkan rekomendasi pembelajaran
                terbaik yang disesuaikan dengan kemampuan dan gaya belajarmu.
            </p>
            <div class="bimbingan-tips">
                <div class="bimbingan-tip">
                    <i class="fas fa-check-circle"></i>
                    Isi nilai dan tingkat kesulitan mata pelajaran secara rutin setiap sesi belajar.
                </div>
                <div class="bimbingan-tip">
                    <i class="fas fa-check-circle"></i>
                    Lihat halaman <strong>Rekomendasi</strong> untuk mendapatkan saran program belajar otomatis.
                </div>
                <div class="bimbingan-tip">
                    <i class="fas fa-check-circle"></i>
                    Konsultasikan hasilnya dengan tutor untuk perkembangan belajar yang optimal.
                </div>
            </div>
        </div>
    </div>

    @if($latestLearning && $latestLearning->recommendation_result)
    {{-- ── Ringkasan Rekomendasi Terakhir (Machine Learning) ── --}}
    <div class="card border p-4 mb-4" style="border-radius: 16px; border-color: #e2e8f0; background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
        <div class="d-flex align-items-center mb-3">
            <div class="p-2 rounded-3 me-3 d-flex align-items-center justify-content-center text-white" style="background: #2563eb; width: 48px; height: 48px; font-size: 1.4rem;">
                <i class="fas fa-brain"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0" style="color: #1e293b; font-size: 16px;">Ringkasan Rekomendasi Terakhir</h5>
                <p class="text-muted small mb-0">Hasil analisis model Random Forest untuk perkembangan belajar Anda</p>
            </div>
        </div>

        <div class="row g-3">
            <!-- Kategori Terakhir & Confidence -->
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%;">
                    <div class="text-muted small mb-1" style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase;">Kategori Rekomendasi Terakhir</div>
                    <div class="fw-bold text-dark mb-2" style="font-size: 17px; line-height: 1.3;">{{ $latestLearning->recommendation_result }}</div>
                    
                    <div class="d-flex flex-wrap gap-2 align-items-center mt-2">
                        @if($latestLearning->confidence)
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-2.5 py-1" style="font-weight: 600; font-size: 11px; border: 1px solid rgba(0,0,0,0.05);">
                                Confidence: {{ number_format($latestLearning->confidence, 1) }}%
                            </span>
                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: {{ $latestLearning->personalization['warna_bg'] ?? '#e0e7ff' }}; color: {{ $latestLearning->personalization['warna_text'] ?? '#3730a3' }}; font-weight: 600; font-size: 11px; border: 1px solid rgba(0,0,0,0.05);">
                                Kepercayaan: {{ $latestLearning->tingkat_kepercayaan }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Parameter Detail Ringkasan -->
            <div class="col-md-6">
                <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%; display: flex; flex-direction: column; justify-content: center; gap: 8px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small" style="font-size: 11.5px; font-weight: 600; letter-spacing: 0.5px;">PRIORITAS BELAJAR</span>
                        <span class="badge" style="background-color: {{ $latestLearning->personalization['warna_bg'] ?? '#f1f5f9' }}; color: {{ $latestLearning->personalization['warna_text'] ?? '#475569' }}; font-weight: 700; font-size: 11px;">
                            {{ $latestLearning->priority ?? '-' }}
                        </span>
                    </div>
                    <hr style="margin: 4px 0; border-color: #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small" style="font-size: 11.5px; font-weight: 600; letter-spacing: 0.5px;">MATA PELAJARAN</span>
                        <span class="fw-bold text-dark" style="font-size: 12.5px;">{{ $latestLearning->mata_pelajaran }}</span>
                    </div>
                    <hr style="margin: 4px 0; border-color: #e2e8f0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small" style="font-size: 11.5px; font-weight: 600; letter-spacing: 0.5px;">TANGGAL PREDIKSI</span>
                        <span class="text-secondary" style="font-size: 12px; font-weight: 500;">
                            {{ $latestLearning->prediction_date ? $latestLearning->prediction_date->translatedFormat('d M Y, H:i') : $latestLearning->created_at->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alasan Singkat Rekomendasi -->
        <div class="mt-3 pt-3" style="border-top: 1px dashed #e2e8f0;">
            <div class="fw-bold text-dark mb-1" style="font-size: 13px;">
                <i class="fas fa-lightbulb me-2 text-warning"></i>Alasan Rekomendasi:
            </div>
            <p class="text-secondary mb-0" style="font-size: 12.5px; line-height: 1.5;">
                {{ $latestLearning->alasan_rekomendasi }}
            </p>
        </div>
        
        <div class="mt-3 text-end">
            <a href="{{ route('siswa.rekomendasi.show', $latestLearning->id) }}" class="btn btn-sm btn-outline-primary" style="border-radius: 6px; font-size: 12px; font-weight: 600; padding: 5px 15px;">
                Lihat Rekomendasi Lengkap <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
    @endif

    {{-- ── Menu cards ────────────────────────────────────── --}}
    <div class="section-label">Menu Utama</div>
    <div class="menu-grid">

        <a href="{{ route('siswa.input') }}" class="menu-card blue">
            <div class="menu-card-icon"><i class="fas fa-pen-to-square"></i></div>
            <div class="menu-card-body">
                <h5>Input Data Belajar</h5>
                <p>Catat nilai dan data perkembangan belajar terbaru kamu</p>
            </div>
            <i class="fas fa-chevron-right menu-card-arrow"></i>
        </a>

        <a href="{{ route('siswa.rekomendasi') }}" class="menu-card green">
            <div class="menu-card-icon"><i class="fas fa-brain"></i></div>
            <div class="menu-card-body">
                <h5>Lihat Rekomendasi</h5>
                <p>Dapatkan rekomendasi program belajar berbasis decision tree</p>
            </div>
            <i class="fas fa-chevron-right menu-card-arrow"></i>
        </a>

        <a href="{{ route('siswa.riwayat') }}" class="menu-card amber">
            <div class="menu-card-icon"><i class="fas fa-clock-rotate-left"></i></div>
            <div class="menu-card-body">
                <h5>Riwayat Belajar</h5>
                <p>Lihat seluruh riwayat data belajar yang pernah diinput</p>
            </div>
            <i class="fas fa-chevron-right menu-card-arrow"></i>
        </a>

        <a href="{{ route('siswa.dashboard') }}" class="menu-card slate">
            <div class="menu-card-icon"><i class="fas fa-user-circle"></i></div>
            <div class="menu-card-body">
                <h5>Profil Siswa</h5>
                <p>Lihat informasi akun dan data profil kamu</p>
            </div>
            <i class="fas fa-chevron-right menu-card-arrow"></i>
        </a>

    </div>

    {{-- ── Student info ──────────────────────────────────── --}}
    <div class="section-label">Informasi Akun</div>
    <div class="info-card">
        <div class="info-card-title">
            <i class="fas fa-id-card-clip"></i> Data Siswa
        </div>

        <div class="info-row">
            <span class="info-label">Nama Lengkap</span>
            <span class="info-value">{{ $student->name ?? $student->nama ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Username</span>
            <span class="info-value">{{ $student->username ?? explode('@', $student->email ?? 'siswa@-')[0] }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $student->email ?? '-' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Jenjang</span>
            <span class="info-value">
                @if($student->jenjang)
                    {{ strtoupper($student->jenjang) }}
                @else
                    <span style="font-size:12px; background:#f1f5f9; color:#94a3b8; padding:3px 10px; border-radius:20px; font-weight:500;">Belum diatur</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Kelas</span>
            <span class="info-value">
                @if($student->kelas)
                    {{ $student->kelas }}
                @else
                    <span style="font-size:12px; background:#f1f5f9; color:#94a3b8; padding:3px 10px; border-radius:20px; font-weight:500;">Belum diatur</span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Data Belajar</span>
            <span class="info-value">
                @if($learningCount > 0)
                    {{ $learningCount }} entri
                    @if($latestLearning)
                        <span style="font-size:11px; color:#94a3b8; margin-left:6px;">
                            · terakhir {{ $latestLearning->created_at->diffForHumans() }}
                        </span>
                    @endif
                @else
                    <span style="font-size:12px; background:#fff7ed; color:#c2410c; padding:3px 10px; border-radius:20px; font-weight:500;">
                        <i class="fas fa-exclamation-circle" style="font-size:10px;"></i> Belum ada data belajar
                    </span>
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="info-value" style="color: #10b981;">
                <i class="fas fa-circle" style="font-size: 8px; margin-right: 5px;"></i> Aktif
            </span>
        </div>
    </div>

@endsection
