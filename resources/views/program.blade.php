@extends('layouts.public')

@section('title', 'Program Pembelajaran - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <link rel="stylesheet" href="{{ asset('petailmu/styleprogram.css') }}" />
@endsection

@php
use Illuminate\Support\Facades\DB;

try {
    $programs_db = DB::table('programs')->orderBy('id')->get();
    $programs = [];
    foreach ($programs_db as $p) {
        $p_arr = (array)$p;
        $p_arr['subjects'] = json_decode($p_arr['subjects'] ?? '[]', true) ?? [];
        
        $p_arr['features'] = DB::table('program_features')
            ->where('program_id', $p->id)
            ->pluck('feature_text')
            ->toArray();
            
        $packages = DB::table('program_packages')
            ->where('program_id', $p->id)
            ->get();
            
        $p_arr['packages'] = [];
        foreach ($packages as $pkg) {
            $pkg_arr = (array)$pkg;
            
            $pkg_arr['prices'] = DB::table('package_prices')
                ->where('package_id', $pkg->id)
                ->get();
                
            $p_arr['packages'][] = $pkg_arr;
        }
        $programs[] = $p_arr;
    }
    
    $benefits = DB::table('program_benefits')->orderBy('id')->get();
    $faqs = DB::table('program_faqs')->orderBy('id')->get();
} catch (\Exception $e) {
    error_log("Database error in program.blade.php: " . $e->getMessage());
    
    // Fallback data if DB tables not migrated yet
    $programs = [
        [
            'id' => 1,
            'category' => 'sd',
            'icon' => 'fas fa-graduation-cap',
            'title' => 'Program SD',
            'description' => 'Program pembelajaran lengkap untuk siswa SD kelas 1-6.',
            'duration' => '1-2 jam per sesi',
            'frequency' => '2-3 kali per minggu',
            'subjects' => ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA'],
            'features' => ['Matematika - konsep dasar hingga operasi hitung lanjutan', 'Bahasa Indonesia - membaca, menulis, berbicara'],
            'packages' => [
                [
                    'package_type' => 'Kelas Reguler',
                    'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
                    'prices' => [
                        (object)['price_label' => '8x', 'price' => 160000],
                        (object)['price_label' => '12x', 'price' => 240000]
                    ],
                    'info' => '',
                    'extra_info' => ''
                ]
            ]
        ]
    ];
    $benefits = [];
    $faqs = [];
}
@endphp

@section('content')
    <section class="program-hero">
        <div class="container">
            <div class="page-header-content">
                <h1>Program Pembelajaran</h1>
                <p style="margin: 0 !important; margin-bottom: 30px !important; text-align: left !important; white-space: normal !important; padding: 0 !important; display: block !important;">Pilih program pembelajaran yang sesuai dengan kebutuhan dan jenjang pendidikan Anda. Kami menyediakan berbagai program berkualitas tinggi dengan metode pembelajaran yang efektif.</p>
                <nav class="breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span class="separator">/</span>
                    <span class="current">Program</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Program Filter & Grid -->
    <section class="program-filter">
        <div class="container">
            <div class="filter-buttons">
                <a href="#" class="filter-btn active" data-filter="all">Semua Program</a>
                <a href="#sd" class="filter-btn" data-filter="sd">SD</a>
                <a href="#smp" class="filter-btn" data-filter="smp">SMP</a>
                <a href="#sma" class="filter-btn" data-filter="sma">SMA</a>
            </div>

            <div class="program-grid">
                @if (empty($programs))
                    <div class="no-programs">
                        <p>Program sedang tidak tersedia. Silakan hubungi kami untuk informasi lebih lanjut.</p>
                    </div>
                @else
                    @foreach ($programs as $program)
                        <div class="program-card" data-category="{{ $program['category'] }}" id="{{ $program['category'] }}">
                            <div class="program-card-header">
                                <div class="icon">
                                    <i class="{{ $program['icon'] }}"></i>
                                </div>
                            </div>
                            <div class="program-card-content">
                                <h3>{{ $program['title'] }}</h3>
                                <p class="program-description">
                                    {{ $program['description'] }}
                                </p>

                                @if (!empty($program['features']))
                                    <ul class="program-features">
                                        @foreach ($program['features'] as $feature)
                                            <li>{{ $feature }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div class="program-details">
                                    <div class="detail-row">
                                        <span class="detail-label">Durasi</span>
                                        <span class="detail-value">{{ $program['duration'] }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Frekuensi</span>
                                        <span class="detail-value">{{ $program['frequency'] }}</span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="detail-label">Paket Kelas</span>
                                        <span class="detail-value">Private & Reguler</span>
                                    </div>
                                </div>

                                @if (!empty($program['packages']))
                                    <div class="program-packages">
                                        @foreach ($program['packages'] as $package)
                                            <div class="package-item">
                                                <h4>
                                                    <i class="fas fa-box"></i> 
                                                    {{ $package['package_type'] }}
                                                </h4>
                                                <p>{{ $package['description'] }}</p>
                                                
                                                @if (!empty($package['prices']))
                                                    <div class="price-options">
                                                        @foreach ($package['prices'] as $price)
                                                            <div class="price-option">
                                                                <span class="option-label">{{ $price->price_label }}</span>
                                                                <span class="package-price">Rp {{ number_format($price->price, 0, ',', '.') }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                
                                                @if (!empty($package['info']))
                                                    <p class="additional-info">{{ $package['info'] }}</p>
                                                @endif
                                                
                                                @if (!empty($package['extra_info']))
                                                    <p class="additional-info">{{ $package['extra_info'] }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="program-cta">
                                    <a href="{{ url('/pendaftaran') }}" class="btn-program btn-primary">Daftar Sekarang</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Program Benefits -->
    @if (!empty($benefits))
        <section class="program-benefits">
            <div class="container">
                <div class="section-header">
                    <h2>Keunggulan Program Kami</h2>
                    <p>Mengapa siswa dan orang tua memilih program pembelajaran di Peta Ilmu</p>
                </div>
                <div class="benefits-grid">
                    @foreach ($benefits as $benefit)
                        <div class="benefit-item">
                            <div class="benefit-icon">
                                <i class="{{ $benefit->icon }}"></i>
                            </div>
                            <h3>{{ $benefit->title }}</h3>
                            <p>{{ $benefit->description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FAQ Section -->
    @if (!empty($faqs))
        <section class="faq-section">
            <div class="container">
                <div class="section-header">
                    <h2>Pertanyaan yang Sering Diajukan</h2>
                    <p>Temukan jawaban atas pertanyaan umum tentang program pembelajaran kami</p>
                </div>
                <div class="faq-container">
                    @foreach ($faqs as $faq)
                        <div class="faq-item">
                            <button class="faq-question">
                                <span>{{ $faq->question }}</span>
                                <i class="fas fa-chevron-down faq-icon"></i>
                            </button>
                            <div class="faq-answer">
                                <div class="faq-answer-content">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

@section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple client side filter for programs
        const filterBtns = document.querySelectorAll('.filter-btn');
        const programCards = document.querySelectorAll('.program-card');

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                // If it is a hash link, handle appropriately
                const filter = this.getAttribute('data-filter');
                if (filter) {
                    e.preventDefault();
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    programCards.forEach(card => {
                        if (filter === 'all' || card.getAttribute('data-category') === filter) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                }
            });
        });

        // Trigger filter if page loaded with hash
        const hash = window.location.hash.replace('#', '');
        if (hash) {
            const activeBtn = document.querySelector(`.filter-btn[data-filter="${hash}"]`);
            if (activeBtn) {
                activeBtn.click();
            }
        }
    });
    </script>
@endsection
