<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Bimbingan Belajar Peta Ilmu')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('petailmu/stylemain.css') }}" />
    @yield('styles')
    <script src="{{ asset('petailmu/universal.js') }}" defer></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <style>
      /* Smooth Page Entrance Animation */
      @keyframes bodyFadeIn {
          from { opacity: 0; }
          to { opacity: 1; }
      }
      
      @keyframes slideInUp {
          from {
              opacity: 0;
              transform: translateY(30px);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      body {
          animation: bodyFadeIn 0.4s ease-out forwards;
      }

      /* Apply staggered sliding text animation inside page headers */
      .hero-section-custom h1,
      .page-header h1,
      .program-hero h1,
      .registration-hero h1 {
          animation: slideInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
          will-change: transform, opacity;
      }

      .hero-section-custom p,
      .page-header p,
      .program-hero p,
      .registration-hero p,
      .page-header-content p {
          animation: slideInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) 0.15s backwards !important;
          will-change: transform, opacity;
      }

      .hero-btn-group,
      .breadcrumb,
      .page-header .breadcrumb,
      .program-hero .breadcrumb,
      .registration-hero .breadcrumb {
          animation: slideInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) 0.3s backwards !important;
          will-change: transform, opacity;
      }

      .hero-section-custom .d-inline-flex {
          animation: slideInUp 0.8s cubic-bezier(0.25, 1, 0.5, 1) forwards !important;
          will-change: transform, opacity;
      }

      /* Unified Header and Hero Styles with Image Gradients */
      .hero-section-custom,
      .page-header,
      .program-hero,
      .registration-hero {
          position: relative;
          padding: 100px 0;
          min-height: 480px;
          display: flex;
          align-items: center;
          color: white !important;
          background-size: cover !important;
          background-position: center right !important;
          background-repeat: no-repeat !important;
          overflow: hidden;
      }

      /* Specific background images with gradient overlays */
      .hero-section-custom {
          background: linear-gradient(90deg, rgba(15, 32, 67, 0.98) 0%, rgba(30, 60, 114, 0.85) 45%, rgba(42, 82, 152, 0.3) 100%), 
                      url('/petailmu/images/indonesian_students_learning.png') !important;
          background-size: cover !important;
          background-position: center right !important;
          min-height: 550px;
      }

      .page-header-profil {
          background: linear-gradient(90deg, rgba(15, 32, 67, 0.98) 0%, rgba(30, 60, 114, 0.85) 45%, rgba(42, 82, 152, 0.3) 100%), 
                      url('/petailmu/images/indonesian_teacher_teaching.png') !important;
          background-size: cover !important;
          background-position: center right !important;
      }

      .program-hero {
          background: linear-gradient(90deg, rgba(15, 32, 67, 0.98) 0%, rgba(30, 60, 114, 0.85) 45%, rgba(42, 82, 152, 0.3) 100%), 
                      url('/petailmu/images/indonesian_program_header.png') !important;
          background-size: cover !important;
          background-position: center right !important;
      }

      .registration-hero {
          background: linear-gradient(90deg, rgba(15, 32, 67, 0.98) 0%, rgba(30, 60, 114, 0.85) 45%, rgba(42, 82, 152, 0.3) 100%), 
                      url('/petailmu/images/indonesian_registration_header.png') !important;
          background-size: cover !important;
          background-position: center right !important;
      }

      .page-header-kontak {
          background: linear-gradient(90deg, rgba(15, 32, 67, 0.98) 0%, rgba(30, 60, 114, 0.85) 45%, rgba(42, 82, 152, 0.3) 100%), 
                      url('/petailmu/images/indonesian_contact_header.png') !important;
          background-size: cover !important;
          background-position: center right !important;
      }

      /* Ensure all page header containers align left consistently */
      .page-header-content,
      .program-hero .container,
      .registration-hero .container {
          text-align: left !important;
          display: flex !important;
          flex-direction: column !important;
          align-items: flex-start !important;
          justify-content: center !important;
          z-index: 2;
          position: relative;
      }

      /* Ensure text on all headers has correct sizing, contrast, and color */
      .hero-section-custom h1,
      .page-header h1,
      .program-hero h1,
      .registration-hero h1 {
          color: white !important;
          font-size: 2.8rem !important;
          font-weight: 800 !important;
          line-height: 1.2;
          margin-bottom: 20px;
          text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
          text-align: left !important;
      }

      .hero-section-custom p,
      .page-header p,
      .program-hero p,
      .registration-hero p,
      .page-header-content p {
          color: rgba(255, 255, 255, 0.9) !important;
          font-size: 1.15rem !important;
          line-height: 1.6;
          max-width: 650px;
          margin: 0 !important;
          margin-bottom: 30px !important;
          text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.3);
          text-align: left !important;
      }

      /* Breadcrumb overrides for page headers to force left-alignment */
      .breadcrumb,
      .page-header .breadcrumb,
      .program-hero .breadcrumb,
      .registration-hero .breadcrumb {
          display: flex !important;
          flex-wrap: wrap !important;
          justify-content: flex-start !important;
          align-items: center !important;
          gap: 10px !important;
          padding: 0 !important;
          margin: 0 !important;
          background: transparent !important;
          list-style: none !important;
      }

      .breadcrumb ol,
      .breadcrumb ul {
          display: flex !important;
          flex-wrap: wrap !important;
          justify-content: flex-start !important;
          align-items: center !important;
          gap: 10px !important;
          padding: 0 !important;
          margin: 0 !important;
          list-style: none !important;
      }

      .breadcrumb li {
          display: inline-flex !important;
          align-items: center !important;
      }
      
      .page-header .breadcrumb a,
      .program-hero .breadcrumb a,
      .registration-hero .breadcrumb a {
          color: rgba(255, 255, 255, 0.8) !important;
          text-decoration: none !important;
      }
      
      .page-header .breadcrumb .current,
      .program-hero .breadcrumb .current,
      .registration-hero .breadcrumb .current,
      .page-header .breadcrumb li.current,
      .breadcrumb li.current {
          color: white !important;
      }

      /* Remove absolute overlay background elements that might block our background image */
      .page-header-bg,
      .page-header::after,
      .program-hero::after,
      .registration-hero::after {
          display: none !important;
      }

      /* ── Navbar CTA pill ─────────────────────────────────── */
      .nav-menu .nav-cta {
          display: inline-flex;
          align-items: center;
          gap: 6px;
          padding: 9px 22px;
          background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
          color: #fff !important;
          border-radius: 30px;
          font-weight: 600;
          font-size: 0.9rem;
          text-decoration: none;
          line-height: 1;
          box-shadow: 0 4px 14px rgba(30, 60, 114, 0.25);
          transition: transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
          white-space: nowrap;
          /* Suppress the underline ::after from .nav-menu a */
          position: relative;
      }
      .nav-menu .nav-cta::after {
          display: none !important;
      }
      .nav-menu .nav-cta:hover {
          background: linear-gradient(135deg, #2a5298 0%, #3b82f6 100%);
          transform: translateY(-2px);
          box-shadow: 0 8px 20px rgba(30, 60, 114, 0.35);
          color: #fff !important;
          text-decoration: none;
      }
    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="header">
      <div class="container">
        <div class="header-wrapper">
          <div class="logo">
            <a href="{{ url('/') }}">
              <img src="{{ asset('petailmu/images/IMG_3898.PNG') }}" alt="Logo Peta Ilmu" />
              <span>Peta Ilmu</span>
            </a>
          </div>
          <nav class="nav-menu">
            <ul style="list-style: none; padding: 0; margin: 0; display: flex; align-items: center;">
              <li style="list-style: none;"><a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Beranda</a></li>
              <li style="list-style: none;"><a href="{{ url('/profil') }}" class="{{ Request::is('profil') ? 'active' : '' }}">Profil</a></li>
              <li style="list-style: none;"><a href="{{ url('/program') }}" class="{{ Request::is('program') ? 'active' : '' }}">Program</a></li>
              <li style="list-style: none;"><a href="{{ url('/kontak') }}" class="{{ Request::is('kontak') ? 'active' : '' }}">Kontak</a></li>
              <li style="list-style: none; margin-left: 10px;">
                <a href="{{ route('login') }}" class="nav-cta">
                  <i class="fas fa-sign-in-alt" style="font-size: 12px;"></i> Masuk / Daftar
                </a>
              </li>
            </ul>
          </nav>
          <div class="mobile-menu">
            <i class="fas fa-bars"></i>
          </div>
        </div>
      </div>
    </header>

    @yield('content')

    <!-- Footer -->
    <footer class="footer">
      <div class="container">
        <div class="footer-content">
          <div class="footer-logo">
            <img src="{{ asset('petailmu/images/IMG_3899.PNG') }}" alt="Logo Peta Ilmu" />
            <h3>Bimbingan Belajar Peta Ilmu</h3>
            <p>Di setiap tempat, di situ ilmu didapat!</p>
          </div>
          <div class="footer-links">
            <h4>Link Cepat</h4>
            <ul>
              <li><a href="{{ url('/') }}">Beranda</a></li>
              <li><a href="{{ url('/profil') }}">Profil</a></li>
              <li><a href="{{ url('/program') }}">Program</a></li>
              <li><a href="{{ url('/pendaftaran') }}">Pendaftaran</a></li>
              <li><a href="{{ url('/kontak') }}">Kontak</a></li>
            </ul>
          </div>
          <div class="footer-program">
            <h4>Program Kami</h4>
            <ul>
              <li><a href="{{ url('/program#sd') }}">Program SD</a></li>
              <li><a href="{{ url('/program#smp') }}">Program SMP</a></li>
              <li><a href="{{ url('/program#sma') }}">Program SMA</a></li>
            </ul>
          </div>
          <div class="footer-contact">
            <h4>Kontak Kami</h4>
            <ul>
              <li>
                <i class="fas fa-map-marker-alt"></i> Perumahan Nuansa Petung Blok C No.9
              </li>
              <li>
                <i class="fas fa-map-marker-alt"></i> Girimukti Rt.10 No.55 Strat 3
              </li>
              <li>
                <i class="fas fa-phone"></i> +62 8981792917
              </li>
              <li>
                <i class="fas fa-phone"></i> +62 82255131993
              </li>
            </ul>
          </div>
        </div>
        <div class="footer-bottom">
          <p>&copy; {{ date('Y') }} Bimbingan Belajar Peta Ilmu. Hak Cipta Dilindungi.</p>
        </div>
      </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    @yield('scripts')
  </body>
</html>
