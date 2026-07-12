<x-guest-layout>
    {{-- Title --}}
    <div class="mb-6 text-center" style="margin-bottom: 1.5rem; text-align: center;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin: 0 0 6px;">Buat Akun Siswa</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin: 0;">
            Isi data di bawah untuk membuat akun baru
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Name --}}
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-medium text-slate-700" />
            <x-text-input id="name"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email Address --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-700" />
            <x-text-input id="email"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="font-medium text-slate-700" />
            <x-text-input id="password"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirm Password --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="font-medium text-slate-700" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- Actions row: back to login + submit --}}
        <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.75rem;">
            <a href="{{ route('login') }}"
                style="font-size: 0.875rem; color: #2563eb; text-decoration: none;"
                onmouseover="this.style.textDecoration='underline'"
                onmouseout="this.style.textDecoration='none'">
                ← Sudah punya akun?
            </a>

            <button type="submit"
                style="padding: 10px 24px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; color: white; font-size: 0.875rem; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.25); transition: opacity 0.15s ease;"
                onmouseover="this.style.opacity='0.88'"
                onmouseout="this.style.opacity='1'">
                Daftar
            </button>
        </div>
    </form>
</x-guest-layout>
