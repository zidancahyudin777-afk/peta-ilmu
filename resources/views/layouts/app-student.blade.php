<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard Siswa - Bimbingan Belajar Peta Ilmu')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    @yield('styles')

    <style>
        /* ── Base ─────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            margin: 0;
            padding: 0;
            animation: bodyFadeIn 0.35s ease-out forwards;
        }

        @keyframes bodyFadeIn {
            from { opacity: 0; }
            to   { opacity: 1; }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Sidebar ──────────────────────────────────────── */
        :root {
            --sidebar-w: 260px;
            --sidebar-bg: #ffffff;
            --sidebar-border: #e2e8f0;
            --sidebar-text: #475569;
            --sidebar-text-hover: #0f172a;
            --sidebar-accent: #2563eb;
            --sidebar-active-bg: #f1f5f9;
        }

        .sidebar {
            position: fixed;
            top: 0; left: 0;
            width: var(--sidebar-w);
            height: 100vh;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--sidebar-border);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: transform 0.3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        /* Mobile: hide sidebar off-screen by default */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
                box-shadow: 6px 0 24px rgba(0,0,0,0.1);
            }
        }

        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            text-align: center;
            flex-shrink: 0;
        }
        .sidebar-brand .brand-icon {
            font-size: 2rem;
            color: var(--sidebar-accent);
            margin-bottom: 6px;
        }
        .sidebar-brand h5 {
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
            margin: 0 0 2px;
        }
        .sidebar-brand p {
            color: #64748b;
            font-size: 11px;
            margin: 0;
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            list-style: none;
            margin: 0;
        }

        .sidebar-nav li { margin-bottom: 4px; }

        .sidebar-nav a,
        .sidebar-nav span.nav-disabled {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            color: var(--sidebar-text);
            transition: background 0.2s ease, color 0.2s ease;
        }

        .sidebar-nav a:hover {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-text-hover);
        }

        .sidebar-nav a.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-accent);
            font-weight: 600;
            box-shadow: inset 3px 0 0 var(--sidebar-accent);
        }

        .sidebar-nav span.nav-disabled {
            color: #94a3b8;
            cursor: not-allowed;
        }

        .sidebar-nav .nav-icon {
            width: 20px;
            text-align: center;
            font-size: 14px;
            flex-shrink: 0;
        }

        .sidebar-footer {
            padding: 14px 12px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 14px;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            background: #dc2626;
            color: #fff;
            border-color: #dc2626;
        }

        /* ── Topbar (mobile only) ─────────────────────────── */
        .topbar {
            display: none;
            position: sticky;
            top: 0;
            z-index: 1030;
            background: #ffffff;
            padding: 12px 16px;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--sidebar-border);
        }

        @media (max-width: 767px) {
            .topbar { display: flex; }
        }

        .topbar .brand-name {
            color: #0f172a;
            font-size: 15px;
            font-weight: 700;
        }

        .topbar-toggle {
            background: transparent;
            border: none;
            color: #475569;
            font-size: 1.3rem;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
            transition: background 0.2s;
        }
        .topbar-toggle:hover { background: #f1f5f9; }

        /* ── Sidebar overlay (mobile) ─────────────────────── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 1039;
        }
        .sidebar-overlay.show { display: block; }

        /* ── Main content ─────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
            padding: 32px 28px;
            animation: slideInUp 0.5s cubic-bezier(0.25, 1, 0.5, 1) forwards;
        }

        @media (max-width: 767px) {
            .main-content {
                margin-left: 0;
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Mobile topbar -->
    <div class="topbar">
        <span class="brand-name"><i class="fas fa-graduation-cap me-2"></i>Peta Ilmu</span>
        <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Overlay (mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon"><i class="fas fa-graduation-cap"></i></div>
            <h5>Dashboard Siswa</h5>
            <p>Bimbingan Belajar Peta Ilmu</p>
        </div>

        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('siswa.dashboard') }}"
                   class="{{ Route::currentRouteName() === 'siswa.dashboard' ? 'active' : '' }}">
                    <i class="fas fa-home nav-icon"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.input') }}"
                   class="{{ Route::currentRouteName() === 'siswa.input' ? 'active' : '' }}">
                    <i class="fas fa-edit nav-icon"></i> Input Data Belajar
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.rekomendasi') }}"
                   class="{{ Route::currentRouteName() === 'siswa.rekomendasi' ? 'active' : '' }}">
                    <i class="fas fa-brain nav-icon"></i> Rekomendasi Belajar
                </a>
            </li>
            <li>
                <a href="{{ route('siswa.riwayat') }}"
                   class="{{ Route::currentRouteName() === 'siswa.riwayat' ? 'active' : '' }}">
                    <i class="fas fa-history nav-icon"></i> Riwayat Belajar
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <a href="{{ route('siswa.logout') }}" class="btn-logout">
                <i class="fas fa-sign-out-alt nav-icon"></i> Logout
            </a>
        </div>
    </aside>

    <!-- Main content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

    <!-- Sidebar mobile toggle -->
    <script>
        const toggle   = document.getElementById('sidebarToggle');
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('sidebarOverlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('show');
        }
        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        toggle.addEventListener('click', () => {
            sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);
    </script>

    @yield('scripts')
</body>
</html>
