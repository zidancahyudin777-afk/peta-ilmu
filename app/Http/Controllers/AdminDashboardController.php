<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\ProgramFeature;
use App\Models\ProgramPackage;
use App\Models\PackagePrice;
use App\Models\Registration;
use App\Models\RegistrationSubject;
use App\Models\OrganisasiInfo;
use App\Models\SejarahOrganisasi;
use App\Models\MisiOrganisasi;
use App\Models\NilaiOrganisasi;
use App\Models\StrukturOrganisasi;
use App\Models\TimPengajar;
use App\Models\MataPelajaran;
use App\Models\KontakInfo;
use App\Models\LearningData;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $section = $request->query('section', 'dashboard');
        $action = $request->query('action', '');
        
        $pendaftaran = Registration::with('subjects')->orderBy('created_at', 'desc')->get();
        $programs = Program::with(['features', 'packages.prices'])->orderBy('id')->get();

        // Retrieve dynamic data structure for pricing calculation
        $programData = [];
        foreach ($programs as $prog) {
            $jenjang = strtolower($prog->category);
            $subjects = is_array($prog->subjects) ? $prog->subjects : json_decode($prog->subjects, true);
            $programData[$jenjang] = [
                'name' => $prog->title,
                'description' => $prog->description,
                'subjects' => $subjects ?? [],
                'packages' => []
            ];
            foreach ($prog->packages as $package) {
                $packageKey = strtolower(str_replace([' ', '-', '/'], '_', $package->package_type));
                $programData[$jenjang]['packages'][$packageKey] = [
                    'name' => $package->package_type,
                    'description' => $package->description,
                    'prices' => [],
                    'additional' => $package->extra_info ?? ''
                ];
                foreach ($package->prices as $price) {
                    $label = match (strtolower($price->price_label)) {
                        '8x pertemuan', '8x' => '8x',
                        '12x pertemuan', '12x' => '12x',
                        'harian' => 'harian',
                        default => strtolower(str_replace([':', ' ', 'Pertemuan'], '', $price->price_label))
                    };
                    $programData[$jenjang]['packages'][$packageKey]['prices'][$label] = $price->price;
                }
            }
        }

        // Profile data
        $organisasi_info = OrganisasiInfo::first();
        $misi_list = MisiOrganisasi::orderBy('urutan')->pluck('misi_text')->toArray();
        $sejarah_paragraf = SejarahOrganisasi::orderBy('urutan')->pluck('paragraf')->toArray();
        $nilai_nilai = NilaiOrganisasi::orderBy('id')->get();
        $struktur_organisasi = StrukturOrganisasi::orderBy('level')->orderBy('id')->get();
        $tim_pengajar = TimPengajar::with('mataPelajaran')->where('status', 'aktif')->orderBy('nama')->get();
        
        $mapel_list = MataPelajaran::orderBy('nama')->get();
        $mata_pelajaran_filter = [];
        foreach ($mapel_list as $mp) {
            $mata_pelajaran_filter[$mp->kode] = $mp->nama;
        }

        $contacts = KontakInfo::where('status', 'aktif')->orderBy('jenis')->orderBy('urutan')->get();
        $kontak_info = [
            'alamat' => $contacts->where('jenis', 'alamat')->pluck('nilai')->toArray(),
            'telepon' => $contacts->where('jenis', 'telepon')->pluck('nilai')->toArray()
        ];

        foreach ($pendaftaran as $reg) {
            $reg->price_info = $this->calculateRegistrationPrice($reg, $programData);
        }

        // Machine Learning Stats
        $mlStats = [
            'total_predictions' => 0,
            'dasar'             => 0,
            'menengah'          => 0,
            'mahir'             => 0,
            'avg_confidence'    => 0
        ];

        $learningPreds = LearningData::whereNotNull('recommendation_result')->get();
        if ($learningPreds->isNotEmpty()) {
            $mlStats['total_predictions'] = $learningPreds->count();
            $mlStats['dasar'] = $learningPreds->filter(fn($r) => in_array($r->recommendation_result, ['Dasar', 'Program Remedial Intensif']))->count();
            $mlStats['menengah'] = $learningPreds->filter(fn($r) => in_array($r->recommendation_result, ['Menengah', 'Pendampingan Akademik', 'Program Reguler']))->count();
            $mlStats['mahir'] = $learningPreds->filter(fn($r) => in_array($r->recommendation_result, ['Mahir', 'Program Pengayaan']))->count();
            
            $confidences = $learningPreds->whereNotNull('confidence')->pluck('confidence')->toArray();
            if (count($confidences) > 0) {
                $mlStats['avg_confidence'] = round(array_sum($confidences) / count($confidences), 2);
            }
        }

        // Flask API Status Stats
        $flaskStatus = [
            'status' => 'Offline',
            'version' => '1.0',
            'last_prediction_time' => '-',
            'total_requests' => 0
        ];

        // Fetch fallback values from DB in case API is offline or has reset
        $lastPred = LearningData::whereNotNull('prediction_time')->orderBy('prediction_time', 'desc')->first();
        $flaskStatus['last_prediction_time'] = $lastPred ? \Carbon\Carbon::parse($lastPred->prediction_time)->translatedFormat('d M Y, H:i:s') : '-';
        $flaskStatus['total_requests'] = LearningData::whereNotNull('recommendation_result')->count();

        try {
            $response = \Illuminate\Support\Facades\Http::timeout(2)->get('http://127.0.0.1:5000/health');
            if ($response->successful()) {
                $resData = $response->json();
                $flaskStatus['status'] = 'Online';
                $flaskStatus['version'] = $resData['version'] ?? '1.0';
                $flaskStatus['total_requests'] = $resData['total_requests'] ?? $flaskStatus['total_requests'];
                
                if (isset($resData['last_prediction_time']) && $resData['last_prediction_time'] !== 'Belum ada request') {
                    $flaskStatus['last_prediction_time'] = \Carbon\Carbon::parse($resData['last_prediction_time'])->translatedFormat('d M Y, H:i:s');
                }
            }
        } catch (\Exception $e) {
            // Flask is offline
        }

        $students = [];
        $hasil_belajar = [];
        if ($section === 'hasil_belajar') {
            $students = \App\Models\User::orderBy('name')->get();
            $hasil_belajar = LearningData::with(['user', 'program'])->orderBy('created_at', 'desc')->get();
        }

        return view('admin.dashboard', compact(
            'section', 'action', 'pendaftaran', 'programs', 'programData',
            'organisasi_info', 'misi_list', 'sejarah_paragraf', 'nilai_nilai',
            'struktur_organisasi', 'tim_pengajar', 'mata_pelajaran_filter', 'kontak_info',
            'mlStats', 'flaskStatus', 'students', 'hasil_belajar'
        ));
    }

    private function calculateRegistrationPrice($registration, $programData)
    {
        $jenjang = strtolower($registration->jenjang);
        $packageType = $registration->package_type;
        $packageKey = strtolower(str_replace([' ', '-', '/'], '_', $packageType));
        
        $durasi = match (strtolower($registration->durasi)) {
            '8x pertemuan', '8x' => '8x',
            '12x pertemuan', '12x' => '12x',
            'harian' => 'harian',
            default => strtolower(str_replace(' ', '', $registration->durasi))
        };
        
        $jumlahHari = $registration->durasi === 'harian' ? (int)($registration->jumlah_hari ?? 1) : 1;
        $subjects = $registration->subjects->pluck('subject_name')->toArray() ?? [];

        if (!isset($programData[$jenjang]['packages'][$packageKey]['prices'][$durasi])) {
            return [
                'total' => 0,
                'breakdown' => [
                    'Error' => "Data harga tidak ditemukan untuk jenjang '$jenjang', paket '$packageKey', durasi '$durasi'."
                ]
            ];
        }

        $basePricePerSubject = $programData[$jenjang]['packages'][$packageKey]['prices'][$durasi];
        $subjectCount = count($subjects);

        $totalBasePrice = $durasi === 'harian'
            ? $basePricePerSubject * $subjectCount * $jumlahHari
            : $basePricePerSubject * $subjectCount;

        $transportCost = 0;
        
        $transportPackageKeys = [
            'kelas_private_luar_petung_girimukti',
            'private_luar_petung_girimukti',
            'luar_petung_girimukti',
            'private_luar',
            'kelas_private_luar'
        ];
        
        $isTransportPackage = false;
        foreach ($transportPackageKeys as $key) {
            if (strpos($packageKey, $key) !== false || $packageKey === $key) {
                $isTransportPackage = true;
                break;
            }
        }

        if ($isTransportPackage) {
            if ($durasi === 'harian') {
                $transportCost = 6250 * $jumlahHari * $subjectCount;
            } else {
                $sessions = $durasi === '8x' ? 8 : 12;
                $transportCost = 6250 * $sessions * $subjectCount;
            }
        }

        $totalPrice = $totalBasePrice + $transportCost;

        $breakdown = [
            'Mata Pelajaran' => implode(', ', $subjects),
            'Jumlah Mata Pelajaran' => $subjectCount,
            'Biaya per Mata Pelajaran' => 'Rp ' . number_format($basePricePerSubject, 0, ',', '.'),
            'Total Biaya Pembelajaran' => 'Rp ' . number_format($totalBasePrice, 0, ',', '.'),
            'Biaya Transportasi' => $transportCost > 0 ? 'Rp ' . number_format($transportCost, 0, ',', '.') : 'Tidak ada',
            'Total' => 'Rp ' . number_format($totalPrice, 0, ',', '.')
        ];

        return [
            'total' => $totalPrice,
            'breakdown' => $breakdown
        ];
    }

    public function updateRegistrationStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        try {
            $reg = Registration::findOrFail($request->input('id'));
            $reg->status = $request->input('status');
            $reg->save();

            return redirect()->route('admin.dashboard', ['section' => 'registration'])
                ->with('success', 'Status pendaftaran berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard', ['section' => 'registration'])
                ->with('error', 'Gagal memperbarui status pendaftaran: ' . $e->getMessage());
        }
    }

    public function deleteRegistration(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            DB::beginTransaction();
            RegistrationSubject::where('registration_id', $request->input('id'))->delete();
            Registration::findOrFail($request->input('id'))->delete();
            DB::commit();

            return redirect()->route('admin.dashboard', ['section' => 'registration'])
                ->with('success', 'Pendaftaran berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['section' => 'registration'])
                ->with('error', 'Gagal menghapus pendaftaran: ' . $e->getMessage());
        }
    }

    public function addProgram(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:sd,smp,sma',
            'description' => 'required|string',
            'duration' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'subjects' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $programCode = 'PROG' . time();
            $subjects = array_map('trim', explode(',', $request->input('subjects')));

            $program = Program::create([
                'program_code' => $programCode,
                'category' => $request->input('category'),
                'icon' => $request->input('icon') ?: 'fas fa-book',
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'duration' => $request->input('duration'),
                'frequency' => $request->input('frequency'),
                'subjects' => $subjects
            ]);

            if ($request->filled('features')) {
                $features = array_map('trim', explode(',', $request->input('features')));
                foreach ($features as $feat) {
                    ProgramFeature::create([
                        'program_id' => $program->id,
                        'feature_text' => $feat
                    ]);
                }
            }

            if ($request->filled('packages')) {
                $packages = explode(',', $request->input('packages'));
                foreach ($packages as $pkg) {
                    $pkgData = explode('|', $pkg);
                    if (count($pkgData) >= 4) {
                        $package = ProgramPackage::create([
                            'program_id' => $program->id,
                            'package_type' => $pkgData[0],
                            'description' => $pkgData[1],
                            'package_icon' => $pkgData[2],
                            'info' => $pkgData[4] ?? '',
                            'extra_info' => $pkgData[5] ?? ''
                        ]);

                        $prices = explode(';', $pkgData[3]);
                        foreach ($prices as $price) {
                            $priceParts = explode(':', $price);
                            if (count($priceParts) == 2) {
                                PackagePrice::create([
                                    'package_id' => $package->id,
                                    'price_label' => $priceParts[0],
                                    'price' => (float)$priceParts[1]
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('success', 'Program berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('error', 'Gagal menambahkan program: ' . $e->getMessage());
        }
    }

    public function updateProgram(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'title' => 'required|string|max:255',
            'category' => 'required|in:sd,smp,sma',
            'description' => 'required|string',
            'duration' => 'required|string|max:100',
            'frequency' => 'required|string|max:100',
            'subjects' => 'required|string'
        ]);

        try {
            DB::beginTransaction();

            $program = Program::findOrFail($request->input('id'));
            $subjects = array_map('trim', explode(',', $request->input('subjects')));

            $program->update([
                'category' => $request->input('category'),
                'icon' => $request->input('icon') ?: 'fas fa-book',
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'duration' => $request->input('duration'),
                'frequency' => $request->input('frequency'),
                'subjects' => $subjects
            ]);

            // Clear features and packages
            ProgramFeature::where('program_id', $program->id)->delete();
            $packageIds = ProgramPackage::where('program_id', $program->id)->pluck('id');
            PackagePrice::whereIn('package_id', $packageIds)->delete();
            ProgramPackage::where('program_id', $program->id)->delete();

            if ($request->filled('features')) {
                $features = array_map('trim', explode(',', $request->input('features')));
                foreach ($features as $feat) {
                    ProgramFeature::create([
                        'program_id' => $program->id,
                        'feature_text' => $feat
                    ]);
                }
            }

            if ($request->filled('packages')) {
                $packages = explode(',', $request->input('packages'));
                foreach ($packages as $pkg) {
                    $pkgData = explode('|', $pkg);
                    if (count($pkgData) >= 4) {
                        $package = ProgramPackage::create([
                            'program_id' => $program->id,
                            'package_type' => $pkgData[0],
                            'description' => $pkgData[1],
                            'package_icon' => $pkgData[2],
                            'info' => $pkgData[4] ?? '',
                            'extra_info' => $pkgData[5] ?? ''
                        ]);

                        $prices = explode(';', $pkgData[3]);
                        foreach ($prices as $price) {
                            $priceParts = explode(':', $price);
                            if (count($priceParts) == 2) {
                                PackagePrice::create([
                                    'package_id' => $package->id,
                                    'price_label' => $priceParts[0],
                                    'price' => (float)$priceParts[1]
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('success', 'Program berhasil diperbarui!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('error', 'Gagal memperbarui program: ' . $e->getMessage());
        }
    }

    public function deleteProgram(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        try {
            DB::beginTransaction();

            $program = Program::findOrFail($request->input('id'));
            $packageIds = ProgramPackage::where('program_id', $program->id)->pluck('id');
            
            PackagePrice::whereIn('package_id', $packageIds)->delete();
            ProgramPackage::where('program_id', $program->id)->delete();
            ProgramFeature::where('program_id', $program->id)->delete();
            $program->delete();

            DB::commit();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('success', 'Program berhasil dihapus!');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['section' => 'program'])
                ->with('error', 'Gagal menghapus program: ' . $e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            DB::beginTransaction();
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // 1. Organisasi Info
            $org = OrganisasiInfo::first();
            if (!$org) {
                $org = new OrganisasiInfo();
            }
            $org->visi = $request->input('visi');
            $org->tahun_berdiri = (int)$request->input('tahun_berdiri');
            $org->jumlah_siswa_awal = (int)$request->input('jumlah_siswa_awal');
            $org->save();

            // 2. Sejarah
            SejarahOrganisasi::truncate();
            $sejarah = explode(',', $request->input('sejarah'));
            foreach ($sejarah as $index => $paragraf) {
                if (trim($paragraf)) {
                    SejarahOrganisasi::create([
                        'paragraf' => trim($paragraf),
                        'urutan' => $index + 1
                    ]);
                }
            }

            // 3. Misi
            MisiOrganisasi::truncate();
            $misi = explode(',', $request->input('misi'));
            foreach ($misi as $index => $misi_text) {
                if (trim($misi_text)) {
                    MisiOrganisasi::create([
                        'misi_text' => trim($misi_text),
                        'urutan' => $index + 1
                    ]);
                }
            }

            // 4. Nilai-Nilai
            NilaiOrganisasi::truncate();
            $nilai_nilai = explode(',', $request->input('nilai_nilai'));
            foreach ($nilai_nilai as $nilai) {
                if (trim($nilai)) {
                    list($nama, $icon, $deskripsi) = array_pad(explode('|', trim($nilai)), 3, '');
                    NilaiOrganisasi::create([
                        'nama' => $nama,
                        'icon' => $icon,
                        'deskripsi' => $deskripsi
                    ]);
                }
            }

            // 5. Struktur
            StrukturOrganisasi::truncate();
            $struktur = explode(',', $request->input('struktur_organisasi'));
            foreach ($struktur as $staff) {
                if (trim($staff)) {
                    list($nama, $posisi, $foto, $deskripsi, $level) = array_pad(explode('|', trim($staff)), 5, '');
                    StrukturOrganisasi::create([
                        'level' => (int)$level,
                        'nama' => $nama,
                        'posisi' => $posisi,
                        'deskripsi' => $deskripsi,
                        'foto' => $foto
                    ]);
                }
            }

            // 6. Mata Pelajaran Filter
            MataPelajaran::truncate();
            $filter = explode(',', $request->input('mata_pelajaran_filter'));
            foreach ($filter as $item) {
                if (trim($item)) {
                    list($kode, $nama) = array_pad(explode('|', trim($item)), 2, '');
                    MataPelajaran::create([
                        'kode' => $kode,
                        'nama' => $nama
                    ]);
                }
            }

            // 7. Tim Pengajar
            TimPengajar::truncate();
            $pengajar = explode(',', $request->input('tim_pengajar'));
            foreach ($pengajar as $teacher) {
                if (trim($teacher)) {
                    list($nama, $foto, $mata_pelajaran, $mata_pelajaran_kode, $deskripsi) = array_pad(explode('|', trim($teacher)), 5, '');
                    $mp = MataPelajaran::where('kode', $mata_pelajaran_kode)->first();
                    TimPengajar::create([
                        'nama' => $nama,
                        'mata_pelajaran_id' => $mp ? $mp->id : 1, // fallback mapping
                        'deskripsi' => $deskripsi,
                        'foto' => $foto,
                        'status' => 'aktif'
                    ]);
                }
            }

            // 8. Kontak Info
            KontakInfo::truncate();
            $alamat = explode(',', $request->input('alamat'));
            foreach ($alamat as $index => $val) {
                if (trim($val)) {
                    KontakInfo::create([
                        'jenis' => 'alamat',
                        'nilai' => trim($val),
                        'status' => 'aktif',
                        'urutan' => $index + 1
                    ]);
                }
            }

            $telepon = explode(',', $request->input('telepon'));
            foreach ($telepon as $index => $val) {
                if (trim($val)) {
                    KontakInfo::create([
                        'jenis' => 'telepon',
                        'nilai' => trim($val),
                        'status' => 'aktif',
                        'urutan' => $index + 1
                    ]);
                }
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::commit();

            return redirect()->route('admin.dashboard', ['section' => 'profil'])
                ->with('success', 'Profil berhasil diperbarui!');

        } catch (Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::rollBack();
            return redirect()->route('admin.dashboard', ['section' => 'profil'])
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    public function testPredict(Request $request)
    {
        $request->validate([
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_kuis' => 'required|numeric|min:0|max:100',
            'study_duration' => 'required|numeric|min:1|max:5',
            'tingkat_kesulitan' => 'required|string|in:Mudah,Sedang,Sulit',
            'kehadiran' => 'required|string|in:Baik,Cukup,Kurang',
        ]);

        $inputData = [
            'nilai_tugas' => (float)$request->input('nilai_tugas'),
            'nilai_kuis' => (float)$request->input('nilai_kuis'),
            'tingkat_kesulitan' => $request->input('tingkat_kesulitan'),
            'study_duration' => (float)$request->input('study_duration'),
            'kehadiran' => $request->input('kehadiran'),
        ];

        $startTime = microtime(true);
        try {
            $response = \Illuminate\Support\Facades\Http::timeout(3)->post('http://127.0.0.1:5000/predict', $inputData);
            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $startTime) * 1000, 2);

            $adminName = session('admin_username') ?? 'Unknown Admin';
            $adminId = session('admin_id') ?? 'Unknown';
            $userLog = "Admin: $adminName (ID: $adminId)";

            if ($response->successful()) {
                $resData = $response->json();
                
                \Illuminate\Support\Facades\Log::channel('ml')->info('ML Admin Test Prediction Success', [
                    'user' => $userLog,
                    'input' => $inputData,
                    'output' => $resData,
                    'confidence' => ($resData['confidence'] ?? '-') . '%',
                    'response_time' => $responseTimeMs . ' ms'
                ]);

                return response()->json($resData, $response->status());
            } else {
                $errorMsg = "Flask API returned status code: " . $response->status();
                
                \Illuminate\Support\Facades\Log::channel('ml')->error('ML Admin Test Prediction Failed', [
                    'user' => $userLog,
                    'input' => $inputData,
                    'error' => $errorMsg,
                    'response_time' => $responseTimeMs . ' ms'
                ]);

                return response()->json(['error' => $errorMsg], $response->status());
            }
        } catch (\Exception $e) {
            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $startTime) * 1000, 2);
            $errorMsg = $e->getMessage();

            $adminName = session('admin_username') ?? 'Unknown Admin';
            $adminId = session('admin_id') ?? 'Unknown';
            $userLog = "Admin: $adminName (ID: $adminId)";

            \Illuminate\Support\Facades\Log::channel('ml')->error('ML Admin Test Prediction Exception', [
                'user' => $userLog,
                'input' => $inputData,
                'error' => $errorMsg,
                'response_time' => $responseTimeMs . ' ms'
            ]);

            return response()->json(['error' => 'Gagal terhubung ke Flask API: ' . $errorMsg], 500);
        }
    }

    public function addHasilBelajar(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_kuis' => 'required|numeric|min:0|max:100',
            'kehadiran' => 'required|string|in:Baik,Cukup,Kurang',
        ]);

        $program = Program::findOrFail($request->input('program_id'));
        $nilai = ($request->input('nilai_tugas') + $request->input('nilai_kuis')) / 2;

        LearningData::create([
            'user_id' => $request->input('user_id'),
            'program_id' => $request->input('program_id'),
            'mata_pelajaran' => $program->title,
            'nilai_tugas' => $request->input('nilai_tugas'),
            'nilai_kuis' => $request->input('nilai_kuis'),
            'nilai' => $nilai,
            'kehadiran' => $request->input('kehadiran'),
            'tingkat_kesulitan' => 'Sedang', // default placeholder before student completes it
            'recommendation_result' => 'Menunggu Input Siswa',
        ]);

        return redirect()->route('admin.dashboard', ['section' => 'hasil_belajar'])
            ->with('success', 'Data hasil belajar berhasil ditambahkan! Siswa sekarang dapat melengkapi data untuk memicu rekomendasi.');
    }

    public function updateHasilBelajar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:learning_data,id',
            'user_id' => 'required|exists:users,id',
            'program_id' => 'required|exists:programs,id',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_kuis' => 'required|numeric|min:0|max:100',
            'kehadiran' => 'required|string|in:Baik,Cukup,Kurang',
        ]);

        $record = LearningData::findOrFail($request->input('id'));
        $program = Program::findOrFail($request->input('program_id'));
        $nilai = ($request->input('nilai_tugas') + $request->input('nilai_kuis')) / 2;

        $record->update([
            'user_id' => $request->input('user_id'),
            'program_id' => $request->input('program_id'),
            'mata_pelajaran' => $program->title,
            'nilai_tugas' => $request->input('nilai_tugas'),
            'nilai_kuis' => $request->input('nilai_kuis'),
            'nilai' => $nilai,
            'kehadiran' => $request->input('kehadiran'),
        ]);

        return redirect()->route('admin.dashboard', ['section' => 'hasil_belajar'])
            ->with('success', 'Data hasil belajar berhasil diperbarui!');
    }

    public function deleteHasilBelajar(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:learning_data,id',
        ]);

        $record = LearningData::findOrFail($request->input('id'));
        $record->delete();

        return redirect()->route('admin.dashboard', ['section' => 'hasil_belajar'])
            ->with('success', 'Data hasil belajar berhasil dihapus!');
    }
}
