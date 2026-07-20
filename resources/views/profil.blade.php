@extends('layouts.public')

@section('title', 'Profil - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <link rel="stylesheet" href="{{ asset('petailmu/styleprofil.css') }}" />
@endsection

@php
use Illuminate\Support\Facades\DB;

try {
    $organisasi_info = DB::table('organisasi_info')->first();
    $sejarah_paragraf = DB::table('sejarah_organisasi')->orderBy('urutan')->pluck('paragraf')->toArray();
    $misi_list = DB::table('misi_organisasi')->orderBy('urutan')->pluck('misi_text')->toArray();
    $nilai_nilai = DB::table('nilai_organisasi')->orderBy('id')->get();
    $struktur_organisasi = DB::table('struktur_organisasi')->orderBy('level')->orderBy('id')->get();
    
    $tim_pengajar = DB::table('tim_pengajar as tp')
        ->join('mata_pelajaran as mp', 'tp.mata_pelajaran_id', '=', 'mp.id')
        ->where('tp.status', 'aktif')
        ->select('tp.nama', 'mp.nama as mata_pelajaran', 'mp.kode as mata_pelajaran_kode', 'tp.deskripsi', 'tp.foto')
        ->orderBy('mp.nama')
        ->orderBy('tp.nama')
        ->get();

    $mapel_list = DB::table('mata_pelajaran')->orderBy('nama')->get();
    $mata_pelajaran_filter = ['all' => 'Semua'];
    foreach ($mapel_list as $mp) {
        $mata_pelajaran_filter[$mp->kode] = $mp->nama;
    }

    $contacts = DB::table('kontak_info')->where('status', 'aktif')->orderBy('jenis')->orderBy('urutan')->get();
    $kontak_info = [
        'alamat' => [],
        'telepon' => []
    ];
    foreach ($contacts as $c) {
        if ($c->jenis == 'alamat') {
            $kontak_info['alamat'][] = $c->nilai;
        } elseif ($c->jenis == 'telepon') {
            $kontak_info['telepon'][] = $c->nilai;
        }
    }

    $sejarah = [
        "tahun_berdiri" => $organisasi_info->tahun_berdiri ?? '2024',
        "jumlah_siswa_awal" => $organisasi_info->jumlah_siswa_awal ?? '5',
        "deskripsi" => $sejarah_paragraf ?: ['Data tidak dapat dimuat dari database.']
    ];
    
    $visi = $organisasi_info->visi ?? 'Data tidak dapat dimuat dari database.';
    $misi = $misi_list ?: ['Data tidak dapat dimuat dari database.'];

} catch (\Exception $e) {
    error_log('Profil Page Error: ' . $e->getMessage());
    $sejarah = [
        "tahun_berdiri" => "2024",
        "jumlah_siswa_awal" => "5",
        "deskripsi" => ["Data tidak dapat dimuat dari database."]
    ];
    $visi = "Data tidak dapat dimuat dari database.";
    $misi = ["Data tidak dapat dimuat dari database."];
    $nilai_nilai = [];
    $struktur_organisasi = [];
    $tim_pengajar = [];
    $mata_pelajaran_filter = ['all' => 'Semua'];
    $kontak_info = ['alamat' => [], 'telepon' => []];
}
@endphp

@section('content')
    <!-- Page Header -->
    <section class="page-header page-header-profil">
        <div class="container">
            <div class="page-header-content">
                <h1>Profil Lembaga</h1>
                <p style="margin: 0 !important; margin-bottom: 30px !important; text-align: left !important;">Mengenal lebih dekat Bimbingan Belajar Peta Ilmu</p>
                <nav class="breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span class="separator">/</span>
                    <span class="current">Profil</span>
                </nav>
            </div>
        </div>
        <div class="page-header-bg"></div>
    </section>

    <!-- Sejarah Section -->
    <section class="sejarah-section">
        <div class="container">
            <div class="sejarah-wrapper">
                <div class="sejarah-content">
                    <div class="section-header">
                        <h2>Sejarah Peta Ilmu</h2>
                        <div class="section-divider"></div>
                    </div>
                    <div class="sejarah-text">
                        @foreach ($sejarah['deskripsi'] as $paragraf)
                            <p>{{ $paragraf }}</p>
                        @endforeach
                    </div>
                </div>
                <div class="sejarah-image">
                    <img src="{{ asset('petailmu/images/IMG_3898.PNG') }}" alt="Peta Ilmu" />
                </div>
            </div>
        </div>
    </section>

    <!-- Visi Misi Section -->
    <section class="visi-misi-section">
        <div class="container">
            <div class="section-header">
                <h2>Visi & Misi</h2>
                <div class="section-divider"></div>
                <p>Landasan dan tujuan kami dalam memberikan pendidikan terbaik</p>
            </div>
            <div class="visi-misi-wrapper">
                <div class="visi-card">
                    <div class="card-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visi</h3>
                    <p>{{ $visi }}</p>
                </div>
                <div class="misi-card">
                    <div class="card-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h3>Misi</h3>
                    <ul>
                        @foreach ($misi as $item_misi)
                            <li>{{ $item_misi }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Nilai-nilai Section -->
    <section class="nilai-section">
        <div class="container">
            <div class="section-header">
                <h2>Nilai-Nilai Kami</h2>
                <div class="section-divider"></div>
                <p>Prinsip-prinsip yang menjadi fondasi dalam setiap aktivitas kami</p>
            </div>
            <div class="nilai-grid">
                @foreach ($nilai_nilai as $nilai)
                    <div class="nilai-item">
                        <div class="nilai-icon">
                            <i class="{{ $nilai->icon }}"></i>
                        </div>
                        <h3>{{ $nilai->nama }}</h3>
                        <p>{{ $nilai->deskripsi }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Struktur Organisasi Section -->
    <section class="struktur-section">
        <div class="container">
            <div class="section-header">
                <h2>Struktur Organisasi</h2>
                <div class="section-divider"></div>
                <p>Tim manajemen bimbingan belajar Peta Ilmu</p>
            </div>
            <div class="struktur-chart">
                @php 
                $levels = [1, 2, 3];
                @endphp
                @foreach ($levels as $level)
                    @php
                    $staff_level = array_filter(is_array($struktur_organisasi) ? $struktur_organisasi : $struktur_organisasi->toArray(), function($staff) use ($level) {
                        $staff = (array)$staff;
                        return $staff['level'] == $level;
                    });
                    @endphp
                    @if (!empty($staff_level))
                        <div class="struktur-level level-{{ $level }}">
                            @foreach ($staff_level as $staff)
                                @php $staff = (array)$staff; @endphp
                                <div class="struktur-card">
                                    <div class="struktur-photo">
                                        <img src="{{ asset('petailmu/' . $staff['foto']) }}" 
                                             alt="{{ $staff['posisi'] }}" />
                                    </div>
                                    <div class="struktur-info">
                                        <h3>{{ $staff['nama'] }}</h3>
                                        <span class="position">{{ $staff['posisi'] }}</span>
                                        @if (!empty($staff['deskripsi']))
                                            <p>{{ $staff['deskripsi'] }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

    <!-- Tim Pengajar Section -->
    <section class="pengajar-section" id="pengajar">
        <div class="container">
            <div class="section-header">
                <h2>Tim Pengajar Profesional</h2>
                <div class="section-divider"></div>
                <p>Tenaga pengajar berpengalaman dan berkualitas yang siap membimbing kesuksesanmu</p>
            </div>

            <!-- Filter Mata Pelajaran -->
            <div class="pengajar-filter">
                @foreach ($mata_pelajaran_filter as $key => $value)
                    <button class="filter-btn {{ ($key == 'all') ? 'active' : '' }}" 
                            data-subject="{{ $key }}">
                        {{ $value }}
                    </button>
                @endforeach
            </div>

            <div class="pengajar-grid">
                @foreach ($tim_pengajar as $pengajar)
                    <div class="pengajar-card" 
                         data-subject="{{ $pengajar->mata_pelajaran_kode }}">
                        <div class="pengajar-photo">
                            <img src="{{ asset('petailmu/' . $pengajar->foto) }}" 
                                 alt="Guru {{ $pengajar->mata_pelajaran }}" />
                        </div>
                        <div class="pengajar-info">
                            <h3>{{ $pengajar->nama }}</h3>
                            <span class="subject">{{ $pengajar->mata_pelajaran }}</span>
                            <p>{{ $pengajar->deskripsi }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pengajar-cta">
                <a href="{{ url('/program') }}" class="btn btn-primary">Lihat Program Pembelajaran</a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content">
                <h2>Bergabunglah dengan Keluarga Besar Peta Ilmu!</h2>
                <p>Dapatkan bimbingan terbaik dari tim pengajar profesional kami untuk meraih prestasi akademik yang gemilang.</p>
                <div class="cta-buttons">
                    <a href="{{ url('/pendaftaran') }}" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="{{ url('/kontak') }}" class="btn btn-secondary">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- JavaScript untuk filter pengajar -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        const pengajarCards = document.querySelectorAll('.pengajar-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                filterBtns.forEach(b => b.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');

                const selectedSubject = this.getAttribute('data-subject');

                pengajarCards.forEach(card => {
                    if (selectedSubject === 'all' || card.getAttribute('data-subject') === selectedSubject) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    });
    </script>
@endsection
