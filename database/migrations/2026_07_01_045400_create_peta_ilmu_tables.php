<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Admins
        if (!Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->integer('id', true); // auto-increment primary key
                $table->string('username', 50)->unique();
                $table->string('password', 255);
                $table->timestamp('created_at')->useCurrent();
            });

            // Insert default admin
            DB::table('admins')->insert([
                'username' => 'admin',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' // admin123
            ]);
        }

        // 2. Programs
        if (!Schema::hasTable('programs')) {
            Schema::create('programs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('program_code', 50)->unique();
                $table->enum('category', ['sd', 'smp', 'sma']);
                $table->string('icon', 100)->nullable();
                $table->string('title', 255);
                $table->text('description')->nullable();
                $table->string('duration', 100)->nullable();
                $table->string('frequency', 100)->nullable();
                $table->json('subjects')->nullable();
                $table->timestamp('created_at')->useCurrent();
                $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            });
        }

        // 3. Program Features
        if (!Schema::hasTable('program_features')) {
            Schema::create('program_features', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('program_id');
                $table->text('feature_text');
                
                $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            });
        }

        // 4. Program Packages
        if (!Schema::hasTable('program_packages')) {
            Schema::create('program_packages', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('program_id');
                $table->string('package_type', 100);
                $table->text('description')->nullable();
                $table->string('package_icon', 100)->nullable();
                $table->text('info')->nullable();
                $table->text('extra_info')->nullable();

                $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            });
        }

        // 5. Package Prices
        if (!Schema::hasTable('package_prices')) {
            Schema::create('package_prices', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('package_id');
                $table->string('price_label', 50);
                $table->decimal('price', 10, 2);

                $table->foreign('package_id')->references('id')->on('program_packages')->onDelete('cascade');
            });
        }

        // 6. Program Benefits
        if (!Schema::hasTable('program_benefits')) {
            Schema::create('program_benefits', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('icon', 100)->nullable();
                $table->string('title', 255);
                $table->text('description')->nullable();
            });

            // Default program benefits
            DB::table('program_benefits')->insert([
                ['icon' => 'fas fa-chalkboard-teacher', 'title' => 'Pengajar Berpengalaman', 'description' => 'Tim pengajar profesional dan berpengalaman di bidangnya'],
                ['icon' => 'fas fa-users', 'title' => 'Kelas Kecil', 'description' => 'Kelas dengan jumlah siswa terbatas untuk pembelajaran lebih fokus'],
                ['icon' => 'fas fa-book-open', 'title' => 'Materi Lengkap', 'description' => 'Materi pembelajaran lengkap dan terupdate'],
                ['icon' => 'fas fa-clock', 'title' => 'Fleksibel', 'description' => 'Jadwal belajar yang fleksibel sesuai kebutuhan siswa']
            ]);
        }

        // 7. Program FAQs
        if (!Schema::hasTable('program_faqs')) {
            Schema::create('program_faqs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('question');
                $table->text('answer');
            });

            // Default program FAQs
            DB::table('program_faqs')->insert([
                ['question' => 'Bagaimana cara mendaftar?', 'answer' => 'Anda dapat mendaftar melalui halaman pendaftaran atau datang langsung ke lokasi kami.'],
                ['question' => 'Berapa biaya pendaftaran?', 'answer' => 'Biaya pendaftaran bervariasi tergantung program yang dipilih. Silakan lihat detail program untuk informasi lebih lanjut.'],
                ['question' => 'Apakah ada trial class?', 'answer' => 'Ya, kami menyediakan trial class untuk memastikan program cocok untuk siswa.']
            ]);
        }

        // 8. Pendaftaran
        if (!Schema::hasTable('pendaftaran')) {
            Schema::create('pendaftaran', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama_lengkap', 255);
                $table->date('tanggal_lahir')->nullable();
                $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
                $table->text('alamat')->nullable();
                $table->string('telepon', 20);
                $table->string('email', 255)->nullable();
                $table->enum('jenjang', ['sd', 'smp', 'sma']);
                $table->string('kelas', 50);
                $table->string('sekolah', 255)->nullable();
                $table->integer('package_id')->nullable();
                $table->string('package_type', 100);
                $table->string('durasi', 50);
                $table->integer('jumlah_hari')->nullable();
                $table->string('nama_ortu', 255);
                $table->string('pekerjaan_ortu', 255)->nullable();
                $table->string('telepon_ortu', 20);
                $table->text('motivasi')->nullable();
                $table->string('referensi', 255)->nullable();
                $table->decimal('total_price', 10, 2);
                $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
                $table->timestamp('created_at')->useCurrent();

                $table->foreign('package_id')->references('id')->on('program_packages')->onDelete('set null');
            });
        }

        // 9. Registration Subjects
        if (!Schema::hasTable('registration_subjects')) {
            Schema::create('registration_subjects', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('registration_id');
                $table->string('subject_name', 255);

                $table->foreign('registration_id')->references('id')->on('pendaftaran')->onDelete('cascade');
            });
        }

        // 10. Organisasi Info
        if (!Schema::hasTable('organisasi_info')) {
            Schema::create('organisasi_info', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('visi')->nullable();
                $table->integer('tahun_berdiri')->nullable();
                $table->integer('jumlah_siswa_awal')->nullable();
            });

            // Default
            DB::table('organisasi_info')->insert([
                'visi' => 'Menjadi lembaga bimbingan belajar terbaik yang membantu siswa mencapai potensi maksimal mereka.',
                'tahun_berdiri' => 2010,
                'jumlah_siswa_awal' => 20
            ]);
        }

        // 11. Sejarah Organisasi
        if (!Schema::hasTable('sejarah_organisasi')) {
            Schema::create('sejarah_organisasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('paragraf');
                $table->integer('urutan');
            });
        }

        // 12. Misi Organisasi
        if (!Schema::hasTable('misi_organisasi')) {
            Schema::create('misi_organisasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->text('misi_text');
                $table->integer('urutan');
            });
        }

        // 13. Nilai Organisasi
        if (!Schema::hasTable('nilai_organisasi')) {
            Schema::create('nilai_organisasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('icon', 100)->nullable();
                $table->string('nama', 255);
                $table->text('deskripsi')->nullable();
            });
        }

        // 14. Struktur Organisasi
        if (!Schema::hasTable('struktur_organisasi')) {
            Schema::create('struktur_organisasi', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('level');
                $table->string('nama', 255);
                $table->string('posisi', 255);
                $table->text('deskripsi')->nullable();
                $table->string('foto', 255)->nullable();
            });
        }

        // 15. Mata Pelajaran
        if (!Schema::hasTable('mata_pelajaran')) {
            Schema::create('mata_pelajaran', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('kode', 50)->unique();
                $table->string('nama', 255);
            });

            // Default subjects
            DB::table('mata_pelajaran')->insert([
                ['kode' => 'MTK', 'nama' => 'Matematika'],
                ['kode' => 'BID', 'nama' => 'Bahasa Indonesia'],
                ['kode' => 'BIG', 'nama' => 'Bahasa Inggris'],
                ['kode' => 'IPA', 'nama' => 'Ilmu Pengetahuan Alam'],
                ['kode' => 'IPS', 'nama' => 'Ilmu Pengetahuan Sosial'],
                ['kode' => 'FIS', 'nama' => 'Fisika'],
                ['kode' => 'KIM', 'nama' => 'Kimia'],
                ['kode' => 'BIO', 'nama' => 'Biologi'],
                ['kode' => 'SEJ', 'nama' => 'Sejarah'],
                ['kode' => 'GEO', 'nama' => 'Geografi'],
                ['kode' => 'ECO', 'nama' => 'Ekonomi'],
                ['kode' => 'SOS', 'nama' => 'Sosiologi']
            ]);
        }

        // 16. Tim Pengajar
        if (!Schema::hasTable('tim_pengajar')) {
            Schema::create('tim_pengajar', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('nama', 255);
                $table->integer('mata_pelajaran_id');
                $table->text('deskripsi')->nullable();
                $table->string('foto', 255)->nullable();
                $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');

                $table->foreign('mata_pelajaran_id')->references('id')->on('mata_pelajaran')->onDelete('cascade');
            });
        }

        // 17. Kontak Info
        if (!Schema::hasTable('kontak_info')) {
            Schema::create('kontak_info', function (Blueprint $table) {
                $table->integer('id', true);
                $table->enum('jenis', ['alamat', 'telepon', 'email', 'fax']);
                $table->text('nilai');
                $table->enum('status', ['aktif', 'non-aktif'])->default('aktif');
                $table->integer('urutan')->default(1);
            });
        }

        // 18. Students
        if (!Schema::hasTable('students')) {
            Schema::create('students', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('username', 50)->unique();
                $table->string('nama', 255);
                $table->string('email', 255)->nullable();
                $table->string('password', 255);
                $table->enum('jenjang', ['sd', 'smp', 'sma']);
                $table->string('kelas', 50);
                $table->timestamp('created_at')->useCurrent();
            });

            // Insert default student
            DB::table('students')->insert([
                'username' => 'siswa',
                'nama' => 'Siswa Demo',
                'email' => 'siswa@petailmu.local',
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // let's set it to standard hash of "siswa123"
                'jenjang' => 'sd',
                'kelas' => '5'
            ]);
        }

        // 19. Learning Data
        if (!Schema::hasTable('learning_data')) {
            Schema::create('learning_data', function (Blueprint $table) {
                $table->integer('id', true);
                $table->integer('student_id');
                $table->string('mata_pelajaran', 255);
                $table->decimal('nilai', 5, 2)->nullable();
                $table->enum('tingkat_kesulitan', ['mudah', 'sedang', 'sulit'])->default('sedang');
                $table->string('gaya_belajar', 100)->nullable();
                $table->text('catatan')->nullable();
                $table->timestamp('tanggal_input')->useCurrent();

                $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_data');
        Schema::dropIfExists('students');
        Schema::dropIfExists('kontak_info');
        Schema::dropIfExists('tim_pengajar');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('struktur_organisasi');
        Schema::dropIfExists('nilai_organisasi');
        Schema::dropIfExists('misi_organisasi');
        Schema::dropIfExists('sejarah_organisasi');
        Schema::dropIfExists('organisasi_info');
        Schema::dropIfExists('registration_subjects');
        Schema::dropIfExists('pendaftaran');
        Schema::dropIfExists('program_faqs');
        Schema::dropIfExists('program_benefits');
        Schema::dropIfExists('package_prices');
        Schema::dropIfExists('program_packages');
        Schema::dropIfExists('program_features');
        Schema::dropIfExists('programs');
        Schema::dropIfExists('admins');
    }
};
