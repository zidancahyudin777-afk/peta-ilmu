<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/profil', function () {
    return view('profil');
});

Route::get('/program', function () {
    return view('program');
});

Route::get('/kontak', function () {
    return view('kontak');
});

Route::get('/pendaftaran', function () {
    return view('pendaftaran');
})->name('pendaftaran');

Route::post('/pendaftaran', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'namaLengkap' => 'required|string',
        'tanggalLahir' => 'required|date',
        'jenisKelamin' => 'required|in:laki-laki,perempuan',
        'alamat' => 'required|string',
        'telepon' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'jenjang' => 'required|in:sd,smp,sma',
        'kelas' => 'required|string',
        'sekolah' => 'required|string',
        'package_type' => 'required|string',
        'durasi' => 'required|string',
        'jumlahHari' => 'nullable|integer',
        'namaOrtu' => 'required|string',
        'pekerjaanOrtu' => 'nullable|string',
        'teleponOrtu' => 'required|string',
        'motivasi' => 'nullable|string',
        'referensi' => 'nullable|string',
        'mata_pelajaran' => 'required|string',
    ], [
        'email.unique' => 'Email ini sudah terdaftar. Silakan gunakan email lain atau langsung login.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'password.min' => 'Password minimal 8 karakter.',
    ]);

    $formData = [
        'nama_lengkap' => $request->input('namaLengkap'),
        'tanggal_lahir' => $request->input('tanggalLahir'),
        'jenis_kelamin' => $request->input('jenisKelamin') == 'laki-laki' ? 'L' : 'P',
        'alamat' => $request->input('alamat'),
        'telepon' => $request->input('telepon'),
        'email' => $request->input('email'),
        'jenjang' => $request->input('jenjang'),
        'kelas' => $request->input('kelas'),
        'sekolah' => $request->input('sekolah'),
        'package_type' => $request->input('package_type'),
        'durasi' => $request->input('durasi'),
        'jumlah_hari' => $request->input('jumlahHari'),
        'nama_ortu' => $request->input('namaOrtu'),
        'pekerjaan_ortu' => $request->input('pekerjaanOrtu'),
        'telepon_ortu' => $request->input('teleponOrtu'),
        'motivasi' => $request->input('motivasi'),
        'referensi' => $request->input('referensi'),
    ];

    $subjects = explode(',', $request->input('mata_pelajaran'));
    $subjects = array_filter(array_map('trim', $subjects));

    // Build packageMap dynamically from DB to ensure form keys always match
    $allPackages = DB::table('program_packages')->distinct()->get(['id', 'program_id', 'package_type']);
    $packageMap = [];
    foreach ($allPackages as $pkg) {
        $key = trim(preg_replace('/[^a-z0-9]+/', '_', strtolower($pkg->package_type)), '_');
        $packageMap[$key] = $pkg->package_type;
    }
    $packageTypeDB = $packageMap[$formData['package_type']] ?? $formData['package_type'];

    $package = DB::table('program_packages as pp')
        ->join('programs as p', 'pp.program_id', '=', 'p.id')
        ->where('pp.package_type', $packageTypeDB)
        ->where('p.category', $formData['jenjang'])
        ->select('pp.id')
        ->first();

    if (!$package) {
        return back()->with('error', "Pilihan paket program tidak ditemukan. (Paket: '{$formData['package_type']}' → '{$packageTypeDB}', Jenjang: '{$formData['jenjang']}')")->withInput();
    }

    $package_id = $package->id;

    $prices_db = DB::table('package_prices')
        ->where('package_id', $package_id)
        ->get();

    $durasiKey = match(strtolower($formData['durasi'])) {
        '8x pertemuan', '8x' => '8x',
        '12x pertemuan', '12x' => '12x',
        'harian' => 'harian',
        default => strtolower(str_replace(' ', '', $formData['durasi']))
    };

    $basePricePerSubject = null;
    foreach ($prices_db as $price) {
        $priceKey = match($price->price_label) {
            '8x Pertemuan', '8x' => '8x',
            '12x Pertemuan', '12x' => '12x',
            'Harian', 'harian' => 'harian',
            default => strtolower($price->price_label)
        };
        if ($priceKey === $durasiKey) {
            $basePricePerSubject = (float)$price->price;
            break;
        }
    }

    if ($basePricePerSubject === null) {
        return back()->with('error', "Harga tidak ditemukan untuk durasi: {$formData['durasi']}.")->withInput();
    }

    $subjectCount = count($subjects);
    $jumlahHari = $formData['durasi'] === 'harian' ? (int)($formData['jumlah_hari'] ?? 1) : 1;

    $totalBasePrice = $formData['durasi'] === 'harian'
        ? $basePricePerSubject * $subjectCount * $jumlahHari
        : $basePricePerSubject * $subjectCount;

    $transportCost = 0;
    if ($formData['package_type'] === 'kelas_private_luar_petung_girimukti') {
        if ($formData['durasi'] === 'harian') {
            $transportCost = 6250 * $jumlahHari * $subjectCount;
        } else {
            $sessions = $durasiKey === '8x' ? 8 : 12;
            $transportCost = 6250 * $sessions * $subjectCount;
        }
    }

    $totalPrice = $totalBasePrice + $transportCost;

    try {
        DB::beginTransaction();

        $registrationId = DB::table('pendaftaran')->insertGetId([
            'nama_lengkap' => $formData['nama_lengkap'],
            'tanggal_lahir' => $formData['tanggal_lahir'],
            'jenis_kelamin' => $formData['jenis_kelamin'],
            'alamat' => $formData['alamat'],
            'telepon' => $formData['telepon'],
            'email' => $formData['email'] ?: null,
            'jenjang' => $formData['jenjang'],
            'kelas' => $formData['kelas'],
            'sekolah' => $formData['sekolah'],
            'package_id' => $package_id,
            'package_type' => $packageTypeDB,
            'durasi' => $formData['durasi'],
            'jumlah_hari' => $formData['durasi'] === 'harian' ? $jumlahHari : null,
            'nama_ortu' => $formData['nama_ortu'],
            'pekerjaan_ortu' => $formData['pekerjaan_ortu'] ?: null,
            'telepon_ortu' => $formData['telepon_ortu'],
            'motivasi' => $formData['motivasi'] ?: null,
            'referensi' => $formData['referensi'] ?: null,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'created_at' => now(),
        ]);

        foreach ($subjects as $subject) {
            DB::table('registration_subjects')->insert([
                'registration_id' => $registrationId,
                'subject_name' => trim($subject)
            ]);
        }

        // Create student User account with their chosen password
        $user = \App\Models\User::create([
            'name' => $formData['nama_lengkap'],
            'email' => $formData['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($request->input('password')),
            'jenjang' => $formData['jenjang'],
            'kelas' => $formData['kelas'],
        ]);

        DB::commit();
        return redirect()->route('pendaftaran')->with('success', 'Pendaftaran berhasil! Akun siswa telah dibuat secara otomatis. Silakan login ke dashboard siswa menggunakan email Anda.');
    } catch (\Exception $e) {
        DB::rollBack();
        error_log("Error saving registration in Laravel: " . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran: ' . $e->getMessage())->withInput();
    }
})->name('pendaftaran.store');

// Student Routes
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\LearningDataController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware(['auth'])->group(function () {
    Route::get('/siswa/dashboard', [StudentDashboardController::class, 'index'])->name('siswa.dashboard');
    Route::get('/siswa/input', [LearningDataController::class, 'create'])->name('siswa.input');
    Route::post('/siswa/input', [LearningDataController::class, 'store'])->name('learning.store');
    Route::get('/siswa/rekomendasi', [LearningDataController::class, 'recommendation'])->name('siswa.rekomendasi');
    Route::get('/siswa/rekomendasi/{id}', [LearningDataController::class, 'show'])->name('siswa.rekomendasi.show');
    Route::get('/siswa/riwayat', [StudentDashboardController::class, 'riwayat'])->name('siswa.riwayat');
    Route::get('/siswa/logout', [AuthenticatedSessionController::class, 'destroy'])->name('siswa.logout');
});

// Fallback /dashboard route for Breeze redirection
Route::get('/dashboard', function () {
    return redirect()->route('siswa.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware(['admin.auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/program/add', [AdminDashboardController::class, 'addProgram'])->name('admin.program.add');
    Route::post('/admin/program/update', [AdminDashboardController::class, 'updateProgram'])->name('admin.program.update');
    Route::post('/admin/program/delete', [AdminDashboardController::class, 'deleteProgram'])->name('admin.program.delete');
    Route::post('/admin/pendaftaran/status', [AdminDashboardController::class, 'updateRegistrationStatus'])->name('admin.registration.update_status');
    Route::post('/admin/pendaftaran/delete', [AdminDashboardController::class, 'deleteRegistration'])->name('admin.registration.delete');
    Route::post('/admin/profil/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profil.update');
    Route::post('/admin/api-testing/predict', [AdminDashboardController::class, 'testPredict'])->name('admin.api_testing.predict');
    Route::post('/admin/hasil-belajar/add', [AdminDashboardController::class, 'addHasilBelajar'])->name('admin.hasil_belajar.add');
    Route::post('/admin/hasil-belajar/update', [AdminDashboardController::class, 'updateHasilBelajar'])->name('admin.hasil_belajar.update');
    Route::post('/admin/hasil-belajar/delete', [AdminDashboardController::class, 'deleteHasilBelajar'])->name('admin.hasil_belajar.delete');
});

require __DIR__.'/auth.php';