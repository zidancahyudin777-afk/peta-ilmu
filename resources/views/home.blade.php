@extends('layouts.public')

@section('title', 'Bimbingan Belajar Peta Ilmu - Beranda')

@section('styles')
    <style>
        /* Educational Theme Design System */
        :root {
            --primary-color: #1e3c72;
            --primary-light: #2a5298;
            --accent-color: #3b82f6;
            --bg-light: #f4f8fc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            color: var(--text-dark);
            background-color: #ffffff;
        }

        /* Hero Section */
        .hero-section-custom {
            padding: 80px 0;
            background: linear-gradient(135deg, #ffffff 0%, var(--bg-light) 100%);
            overflow: hidden;
            position: relative;
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--primary-color);
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .hero-btn-group .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            border: none;
            color: white;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.2);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(30, 60, 114, 0.3);
            color: white;
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }

        .btn-outline-custom:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        .hero-img-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-img-wrapper img {
            max-width: 100%;
            height: auto;
            border-radius: 20px;
            /* Subtle floating animation */
            animation: floatImage 4s ease-in-out infinite;
        }

        @keyframes floatImage {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Features Section */
        .features-section-custom {
            padding: 80px 0;
            background-color: #ffffff;
        }

        .section-header-custom {
            text-align: center;
            margin-bottom: 50px;
        }

        .section-header-custom h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .section-header-custom p {
            color: var(--text-muted);
            max-width: 600px;
            margin: 0 auto;
        }

        .feature-card-custom {
            border: none;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.04);
            padding: 30px;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .feature-card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(30, 60, 114, 0.08);
        }

        .feature-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            background-color: var(--bg-light);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 25px;
            transition: all 0.3s ease;
        }

        .feature-card-custom:hover .feature-icon-wrapper {
            background-color: var(--primary-color);
            color: white;
        }

        .feature-card-custom h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 15px;
        }

        .feature-card-custom p {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* About Section */
        .about-section-custom {
            padding: 80px 0;
            background-color: var(--bg-light);
        }

        .about-img-wrapper img {
            width: 100%;
            height: auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .about-content h3 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .about-content p {
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 25px;
        }

        /* Program Cards */
        .program-card-custom {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.04);
            transition: all 0.3s ease;
            height: 100%;
        }

        .program-card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(30, 60, 114, 0.08);
        }

        .program-card-img-wrapper {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .program-card-img-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .program-card-custom:hover .program-card-img-wrapper img {
            transform: scale(1.05);
        }

        .program-card-body {
            padding: 25px;
            background: white;
        }

        .program-card-body h3 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .program-card-body p {
            font-size: 0.9rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .btn-link-custom {
            color: var(--primary-color);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .btn-link-custom:hover {
            color: var(--accent-color);
            gap: 12px;
        }

        /* Testimonials */
        .testimonial-card-custom {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(30, 60, 114, 0.03);
            padding: 35px;
            border: none;
        }

        .rating-stars {
            color: #fbbf24;
            margin-bottom: 15px;
        }

        /* CTA */
        .cta-section-custom {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: white;
            text-align: center;
        }

        .cta-section-custom h2 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }

        .cta-section-custom p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 700px;
            margin: 0 auto 35px auto;
        }

        /* Marquee Text Badge styling */
        .marquee-badge-container {
            width: 240px;
            overflow: hidden;
            position: relative;
            display: inline-block;
            vertical-align: middle;
        }
        @media (max-width: 576px) {
            .marquee-badge-container {
                width: 150px;
            }
        }
        .marquee-badge-text {
            display: inline-block;
            white-space: nowrap;
            animation: marquee-badge-scroll 10s linear infinite;
        }
        @keyframes marquee-badge-scroll {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        /* Word Rotation transitions — fixed width to prevent layout shift */
        .word-rotate-wrap {
            display: inline-block;
            min-width: 19ch;   /* width of longest phrase: "Impian Akademikmu" */
            vertical-align: middle;
            text-align: left;
        }
        .word-rotate {
            display: inline-block;
            color: #ffc107 !important;
            transition: opacity 0.35s ease, transform 0.35s ease;
            opacity: 1;
            transform: translateY(0);
            will-change: opacity, transform;
        }
        .word-rotate.exiting {
            opacity: 0;
            transform: translateY(-12px);
        }
        .word-rotate.entering {
            opacity: 0;
            transform: translateY(12px);
        }

        /* Responsive Hero & Typography Overrides */
        @media (max-width: 991px) {
            .hero-section-custom {
                padding: 60px 0 80px 0 !important;
                min-height: auto !important;
                background: linear-gradient(180deg, rgba(15, 32, 67, 0.96) 0%, rgba(30, 60, 114, 0.9) 100%), 
                            url('/petailmu/images/indonesian_students_learning.png') !important;
                background-size: cover !important;
                background-position: center center !important;
                text-align: center !important;
            }
            .hero-section-custom .pe-lg-4 {
                padding-right: 0 !important;
            }
            .hero-section-custom .d-inline-flex {
                justify-content: center !important;
            }
            .hero-title {
                font-size: 2.2rem !important;
                text-align: center !important;
            }
            .hero-subtitle {
                font-size: 1rem !important;
                text-align: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
            .hero-btn-group {
                justify-content: center !important;
            }
            .word-rotate-wrap {
                text-align: center !important;
                min-width: auto !important;
                display: inline !important;
            }
            .word-rotate {
                display: inline !important;
            }
        }
        @media (max-width: 576px) {
            .hero-title {
                font-size: 1.8rem !important;
            }
            .hero-subtitle {
                font-size: 0.9rem !important;
            }
            .section-header-custom h2 {
                font-size: 1.8rem !important;
            }
            .about-content h3 {
                font-size: 1.6rem !important;
            }
            .cta-section-custom h2 {
                font-size: 1.6rem !important;
            }
        }
    </style>
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section-custom">
        <div class="container">
            <div class="row">
                <!-- Text Content Column -->
                <div class="col-lg-7">
                    <div class="pe-lg-4">
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill shadow-sm mb-4 border border-white/20" style="background-color: rgba(255, 255, 255, 0.1); max-width: 100%; overflow: hidden;">
                            <span class="badge bg-primary rounded-pill" style="position: relative; z-index: 2;">Baru</span>
                            <div class="marquee-badge-container">
                                <span class="small fw-medium text-white marquee-badge-text">Sistem Rekomendasi Belajar Pintar</span>
                            </div>
                        </div>
                        <h1 class="hero-title text-white">Raih <span class="word-rotate-wrap"><span id="rotate-text" class="word-rotate">Prestasi Terbaikmu</span></span> Bersama Peta Ilmu</h1>
                        <p class="hero-subtitle text-white-50">
                            Bimbingan belajar modern dengan pengajar generasi muda kompeten. Membantu siswa SD, SMP, dan SMA meraih kesuksesan akademik melalui pendekatan pembelajaran yang inovatif dan terukur.
                        </p>
                        <div class="hero-btn-group d-flex flex-wrap gap-3">
                            <a href="{{ url('/pendaftaran') }}" class="btn btn-primary shadow text-white fw-bold px-4 py-2" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); border: none; border-radius: 30px;">Daftar Sekarang</a>
                            <a href="{{ url('/program') }}" class="btn btn-outline-light text-white fw-bold px-4 py-2" style="border: 2px solid white; border-radius: 30px; background: transparent;">Lihat Program</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section (3-4 cards) -->
    <section class="features-section-custom">
        <div class="container">
            <div class="section-header-custom">
                <h2>Mengapa Memilih Kami?</h2>
                <p>Keunggulan bimbingan belajar Peta Ilmu dalam membentuk karakter dan prestasi akademik siswa</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card-custom">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <h3>Pengajar Muda</h3>
                        <p>Dibimbing langsung oleh para pengajar muda berprestasi yang dinamis, komunikatif, dan update dengan perkembangan kurikulum.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card-custom">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Kurikulum Terintegrasi</h3>
                        <p>Materi belajar terstruktur yang dirancang agar sesuai dengan sistem kurikulum nasional, lengkap dengan latihan soal berkala.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card-custom">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3>Rekomendasi Pintar</h3>
                        <p>Analisis Decision Tree dinamis berdasarkan data belajar siswa untuk memberikan saran program pembelajaran yang tepat sasaran.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="feature-card-custom">
                        <div class="feature-icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3>Kelas Interaktif</h3>
                        <p>Membatasi kuota siswa maksimal 5 siswa per kelas reguler untuk menjaga efektivitas bimbingan secara personal.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section-custom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <div class="about-img-wrapper">
                        <!-- About image representing bimbel environment -->
                        <img src="{{ asset('petailmu/images/indonesian_teacher_teaching.png') }}" alt="Ilustrasi Guru Peta Ilmu Mengajar" class="img-fluid rounded-3" />
                    </div>
                </div>
                <div class="col-lg-7 ps-lg-5">
                    <div class="about-content">
                        <h3>Mengenal Bimbingan Belajar Peta Ilmu</h3>
                        <p>
                            Bimbingan Belajar Peta Ilmu adalah institusi bimbingan belajar non-formal yang berdedikasi tinggi untuk melahirkan siswa-siswi berprestasi. Mengusung moto <em>"Di setiap tempat, di situ ilmu didapat"</em>, kami percaya bahwa setiap anak memiliki potensi besar yang dapat dioptimalkan melalui metode bimbingan yang tepat.
                        </p>
                        <p>
                            Kami menggabungkan konsep pengajaran interaktif tatap muka dengan modul evaluasi pintar (sistem pendukung keputusan belajar) untuk memantau kemajuan belajar siswa dari waktu ke waktu secara real-time.
                        </p>
                        <a href="{{ url('/profil') }}" class="btn btn-outline-custom px-4 py-2" style="border-radius: 30px;">Baca Profil Lengkap</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Highlights -->
    <section class="features-section-custom bg-white">
        <div class="container">
            <div class="section-header-custom">
                <h2>Program Pembelajaran Terbaik</h2>
                <p>Pilihan program belajar terstruktur yang disesuaikan dengan jenjang pendidikan putra-putri Anda</p>
            </div>
            <div class="row g-4">
                <!-- SD Program -->
                <div class="col-md-4">
                    <div class="program-card-custom">
                        <div class="program-card-img-wrapper">
                            <img src="{{ asset('petailmu/images/programsd.png') }}" alt="Program SD Peta Ilmu" />
                        </div>
                        <div class="program-card-body">
                            <h3>Program SD (Sekolah Dasar)</h3>
                            <p>Metode bermain sambil belajar yang interaktif untuk memperkuat konsep dasar matematika, IPA, dan bahasa.</p>
                            <a href="{{ url('/program#sd') }}" class="btn-link-custom">Lihat Paket Kelas <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- SMP Program -->
                <div class="col-md-4">
                    <div class="program-card-custom">
                        <div class="program-card-img-wrapper">
                            <img src="{{ asset('petailmu/images/programsmp.png') }}" alt="Program SMP Peta Ilmu" />
                        </div>
                        <div class="program-card-body">
                            <h3>Program SMP (Sekolah Menengah Pertama)</h3>
                            <p>Fokus penguasaan materi Matematika, IPA, dan Bahasa Inggris untuk persiapan ujian sekolah yang matang.</p>
                            <a href="{{ url('/program#smp') }}" class="btn-link-custom">Lihat Paket Kelas <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- SMA Program -->
                <div class="col-md-4">
                    <div class="program-card-custom">
                        <div class="program-card-img-wrapper">
                            <img src="{{ asset('petailmu/images/programsma.png') }}" alt="Program SMA Peta Ilmu" />
                        </div>
                        <div class="program-card-body">
                            <h3>Program SMA (Sekolah Menengah Atas)</h3>
                            <p>Strategi belajar intensif untuk persiapan ujian akhir sekolah dan bimbingan sukses lolos ujian masuk PTN favorit.</p>
                            <a href="{{ url('/program#sma') }}" class="btn-link-custom">Lihat Paket Kelas <i class="fas fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="about-section-custom">
        <div class="container">
            <div class="section-header-custom">
                <h2>Ulasan Siswa & Orang Tua</h2>
                <p>Cerita sukses mereka setelah berproses bersama Bimbingan Belajar Peta Ilmu</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card testimonial-card-custom h-100">
                        <div class="card-body">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-secondary small fst-italic">"Belajar di Peta Ilmu asyik banget. Kakak guru ngajarnya sabar dan seru, materi matematika yang tadinya rumit jadi gampang dimengerti!"</p>
                            <div class="mt-4">
                                <h5 class="mb-0 fw-bold small text-dark">Aisyah Aqila</h5>
                                <span class="text-muted small" style="font-size: 12px;">Siswi SMP Kelas 7</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card-custom h-100">
                        <div class="card-body">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="text-secondary small fst-italic">"Sangat puas dengan sistem laporan perkembangan anak. Ada rekomendasi belajar berbasis kecerdasan buatan sederhana (Decision Tree) yang membantu saya memilih tambahan materi."</p>
                            <div class="mt-4">
                                <h5 class="mb-0 fw-bold small text-dark">Pak Fandi</h5>
                                <span class="text-muted small" style="font-size: 12px;">Orang Tua Siswa (SD)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card testimonial-card-custom h-100">
                        <div class="card-body">
                            <div class="rating-stars">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                            <p class="text-secondary small fst-italic">"Suasana belajarnya santai tapi fokus. Kelasnya ber-AC dan dibatasi hanya 5 orang, jadi konsentrasi belajar lebih terjaga dibandingkan bimbel besar."</p>
                            <div class="mt-4">
                                <h5 class="mb-0 fw-bold small text-dark">Zaqia</h5>
                                <span class="text-muted small" style="font-size: 12px;">Siswi SMA Kelas 11</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section-custom">
        <div class="container">
            <div class="cta-content">
                <h2>Raih Impian Akademikmu Bersama Peta Ilmu</h2>
                <p>Mulailah langkah pertamamu sekarang juga. Daftar secara mudah secara online dan pilih program belajar yang paling tepat untuk perkembangan prestasimu.</p>
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a href="{{ url('/pendaftaran') }}" class="btn btn-light px-4 py-2 fw-bold text-dark" style="border-radius: 30px;">Daftar Sekarang</a>
                    <a href="{{ url('/kontak') }}" class="btn btn-outline-light px-4 py-2 fw-bold" style="border-radius: 30px;">Hubungi Kami</a>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rotateEl = document.getElementById('rotate-text');
            if (!rotateEl) return;

            const words = [
                'Prestasi Terbaikmu',
                'Masa Depan Cerahmu',
                'Nilai Tertinggimu',
                'Impian Akademikmu'
            ];
            let index = 0;
            const INTERVAL   = 3000;  // ms between rotations
            const FADE_DUR   = 350;   // ms — must match CSS transition duration

            function rotateTo(newIndex) {
                // Phase 1: exit
                rotateEl.classList.add('exiting');

                setTimeout(() => {
                    // Phase 2: swap text while invisible
                    rotateEl.textContent = words[newIndex];
                    rotateEl.classList.remove('exiting');
                    rotateEl.classList.add('entering');

                    // Force reflow so the browser registers the class swap
                    void rotateEl.offsetWidth;

                    // Phase 3: enter
                    rotateEl.classList.remove('entering');
                }, FADE_DUR);
            }

            setInterval(() => {
                index = (index + 1) % words.length;
                rotateTo(index);
            }, INTERVAL);
        });
    </script>
@endsection
