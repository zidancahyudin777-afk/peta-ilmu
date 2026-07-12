<x-guest-layout>
    {{-- Title --}}
    <div class="mb-6 text-center" style="margin-bottom: 1.5rem; text-align: center;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 6px;">Login Siswa</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
            Gunakan email dan password yang Anda buat saat mendaftar
        </p>
    </div>

    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email Address --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-700" />
            <x-text-input id="email"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="font-medium text-slate-700" />
            <x-text-input id="password"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me --}}
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center" style="cursor: pointer;">
                <input id="remember_me" type="checkbox"
                    class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500"
                    name="remember">
                <span style="margin-left: 0.5rem; font-size: 0.875rem; color: #475569;">Ingat saya</span>
            </label>
        </div>

        {{-- Actions row: forgot password + submit --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    style="font-size: 0.875rem; color: #2563eb; text-decoration: none;"
                    onmouseover="this.style.textDecoration='underline'"
                    onmouseout="this.style.textDecoration='none'">
                    Lupa password?
                </a>
            @endif

            <button type="submit"
                style="padding: 10px 24px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; color: white; font-size: 0.875rem; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.25); transition: opacity 0.15s ease;"
                onmouseover="this.style.opacity='0.88'"
                onmouseout="this.style.opacity='1'">
                Masuk
            </button>
        </div>
    </form>

    {{-- Divider --}}
    <div style="display: flex; align-items: center; margin: 1.5rem 0;">
        <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
        <span style="margin: 0 12px; font-size: 0.8rem; color: #94a3b8;">atau</span>
        <div style="flex: 1; height: 1px; background: #e2e8f0;"></div>
    </div>

    {{-- Register link --}}
    @if (Route::has('register'))
        <div style="text-align: center;">
            <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
                Belum punya akun?
                <a href="{{ route('register') }}"
                    style="color: #1e3c72; font-weight: 600; text-decoration: none;"
                    onmouseover="this.style.textDecoration='underline'"
                    onmouseout="this.style.textDecoration='none'">
                    Daftar sekarang
                </a>
            </p>
        </div>
    @endif
</x-guest-layout>
