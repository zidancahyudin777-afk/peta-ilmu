@extends('layouts.public')

@section('title', 'Kontak Kami - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <link rel="stylesheet" href="{{ asset('petailmu/stylekontak.css') }}" />
@endsection

@php
$contact_info = [
    [
        'icon' => 'fas fa-map-marker-alt',
        'title' => 'Alamat Kami',
        'content' => 'Perum Nuansa Blok.C No.9<br>Kelurahan Petung<br>Penajam Paser Utara 76143<br>Kalimantan Timur'
    ],
    [
        'icon' => 'fas fa-map-marker-alt',
        'title' => 'Alamat Kami',
        'content' => 'Girimukti Strat 3<br>Desa Girimukti<br>Penajam Paser Utara 76143<br>Kalimantan Timur'
    ],
    [
        'icon' => 'fas fa-phone',
        'title' => 'Telepon',
        'content' => '<strong>Contact Person 1 :</strong> <br/>+62 898-1792-917'
    ],
    [
        'icon' => 'fas fa-phone',
        'title' => 'Telepon',
        'content' => '<strong>Contact Person 2 :</strong> <br/>+62 822-5513-1993'
    ]
];

$faq_data = [
    [
        'question' => 'Bagaimana cara mendaftar di Peta Ilmu?',
        'answer' => 'Anda dapat mendaftar secara online melalui halaman pendaftaran di website kami, datang langsung ke kantor kami, atau menghubungi kami via telepon/WhatsApp untuk informasi lebih lanjut.'
    ],
    [
        'question' => 'Berapa jumlah siswa per kelas?',
        'answer' => 'Kami membatasi jumlah siswa maksimal 5 orang per kelas untuk memastikan setiap siswa mendapat perhatian yang optimal dari pengajar.'
    ],
    [
        'question' => 'Apakah tersedia konsultasi dengan orang tua?',
        'answer' => 'Ya, kami mengadakan sesi konsultasi rutin dengan orang tua setiap bulan untuk membahas perkembangan belajar anak. Konsultasi tambahan dapat dijadwalkan sesuai kebutuhan.'
    ]
];
@endphp

@section('content')
    <!-- Page Header -->
    <section class="page-header page-header-kontak">
        <div class="container">
            <div class="page-header-content">
                <h1>Kontak Kami</h1>
                <p style="margin: 0 !important; margin-bottom: 30px !important; text-align: left !important; white-space: normal !important; padding: 0 !important; display: block !important;">Hubungi kami untuk informasi lebih lanjut tentang program bimbingan belajar</p>
                <nav class="breadcrumb">
                    <ol>
                        <li><a href="{{ url('/') }}">Beranda</a></li>
                        <li class="current">Kontak</li>
                    </ol>
                </nav>
            </div>
        </div>
    </section>

    <!-- Kontak Info Section -->
    <section class="contact-info-section">
        <div class="container">
            <div class="section-header">
                <h2>Informasi Kontak</h2>
                <p>Berbagai cara untuk menghubungi Bimbingan Belajar Peta Ilmu</p>
            </div>
            <div class="contact-info-grid">
                @foreach ($contact_info as $info)
                <div class="contact-info-card">
                    <div class="contact-icon">
                        <i class="{{ $info['icon'] }}"></i>
                    </div>
                    <h3>{{ $info['title'] }}</h3>
                    <p>{!! $info['content'] !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <div class="container">
            <div class="section-header">
                <h2>Lokasi Kami</h2>
                <p>Temukan lokasi Bimbingan Belajar Peta Ilmu dengan mudah</p>
            </div>
        </div>
        <div class="map-container">
            <div class="map-wrapper">
                <!-- Google Maps Embed -->
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3988.716760568575!2d116.65701907485216!3d-1.3464030818094193!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zMcKwMjAnNDcuMSJTIDExNsKwMzknMzAuMyJF!5e0!3m2!1sid!2sid!4v1748070033497!5m2!1sid!2sid"
                    width="100%"
                    height="450"
                    style="border: 0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                >
                </iframe>
                <div class="map-overlay">
                    <div class="map-info">
                        <h3>Bimbingan Belajar Peta Ilmu</h3>
                        <p>
                            <i class="fas fa-map-marker-alt"></i> Perumahan Nuansa Petung
                            Blok C No.9
                        </p>
                        <a
                            href="https://maps.app.goo.gl/1g7c2cgPvYi5LDF99"
                            target="_blank"
                            class="btn btn-primary"
                        >
                            <i class="fas fa-directions"></i> Petunjuk Arah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <div class="section-header">
                <h2>Pertanyaan yang Sering Diajukan</h2>
                <p>Jawaban untuk pertanyaan umum seputar layanan kami</p>
            </div>
            <div class="faq-container">
                @foreach ($faq_data as $index => $faq)
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>{{ $faq['question'] }}</h3>
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        <p>{{ $faq['answer'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
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
