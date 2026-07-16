<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\ProgramFeature;
use App\Models\ProgramPackage;
use App\Models\PackagePrice;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Avoid duplicate seeding
        if (Program::count() > 0) {
            return;
        }

        // 1. Program SD
        $sd = Program::create([
            'program_code' => 'PROG_SD',
            'category' => 'sd',
            'icon' => 'fas fa-child',
            'title' => 'Program SD (Sekolah Dasar)',
            'description' => 'Program pembelajaran lengkap untuk siswa SD kelas 1-6 meliputi semua mata pelajaran utama dengan metode yang menyenangkan dan interaktif.',
            'duration' => '90 Menit per Sesi',
            'frequency' => '2 - 3 Kali per Minggu',
            'subjects' => ["Matematika", "Bahasa Indonesia", "IPA", "IPS", "Bahasa Inggris", "Seni Budaya", "PKn"]
        ]);

        ProgramFeature::create(['program_id' => $sd->id, 'feature_text' => 'Matematika - konsep dasar hingga operasi hitung lanjutan']);
        ProgramFeature::create(['program_id' => $sd->id, 'feature_text' => 'Bahasa Indonesia - membaca, menulis, berbicara']);
        ProgramFeature::create(['program_id' => $sd->id, 'feature_text' => 'Persiapan Ujian Sekolah & Ulangan Harian']);

        $pkgSdReg = ProgramPackage::create([
            'program_id' => $sd->id,
            'package_type' => 'Kelas Reguler',
            'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
            'package_icon' => 'fas fa-users',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSdReg->id, 'price_label' => '8x Pertemuan', 'price' => 160000]);
        PackagePrice::create(['package_id' => $pkgSdReg->id, 'price_label' => '12x Pertemuan', 'price' => 240000]);
        PackagePrice::create(['package_id' => $pkgSdReg->id, 'price_label' => 'Harian', 'price' => 30000]);

        $pkgSdPrivLocal = ProgramPackage::create([
            'program_id' => $sd->id,
            'package_type' => 'Private - Petung/Girimukti',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-home',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSdPrivLocal->id, 'price_label' => '8x Pertemuan', 'price' => 200000]);
        PackagePrice::create(['package_id' => $pkgSdPrivLocal->id, 'price_label' => '12x Pertemuan', 'price' => 300000]);
        PackagePrice::create(['package_id' => $pkgSdPrivLocal->id, 'price_label' => 'Harian', 'price' => 35000]);

        $pkgSdPrivOut = ProgramPackage::create([
            'program_id' => $sd->id,
            'package_type' => 'Private - Luar Petung/Giri',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-car',
            'info' => '',
            'extra_info' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
        ]);
        PackagePrice::create(['package_id' => $pkgSdPrivOut->id, 'price_label' => '8x Pertemuan', 'price' => 240000]);
        PackagePrice::create(['package_id' => $pkgSdPrivOut->id, 'price_label' => '12x Pertemuan', 'price' => 360000]);
        PackagePrice::create(['package_id' => $pkgSdPrivOut->id, 'price_label' => 'Harian', 'price' => 40000]);


        // 2. Program SMP
        $smp = Program::create([
            'program_code' => 'PROG_SMP',
            'category' => 'smp',
            'icon' => 'fas fa-book-reader',
            'title' => 'Program SMP (Sekolah Menengah Pertama)',
            'description' => 'Program yang dirancang khusus untuk siswa SMP dengan fokus pada tiga mata pelajaran inti untuk membangun fondasi akademik yang kuat.',
            'duration' => '90 Menit per Sesi',
            'frequency' => '2 - 3 Kali per Minggu',
            'subjects' => ["Matematika", "IPA (Fisika & Biologi)", "Bahasa Inggris"]
        ]);

        ProgramFeature::create(['program_id' => $smp->id, 'feature_text' => 'Fokus mata pelajaran Matematika, IPA, dan Bahasa Inggris']);
        ProgramFeature::create(['program_id' => $smp->id, 'feature_text' => 'Persiapan menghadapi asesmen nasional / ujian akhir']);

        $pkgSmpReg = ProgramPackage::create([
            'program_id' => $smp->id,
            'package_type' => 'Kelas Reguler',
            'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
            'package_icon' => 'fas fa-users',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSmpReg->id, 'price_label' => '8x Pertemuan', 'price' => 200000]);
        PackagePrice::create(['package_id' => $pkgSmpReg->id, 'price_label' => '12x Pertemuan', 'price' => 300000]);
        PackagePrice::create(['package_id' => $pkgSmpReg->id, 'price_label' => 'Harian', 'price' => 35000]);

        $pkgSmpPrivLocal = ProgramPackage::create([
            'program_id' => $smp->id,
            'package_type' => 'Private - Petung/Girimukti',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-home',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSmpPrivLocal->id, 'price_label' => '8x Pertemuan', 'price' => 240000]);
        PackagePrice::create(['package_id' => $pkgSmpPrivLocal->id, 'price_label' => '12x Pertemuan', 'price' => 360000]);
        PackagePrice::create(['package_id' => $pkgSmpPrivLocal->id, 'price_label' => 'Harian', 'price' => 40000]);

        $pkgSmpPrivOut = ProgramPackage::create([
            'program_id' => $smp->id,
            'package_type' => 'Private - Luar Petung/Giri',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-car',
            'info' => '',
            'extra_info' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
        ]);
        PackagePrice::create(['package_id' => $pkgSmpPrivOut->id, 'price_label' => '8x Pertemuan', 'price' => 280000]);
        PackagePrice::create(['package_id' => $pkgSmpPrivOut->id, 'price_label' => '12x Pertemuan', 'price' => 420000]);
        PackagePrice::create(['package_id' => $pkgSmpPrivOut->id, 'price_label' => 'Harian', 'price' => 45000]);


        // 3. Program SMA
        $sma = Program::create([
            'program_code' => 'PROG_SMA',
            'category' => 'sma',
            'icon' => 'fas fa-user-graduate',
            'title' => 'Program SMA (Sekolah Menengah Atas)',
            'description' => 'Program intensif untuk siswa SMA dengan fokus pada mata pelajaran sains untuk persiapan optimal masuk perguruan tinggi.',
            'duration' => '90 Menit per Sesi',
            'frequency' => '2 - 3 Kali per Minggu',
            'subjects' => ["Matematika", "Fisika", "Kimia", "Biologi"]
        ]);

        ProgramFeature::create(['program_id' => $sma->id, 'feature_text' => 'Fokus materi sains MIPA (Matematika, Fisika, Kimia, Biologi)']);
        ProgramFeature::create(['program_id' => $sma->id, 'feature_text' => 'Strategi dan persiapan masuk PTN favorit']);

        $pkgSmaReg = ProgramPackage::create([
            'program_id' => $sma->id,
            'package_type' => 'Kelas Reguler',
            'description' => 'Max 5 Siswa : 1 Guru (Di tempat bimbel)',
            'package_icon' => 'fas fa-users',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSmaReg->id, 'price_label' => '8x Pertemuan', 'price' => 240000]);
        PackagePrice::create(['package_id' => $pkgSmaReg->id, 'price_label' => '12x Pertemuan', 'price' => 360000]);
        PackagePrice::create(['package_id' => $pkgSmaReg->id, 'price_label' => 'Harian', 'price' => 40000]);

        $pkgSmaPrivLocal = ProgramPackage::create([
            'program_id' => $sma->id,
            'package_type' => 'Private - Petung/Girimukti',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-home',
            'info' => '',
            'extra_info' => ''
        ]);
        PackagePrice::create(['package_id' => $pkgSmaPrivLocal->id, 'price_label' => '8x Pertemuan', 'price' => 280000]);
        PackagePrice::create(['package_id' => $pkgSmaPrivLocal->id, 'price_label' => '12x Pertemuan', 'price' => 420000]);
        PackagePrice::create(['package_id' => $pkgSmaPrivLocal->id, 'price_label' => 'Harian', 'price' => 45000]);

        $pkgSmaPrivOut = ProgramPackage::create([
            'program_id' => $sma->id,
            'package_type' => 'Private - Luar Petung/Giri',
            'description' => '1 Siswa : 1 Guru (Guru datang ke rumah)',
            'package_icon' => 'fas fa-car',
            'info' => '',
            'extra_info' => '*Tambahan biaya transportasi guru: Rp 6.250/pertemuan'
        ]);
        PackagePrice::create(['package_id' => $pkgSmaPrivOut->id, 'price_label' => '8x Pertemuan', 'price' => 320000]);
        PackagePrice::create(['package_id' => $pkgSmaPrivOut->id, 'price_label' => '12x Pertemuan', 'price' => 480000]);
        PackagePrice::create(['package_id' => $pkgSmaPrivOut->id, 'price_label' => 'Harian', 'price' => 50000]);
    }
}
