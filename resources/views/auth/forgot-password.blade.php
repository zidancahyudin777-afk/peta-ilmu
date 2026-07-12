<x-guest-layout>
    <!-- Title -->
    <div class="mb-6 text-center" style="margin-bottom: 1.5rem; text-align: center;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Lupa Password</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem;">
            Masukkan email terdaftar Anda. Kami akan mengirimkan tautan untuk membuat password baru.
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-6" style="display: flex; align-items: center; justify-content: space-between; margin-top: 1.5rem;">
            <a href="{{ route('login') }}" style="font-size: 0.875rem; color: #2563eb;">
                ← Kembali ke Login
            </a>
            <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; color: white; font-size: 0.875rem; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.15);">
                Kirim Tautan Reset
            </button>
        </div>
    </form>
</x-guest-layout>
