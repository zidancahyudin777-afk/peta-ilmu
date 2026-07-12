@extends('layouts.public')

@section('title', 'Pendaftaran - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <link rel="stylesheet" href="{{ asset('petailmu/styledaftar.css') }}" />
@endsection

@php
use Illuminate\Support\Facades\DB;

$programData = [];
try {
    $categories = ['sd', 'smp', 'sma'];
    foreach ($categories as $category) {
        $programs = DB::table('programs')->where('category', $category)->orderBy('id')->get();
        if ($programs->isNotEmpty()) {
            $program = (array)$programs->first();
            $subjects = is_array($program['subjects']) ? $program['subjects'] : json_decode($program['subjects'] ?? '[]', true);
            $programData[$category] = [
                'name' => $program['title'],
                'description' => $program['description'],
                'subjects' => $subjects ?? [],
                'packages' => []
            ];
            
            $packages = DB::table('program_packages')->where('program_id', $program['id'])->get();
            foreach ($packages as $package) {
                $package = (array)$package;
                $packageKey = preg_replace('/[^a-z0-9]+/', '_', strtolower($package['package_type']));
                $packageKey = trim($packageKey, '_');
                
                $prices_db = DB::table('package_prices')->where('package_id', $package['id'])->get();
                $prices = [];
                foreach ($prices_db as $price) {
                    $price = (array)$price;
                    $priceKey = match($price['price_label']) {
                        '8x Pertemuan', '8x' => '8x',
                        '12x Pertemuan', '12x' => '12x',
                        'Harian', 'harian' => 'harian',
                        default => strtolower($price['price_label'])
                    };
                    $prices[$priceKey] = (float)$price['price'];
                }
                
                $programData[$category]['packages'][$packageKey] = [
                    'name' => $package['package_type'],
                    'description' => $package['description'],
                    'prices' => $prices,
                    'additional' => $package['extra_info'] ?? null
                ];
            }
        }
    }
} catch (\Exception $e) {
    error_log("Database error in pendaftaran.blade.php: " . $e->getMessage());
    $programData = [];
}

if (empty($programData)) {
    $programData = [
        'sd' => [
            'name' => 'Program SD (Sekolah Dasar)',
            'description' => 'Program pembelajaran lengkap untuk siswa SD kelas 1-6 meliputi semua mata pelajaran utama (kecuali agama dan olahraga) dengan metode yang menyenangkan dan interaktif.',
            'subjects' => ['Matematika', 'Bahasa Indonesia', 'IPA', 'IPS', 'Bahasa Inggris', 'Seni Budaya', 'PKn'],
            'packages' => [
                'kelas_reguler' => [
                    'name' => 'Kelas Reguler',
                    'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
                    'prices' => [
                        '8x' => 160000,
                        '12x' => 240000,
                        'harian' => 30000
                    ],
                    'additional' => null
                ],
                'kelas_private_petung_girimukti' => [
                    'name' => 'Kelas Private - Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 200000,
                        '12x' => 300000,
                        'harian' => 35000
                    ],
                    'additional' => null
                ],
                'kelas_private_luar_petung_girimukti' => [
                    'name' => 'Kelas Private - Luar Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 240000,
                        '12x' => 360000,
                        'harian' => 40000
                    ],
                    'additional' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
                ]
            ]
        ],
        'smp' => [
            'name' => 'Program SMP (Sekolah Menengah Pertama)',
            'description' => 'Program yang dirancang khusus untuk siswa SMP dengan fokus pada tiga mata pelajaran inti: Matematika, IPA, dan Bahasa Inggris untuk membangun fondasi akademik yang kuat.',
            'subjects' => ['Matematika', 'IPA (Fisika & Biologi)', 'Bahasa Inggris'],
            'packages' => [
                'kelas_reguler' => [
                    'name' => 'Kelas Reguler',
                    'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
                    'prices' => [
                        '8x' => 200000,
                        '12x' => 300000,
                        'harian' => 35000
                    ],
                    'additional' => null
                ],
                'kelas_private_petung_girimukti' => [
                    'name' => 'Kelas Private - Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 240000,
                        '12x' => 360000,
                        'harian' => 40000
                    ],
                    'additional' => null
                ],
                'kelas_private_luar_petung_girimukti' => [
                    'name' => 'Kelas Private - Luar Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 280000,
                        '12x' => 420000,
                        'harian' => 45000
                    ],
                    'additional' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
                ]
            ]
        ],
        'sma' => [
            'name' => 'Program SMA (Sekolah Menengah Atas)',
            'description' => 'Program intensif untuk siswa SMA dengan fokus pada mata pelajaran sains. Persiapan optimal untuk masuk perguruan tinggi jurusan sains dan teknik.',
            'subjects' => ['Matematika', 'Fisika', 'Kimia', 'Biologi'],
            'packages' => [
                'kelas_reguler' => [
                    'name' => 'Kelas Reguler',
                    'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
                    'prices' => [
                        '8x' => 240000,
                        '12x' => 360000,
                        'harian' => 40000
                    ],
                    'additional' => null
                ],
                'kelas_private_petung_girimukti' => [
                    'name' => 'Kelas Private - Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 320000,
                        '12x' => 480000,
                        'harian' => 45000
                    ],
                    'additional' => null
                ],
                'kelas_private_luar_petung_girimukti' => [
                    'name' => 'Kelas Private - Luar Petung/Girimukti',
                    'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
                    'prices' => [
                        '8x' => 360000,
                        '12x' => 540000,
                        'harian' => 50000
                    ],
                    'additional' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
                ]
            ]
        ]
    ];
}
@endphp

@section('content')
    <!-- Hero Section -->
    <section class="registration-hero">
        <div class="container">
            <div class="page-header-content">
                <h1>Daftar Sekarang</h1>
                <p style="margin: 0 !important; margin-bottom: 30px !important; text-align: left !important;">Bergabunglah dengan siswa yang telah merasakan bimbingan belajar di Peta Ilmu. Wujudkan prestasi terbaikmu bersama kami!</p>
                <nav class="breadcrumb">
                    <a href="{{ url('/') }}">Beranda</a>
                    <span class="separator">/</span>
                    <span class="current">Pendaftaran</span>
                </nav>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section class="registration-section">
        <div class="registration-container">
            <!-- Registration Information -->
            <div class="registration-info">
                <h2>Informasi Pendaftaran</h2>
                <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="info-content">
                        <h3>Periode Pendaftaran</h3>
                        <p>Pendaftaran dibuka sepanjang tahun. Kelas dimulai setiap awal bulan.</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-file-alt"></i>
                    <div class="info-content">
                        <h3>Dokumen yang Diperlukan</h3>
                        <p>Fotokopi kartu identitas, pas foto 3x4 (2 lembar), dan fotokopi raport terakhir</p>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-content">
                        <h3>Jadwal Belajar</h3>
                        <p>Senin-Sabtu: 17:00-21:00, Minggu: Libur</p>
                    </div>
                </div>
                <div class="program-options">
                    <h3>Program Yang Tersedia</h3>
                    <div class="program-grid">
                        <div class="program-option">
                            <h4>Program SD</h4>
                            <p>Kelas 1-6</p>
                        </div>
                        <div class="program-option">
                            <h4>Program SMP</h4>
                            <p>Kelas 7-9</p>
                        </div>
                        <div class="program-option">
                            <h4>Program SMA</h4>
                            <p>Kelas 10-12</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <div class="registration-form">
                <div class="form-title">
                    <h2>Formulir Pendaftaran</h2>
                    <p>Lengkapi data berikut untuk mendaftar di Bimbingan Belajar Peta Ilmu</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success" style="margin-bottom: 20px; border-radius: 8px; font-weight: 500; padding: 15px;">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" style="margin-bottom: 20px; border-radius: 8px; font-weight: 500; padding: 15px;">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    </div>
                @endif

                <form id="registrationForm" action="{{ route('pendaftaran.store') }}" method="POST">
                    @csrf
                    <!-- Data Siswa -->
                    <h3 style="color: #667eea; margin-bottom: 20px; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                        <i class="fas fa-user"></i> Data Siswa
                    </h3>
                    <div class="form-group">
                        <label for="namaLengkap">Nama Lengkap <span class="required">*</span></label>
                        <input type="text" id="namaLengkap" name="namaLengkap" class="form-control" value="" required />
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tanggalLahir">Tanggal Lahir <span class="required">*</span></label>
                            <input type="date" id="tanggalLahir" name="tanggalLahir" class="form-control" value="" required />
                        </div>
                        <div class="form-group">
                            <label for="jenisKelamin">Jenis Kelamin <span class="required">*</span></label>
                            <select id="jenisKelamin" name="jenisKelamin" class="form-control" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki-laki">Laki-laki</option>
                                <option value="perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat Lengkap <span class="required">*</span></label>
                        <textarea id="alamat" name="alamat" class="form-control" placeholder="Masukkan alamat lengkap" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="telepon">Nomor Telepon/HP <span class="required">*</span></label>
                            <input type="tel" id="telepon" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" value="" required />
                        </div>
                        <div class="form-group">
                            <label for="email">Email <span class="required">*</span></label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="example@email.com" value="" required />
                            <small style="color: #6b7280; font-size: 0.8rem; margin-top: 4px; display: block;">Email ini digunakan untuk Login &amp; pemulihan password</small>
                        </div>
                    </div>

                    <!-- Password Akun -->
                    <h3 style="color: #667eea; margin: 30px 0 20px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                        <i class="fas fa-lock"></i> Buat Password Akun Siswa
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password <span class="required">*</span></label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Min. 8 karakter" required minlength="8" />
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required minlength="8" />
                        </div>
                    </div>

                    <!-- Data Akademik -->
                    <h3 style="color: #667eea; margin: 30px 0 20px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                        <i class="fas fa-graduation-cap"></i> Data Akademik
                    </h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenjang">Jenjang Pendidikan <span class="required">*</span></label>
                            <select id="jenjang" name="jenjang" class="form-control" required>
                                <option value="">Pilih Jenjang</option>
                                <option value="sd">SD/MI</option>
                                <option value="smp">SMP/MTs</option>
                                <option value="sma">SMA/MA/SMK</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelas">Kelas <span class="required">*</span></label>
                            <select id="kelas" name="kelas" class="form-control" required>
                                <option value="">Pilih Kelas</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sekolah">Asal Sekolah <span class="required">*</span></label>
                        <input type="text" id="sekolah" name="sekolah" class="form-control" placeholder="Nama sekolah lengkap" value="" required />
                    </div>

                    <!-- Program Selection -->
                    <div class="program-selection" id="programSelection">
                        <h3 style="color: #667eea; margin-bottom: 15px">
                            <i class="fas fa-book"></i> Pilihan Program & Paket
                        </h3>
                        <div class="form-group">
                            <label for="package_type">Pilih Paket Program <span class="required">*</span></label>
                            <select id="package_type" name="package_type" class="form-control" required>
                                <option value="">Pilih jenjang pendidikan terlebih dahulu</option>
                            </select>
                            <input type="hidden" name="mata_pelajaran" id="mataPelajaranHidden" value="">
                            <input type="hidden" name="total_price" id="totalPriceHidden" value="">
                        </div>
                        <div class="form-group" id="durasiGroup" style="display: none">
                            <label for="durasi">Pilih Durasi <span class="required">*</span></label>
                            <select id="durasi" name="durasi" class="form-control" required>
                                <option value="">Pilih Durasi</option>
                            </select>
                        </div>
                        <div class="program-details" id="programDetails">
                            <div id="programInfo"></div>
                        </div>
                        <div class="price-display" id="priceDisplay">
                            <div id="priceInfo"></div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <h3 style="color: #667eea; margin: 30px 0 20px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                        <i class="fas fa-users"></i> Data Orang Tua/Wali
                    </h3>
                    <div class="form-group">
                        <label for="namaOrtu">Nama Orang Tua/Wali <span class="required">*</span></label>
                        <input type="text" id="namaOrtu" name="namaOrtu" class="form-control" value="" required />
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="pekerjaanOrtu">Pekerjaan</label>
                            <input type="text" id="pekerjaanOrtu" name="pekerjaanOrtu" class="form-control" value="" />
                        </div>
                        <div class="form-group">
                            <label for="teleponOrtu">Nomor Telepon <span class="required">*</span></label>
                            <input type="tel" id="teleponOrtu" name="teleponOrtu" class="form-control" value="" required />
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <h3 style="color: #667eea; margin: 30px 0 20px 0; border-bottom: 2px solid #667eea; padding-bottom: 10px;">
                        <i class="fas fa-info-circle"></i> Informasi Tambahan
                    </h3>
                    <div class="form-group">
                        <label for="motivasi">Motivasi/Tujuan Mengikuti Bimbel</label>
                        <textarea id="motivasi" name="motivasi" class="form-control" placeholder="Ceritakan motivasi Anda mengikuti bimbingan belajar di Peta Ilmu"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="referensi">Mengetahui Peta Ilmu dari mana?</label>
                        <select id="referensi" name="referensi" class="form-control">
                            <option value="">Pilih Sumber Info</option>
                            <option value="teman">Teman/Saudara</option>
                            <option value="internet">Internet/Website</option>
                            <option value="sosmed">Media Sosial</option>
                            <option value="brosur">Brosur/Pamflet</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <!-- Persetujuan -->
                    <div class="checkbox-group">
                        <input type="checkbox" id="persetujuan" name="persetujuan" required />
                        <label for="persetujuan">
                            Saya menyetujui <a href="#" style="color: #667eea">syarat dan ketentuan</a> yang berlaku di Bimbingan Belajar Peta Ilmu dan bersedia mengikuti aturan yang telah ditetapkan. <span class="required">*</span>
                        </label>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-paper-plane"></i> Kirim Pendaftaran
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- Process Steps Section -->
    <section class="process-section">
        <div class="container">
            <div class="section-header">
                <h2>Cara Mendaftar</h2>
                <p>Ikuti langkah-langkah mudah berikut untuk menjadi bagian dari keluarga besar Peta Ilmu</p>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3>Isi Formulir</h3>
                    <p>Lengkapi formulir pendaftaran online dengan data yang akurat dan benar</p>
                </div>
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3>Konfirmasi</h3>
                    <p>Tim kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi data</p>
                </div>
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3>Mulai Belajar</h3>
                    <p>Selamat! Anda sudah terdaftar dan siap memulai perjalanan belajar di Peta Ilmu</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Pertanyaan yang Sering Diajukan</h2>
                <p>Temukan jawaban atas pertanyaan umum seputar pendaftaran di Peta Ilmu</p>
            </div>
            <div class="faq-container">
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Apakah ada tes masuk untuk mendaftar di Peta Ilmu?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Tidak ada tes masuk khusus. Kami melakukan placement test sederhana untuk menentukan kelas yang sesuai dengan kemampuan siswa agar pembelajaran lebih efektif.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Berapa lama masa belajar di Peta Ilmu?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Masa belajar fleksibel tergantung program yang dipilih.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Apakah bisa mendaftar di tengah semester?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Ya, pendaftaran terbuka sepanjang tahun. Untuk siswa yang masuk di tengah semester akan mendapat materi penyesuaian agar dapat mengikuti pembelajaran dengan baik.
                        </div>
                    </div>
                </div>
                <div class="faq-item">
                    <button class="faq-question">
                        <span>Bagaimana jika ingin pindah kelas atau program?</span>
                        <i class="fas fa-chevron-down faq-icon"></i>
                    </button>
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            Perpindahan kelas atau program dapat dilakukan dengan berkonsultasi terlebih dahulu dengan koordinator akademik untuk menentukan kelas yang paling sesuai.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Inject program data directly into JavaScript
        window.programData = @json($programData);
        
        // FAQ Accordion
        document.querySelectorAll(".faq-question").forEach((question) => {
            question.addEventListener("click", () => {
                const faqItem = question.parentElement;
                const isActive = faqItem.classList.contains("active");

                // Close all FAQ items
                document.querySelectorAll(".faq-item").forEach((item) => {
                    item.classList.remove("active");
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add("active");
                }
            });
        });
    </script>
@endsection
