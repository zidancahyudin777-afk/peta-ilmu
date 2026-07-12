<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Login - Bimbingan Belajar Peta Ilmu</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-slate-50">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-blue-50 to-indigo-100">
            <div class="mb-6">
                <a href="/" class="flex items-center gap-2 text-decoration-none" style="text-decoration: none;">
                    <span class="text-3xl font-extrabold text-[#1e3c72] flex items-center gap-2" style="font-size: 1.875rem; font-weight: 800; color: #1e3c72; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-graduation-cap text-[#3b82f6]" style="color: #3b82f6;"></i> Peta Ilmu
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-xl border border-slate-100 rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
