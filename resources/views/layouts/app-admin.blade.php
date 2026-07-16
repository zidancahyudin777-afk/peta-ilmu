<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Admin Dashboard - Bimbingan Belajar Peta Ilmu')</title>
    <link rel="stylesheet" href="{{ asset('petailmu/admin.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    @yield('styles')
    <style>
        @keyframes bodyFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes dashboardFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        body {
            animation: bodyFadeIn 0.3s ease-out forwards;
        }
        .dashboard-container {
            animation: dashboardFadeIn 0.6s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            will-change: transform, opacity;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-header" style="text-align: center; padding: 25px 15px; border-bottom: 1px solid rgba(255,255,255,0.08);">
                <div style="background: #ffffff; width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                    <img src="{{ asset('petailmu/images/IMG_3898.PNG') }}" alt="Logo Peta Ilmu" style="height: 42px; width: auto;" />
                </div>
                <h2 style="font-size: 1.15rem; font-weight: 700; color: #ffffff; letter-spacing: -0.5px; background: none; -webkit-text-fill-color: initial;">Admin Dashboard</h2>
                <p class="sidebar-subtitle" style="color: rgba(255,255,255,0.6); font-size: 0.7rem; margin-top: 3px; font-weight: 600;">Bimbel Peta Ilmu</p>
            </div>
            <nav>
                <ul>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'dashboard']) }}" class="{{ $section == 'dashboard' ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'profil']) }}" class="{{ $section == 'profil' ? 'active' : '' }}"><i class="fas fa-users"></i> Kelola Profil</a></li>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'program']) }}" class="{{ $section == 'program' ? 'active' : '' }}"><i class="fas fa-book"></i> Kelola Program</a></li>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'registration']) }}" class="{{ $section == 'registration' ? 'active' : '' }}"><i class="fas fa-users"></i> Kelola Pendaftaran</a></li>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'hasil_belajar']) }}" class="{{ $section == 'hasil_belajar' ? 'active' : '' }}"><i class="fas fa-graduation-cap"></i> Kelola Hasil Belajar</a></li>
                    <li><a href="{{ route('admin.dashboard', ['section' => 'ml']) }}" class="{{ $section == 'ml' ? 'active' : '' }}"><i class="fas fa-brain"></i> Machine Learning</a></li>
                    <li><a href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header class="main-header">
                <button class="hamburger" aria-label="Toggle Menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <div class="header-content">
                    <h2>Selamat datang, {{ session('admin_username') }}!</h2>
                    <p class="welcome-text">Kelola profil, program dan pendaftaran dengan mudah</p>
                </div>
            </header>
            <div class="content-body">
                @if (session('success'))
                    <div class="message success fade-in">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="message error fade-in">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            const sidebar = document.querySelector('.sidebar');
            const body = document.body;

            hamburger.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-open');
                body.classList.toggle('sidebar-active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && sidebar.classList.contains('sidebar-open')) {
                    if (!sidebar.contains(event.target) && !hamburger.contains(event.target)) {
                        sidebar.classList.remove('sidebar-open');
                        body.classList.remove('sidebar-active');
                    }
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>
