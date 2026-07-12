<x-guest-layout>
    <!-- Title -->
    <div class="mb-6 text-center" style="margin-bottom: 1.5rem; text-align: center;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Buat Password Baru</h3>
        <p style="font-size: 0.875rem; color: #64748b; margin-top: 0.5rem;">
            Masukkan password baru untuk akun Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-medium text-slate-700" />
            <x-text-input id="email" class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Password Baru" class="font-medium text-slate-700" />
            <x-text-input id="password" class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" class="font-medium text-slate-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-slate-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg shadow-sm"
                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password baru" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6" style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
            <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; color: white; font-size: 0.875rem; font-weight: 600; border-radius: 8px; cursor: pointer; box-shadow: 0 4px 12px rgba(30, 60, 114, 0.15);">
                Simpan Password Baru
            </button>
        </div>
    </form>
</x-guest-layout>
