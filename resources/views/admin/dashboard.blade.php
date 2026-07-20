@extends('layouts.app-admin')

@section('title', 'Admin Dashboard - Bimbingan Belajar Peta Ilmu')

@section('styles')
<style>
    .welcome-card {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: #ffffff;
        border-radius: 16px;
        padding: 35px 30px;
        margin-bottom: 30px;
        box-shadow: 0 10px 25px rgba(30, 60, 114, 0.15);
        display: flex;
        align-items: center;
        justify-content: space-between;
        overflow: hidden;
        position: relative;
    }
    .welcome-card::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -100px;
        right: -80px;
    }
    .welcome-text-content {
        max-width: 75%;
        z-index: 2;
    }
    .welcome-title {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .welcome-desc {
        font-size: 0.95rem;
        opacity: 0.85;
        line-height: 1.6;
    }
    .welcome-icon {
        font-size: 4.5rem;
        color: rgba(255, 255, 255, 0.12);
        z-index: 2;
    }

    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }
    .stat-card-custom {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: inherit;
    }
    .stat-card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(30, 60, 114, 0.08);
        border-color: #cbd5e1;
    }
    .stat-details h4 {
        font-size: 0.85rem;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 5px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .stat-details .stat-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
    }
    .stat-details .stat-desc {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: 5px;
    }
    .stat-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        transition: transform 0.3s ease;
    }
    .stat-card-custom:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }
    
    .bg-blue-light { background-color: #eff6ff; color: #2563eb; }
    .bg-green-light { background-color: #ecfdf5; color: #059669; }
    .bg-orange-light { background-color: #fffbeb; color: #d97706; }

    .dashboard-panel {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
        padding: 25px;
        margin-bottom: 30px;
    }
    .panel-header-custom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f1f5f9;
    }
    .panel-header-custom h3 {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }
    .panel-action-btn {
        font-size: 0.85rem;
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .panel-action-btn:hover {
        color: #1d4ed8;
    }

    .badge-status {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
        text-transform: capitalize;
    }
    .badge-pending { background-color: #fef3c7; color: #d97706; }
    .badge-confirmed { background-color: #d1fae5; color: #059669; }
    .badge-rejected { background-color: #fee2e2; color: #dc2626; }
</style>
@endsection

@section('content')
    @if ($section == 'profil')
        <h3>Kelola Profil</h3>
        <form action="{{ route('admin.profil.update') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="visi">Visi</label>
                <textarea id="visi" name="visi" class="form-control" required style="width: 100%; min-height: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">{{ $organisasi_info->visi ?? '' }}</textarea>
            </div>
            <div class="form-group">
                <label for="misi">Misi (pisahkan dengan koma untuk beberapa item)</label>
                <textarea id="misi" name="misi" class="form-control" style="width: 100%; min-height: 80px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">{{ implode(', ', $misi_list) }}</textarea>
            </div>
            <div class="form-group">
                <label for="tahun_berdiri">Tahun Berdiri</label>
                <input type="text" id="tahun_berdiri" name="tahun_berdiri" class="form-control" value="{{ $organisasi_info->tahun_berdiri ?? '' }}" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;" />
            </div>
            <div class="form-group">
                <label for="jumlah_siswa_awal">Jumlah Siswa Awal</label>
                <input type="text" id="jumlah_siswa_awal" name="jumlah_siswa_awal" class="form-control" value="{{ $organisasi_info->jumlah_siswa_awal ?? '' }}" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #ccc;" />
            </div>
            <div class="form-group">
                <label for="sejarah">Sejarah (pisahkan paragraf dengan koma)</label>
                <textarea id="sejarah" name="sejarah" class="form-control" style="width: 100%; min-height: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">{{ implode(', ', $sejarah_paragraf) }}</textarea>
            </div>
            <div class="form-group">
                <label for="nilai_nilai">Nilai-Nilai (format: nama|icon|deskripsi, pisahkan item dengan koma)</label>
                <textarea id="nilai_nilai" name="nilai_nilai" class="form-control" style="width: 100%; min-height: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">@php
                    $nilai_str = '';
                    foreach ($nilai_nilai as $nilai) {
                        $nilai_str .= $nilai->nama . '|' . $nilai->icon . '|' . $nilai->deskripsi . ',';
                    }
                    echo rtrim($nilai_str, ',');
                @endphp</textarea>
            </div>
            <div class="form-group">
                <label for="struktur_organisasi">Struktur Organisasi (format: nama|posisi|foto|deskripsi|level, pisahkan item dengan koma)</label>
                <textarea id="struktur_organisasi" name="struktur_organisasi" class="form-control" style="width: 100%; min-height: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">@php
                    $struktur_str = '';
                    foreach ($struktur_organisasi as $staff) {
                        $struktur_str .= $staff->nama . '|' . $staff->posisi . '|' . $staff->foto . '|' . $staff->deskripsi . '|' . $staff->level . ',';
                    }
                    echo rtrim($struktur_str, ',');
                @endphp</textarea>
            </div>
            <div class="form-group">
                <label for="tim_pengajar">Tim Pengajar (format: nama|foto|mata_pelajaran|mata_pelajaran_kode|deskripsi, pisahkan item dengan koma)</label>
                <textarea id="tim_pengajar" name="tim_pengajar" class="form-control" style="width: 100%; min-height: 100px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">@php
                    $pengajar_str = '';
                    foreach ($tim_pengajar as $pengajar) {
                        $pengajar_str .= $pengajar->nama . '|' . $pengajar->foto . '|' . ($pengajar->mataPelajaran->nama ?? '') . '|' . ($pengajar->mataPelajaran->kode ?? '') . '|' . $pengajar->deskripsi . ',';
                    }
                    echo rtrim($pengajar_str, ',');
                @endphp</textarea>
            </div>
            <div class="form-group" style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1; margin-top: 15px; margin-bottom: 20px;">
                <label style="font-weight: 600; color: #1e293b; margin-bottom: 8px; display: block;">
                    <i class="fas fa-upload" style="color: #6366f1; margin-right: 6px;"></i> Upload Foto Guru Baru
                </label>
                <p style="font-size: 0.8rem; color: #64748b; margin-top: 0; margin-bottom: 12px;">
                    Unggah foto guru di sini. Setelah berhasil diunggah, salin path-nya (misal: <code>images/nama_file.png</code>) dan masukkan ke format Tim Pengajar di atas.
                </p>
                <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <input type="file" id="upload_foto_guru_input" accept="image/*" style="display: none;" />
                    <button type="button" class="action-btn" id="btn_pilih_foto" style="padding: 8px 16px; background: #6366f1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; display: flex; align-items: center; gap: 6px; transition: background-color 0.2s;">
                        <i class="fas fa-image"></i> Pilih & Upload Foto
                    </button>
                    <span id="upload_status" style="font-size: 0.85rem; color: #64748b;"></span>
                </div>
                
                <!-- Upload Result / Preview -->
                <div id="upload_result_container" style="display: none; margin-top: 15px; align-items: center; gap: 15px; padding: 10px; background: white; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <img id="upload_preview" src="" alt="Preview" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;" />
                    <div style="flex: 1;">
                        <div style="font-size: 0.8rem; color: #475569; font-weight: 500;">Berhasil diunggah! Gunakan path ini:</div>
                        <div style="display: flex; gap: 8px; margin-top: 4px; align-items: center; flex-wrap: wrap;">
                            <code id="uploaded_path_code" style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: 600; color: #0f172a;">images/filename.jpg</code>
                            <button type="button" id="btn_copy_path" style="padding: 4px 10px; background: #e2e8f0; border: none; border-radius: 4px; cursor: pointer; font-size: 0.75rem; font-weight: 500; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fas fa-copy"></i> Salin Path
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="mata_pelajaran_filter">Filter Mata Pelajaran (format: kode|nama, pisahkan item dengan koma)</label>
                <textarea id="mata_pelajaran_filter" name="mata_pelajaran_filter" class="form-control" style="width: 100%; min-height: 80px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">@php
                    $filter_str = '';
                    foreach ($mata_pelajaran_filter as $key => $value) {
                        $filter_str .= $key . '|' . $value . ',';
                    }
                    echo rtrim($filter_str, ',');
                @endphp</textarea>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat (pisahkan dengan koma)</label>
                <textarea id="alamat" name="alamat" class="form-control" style="width: 100%; min-height: 80px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">{{ implode(', ', $kontak_info['alamat']) }}</textarea>
            </div>
            <div class="form-group">
                <label for="telepon">Telepon (pisahkan dengan koma)</label>
                <textarea id="telepon" name="telepon" class="form-control" style="width: 100%; min-height: 80px; padding: 10px; border-radius: 6px; border: 1px solid #ccc; font-family: inherit;">{{ implode(', ', $kontak_info['telepon']) }}</textarea>
            </div>
            <button type="submit" class="action-btn edit-btn" style="padding: 10px 20px; background: #667eea; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Simpan Profil</button>
        </form>

    @elseif ($section == 'program')
        <div class="content-section fade-in">
            <div class="section-header">
                <h3>Kelola Program</h3>
            </div>
            <div class="section-body">
                <form action="{{ route('admin.program.add') }}" method="POST">
                    @csrf
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="title">Nama Program <span class="required">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="category">Jenjang <span class="required">*</span></label>
                            <select id="category" name="category" class="form-control" required>
                                <option value="sd">SD</option>
                                <option value="smp">SMP</option>
                                <option value="sma">SMA</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi <span class="required">*</span></label>
                            <textarea id="description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="duration">Durasi <span class="required">*</span></label>
                            <input type="text" id="duration" name="duration" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="frequency">Frekuensi <span class="required">*</span></label>
                            <input type="text" id="frequency" name="frequency" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label for="icon">Ikon (Font Awesome class)</label>
                            <input type="text" id="icon" name="icon" class="form-control" value="fas fa-book" />
                        </div>
                        <div class="form-group">
                            <label for="subjects">Mata Pelajaran (pisahkan dengan koma) <span class="required">*</span></label>
                            <textarea id="subjects" name="subjects" class="form-control" required placeholder="Contoh: Matematika, IPA, Bahasa Inggris"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="features">Fitur Program (pisahkan dengan koma)</label>
                            <textarea id="features" name="features" class="form-control" placeholder="Contoh: Kelas Kecil, Pengajar Berpengalaman, Evaluasi Bulanan"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="packages">Paket (format: type|description|icon|prices|info|extra_info, pisahkan item dengan koma, prices format: label:price;label:price)</label>
                            <textarea id="packages" name="packages" class="form-control" placeholder="Contoh: Kelas Reguler|Max 5 Siswa|fas fa-users|8x Pertemuan:160000;12x Pertemuan:240000;Harian:30000||"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="primary-btn">Tambah Program</button>
                </form>

                <div class="table-container" style="margin-top: 30px;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Program</th>
                                <th>Jenjang</th>
                                <th>Deskripsi</th>
                                <th>Durasi</th>
                                <th>Frekuensi</th>
                                <th>Mata Pelajaran</th>
                                <th>Fitur</th>
                                <th>Paket</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($programs as $program)
                                <tr>
                                    <td>{{ $program->title }}</td>
                                    <td>{{ strtoupper($program->category) }}</td>
                                    <td>{{ $program->description }}</td>
                                    <td>{{ $program->duration }}</td>
                                    <td>{{ $program->frequency }}</td>
                                    <td>
                                        @php
                                            $subjects = is_array($program->subjects) ? $program->subjects : json_decode($program->subjects, true);
                                        @endphp
                                        {{ is_array($subjects) ? implode(', ', $subjects) : '' }}
                                    </td>
                                    <td>{{ $program->features->pluck('feature_text')->implode(', ') }}</td>
                                    <td>
                                        <ul class="packages-list">
                                            @foreach ($program->packages as $package)
                                                <li>
                                                    <strong>{{ $package->package_type }}</strong>: 
                                                    {{ $package->description }}
                                                    <ul class="prices-list">
                                                        @foreach ($package->prices as $price)
                                                            <li>{{ $price->price_label }}: Rp {{ number_format($price->price, 0, ',', '.') }}</li>
                                                        @endforeach
                                                    </ul>
                                                    @if (!empty($package->info))
                                                        <small>{{ $package->info }}</small><br>
                                                    @endif
                                                    @if (!empty($package->extra_info))
                                                        <small style="color:red;">{{ $package->extra_info }}</small>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.dashboard', ['section' => 'program', 'action' => 'edit', 'id' => $program->id]) }}" class="action-btn edit-btn" style="text-decoration:none; display:inline-block; text-align:center;">Edit</a>
                                        
                                        <form action="{{ route('admin.program.delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $program->id }}">
                                            <button type="submit" class="action-btn delete-btn">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($action == 'edit' && request()->has('id'))
                    @php
                        $editProgram = $programs->firstWhere('id', request()->input('id'));
                    @endphp
                    @if ($editProgram)
                        <div class="content-section" style="margin-top: 30px; border-top: 2px solid #edf2f7; padding-top: 30px;">
                            <div class="section-header">
                                <h3>Edit Program: {{ $editProgram->title }}</h3>
                            </div>
                            <div class="section-body">
                                <form action="{{ route('admin.program.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $editProgram->id }}">
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="title">Nama Program <span class="required">*</span></label>
                                            <input type="text" id="title" name="title" class="form-control" value="{{ $editProgram->title }}" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Jenjang <span class="required">*</span></label>
                                            <select id="category" name="category" class="form-control" required>
                                                <option value="sd" {{ $editProgram->category == 'sd' ? 'selected' : '' }}>SD</option>
                                                <option value="smp" {{ $editProgram->category == 'smp' ? 'selected' : '' }}>SMP</option>
                                                <option value="sma" {{ $editProgram->category == 'sma' ? 'selected' : '' }}>SMA</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Deskripsi <span class="required">*</span></label>
                                            <textarea id="description" name="description" class="form-control" required>{{ $editProgram->description }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="duration">Durasi <span class="required">*</span></label>
                                            <input type="text" id="duration" name="duration" class="form-control" value="{{ $editProgram->duration }}" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="frequency">Frekuensi <span class="required">*</span></label>
                                            <input type="text" id="frequency" name="frequency" class="form-control" value="{{ $editProgram->frequency }}" required />
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Ikon (Font Awesome class)</label>
                                            <input type="text" id="icon" name="icon" class="form-control" value="{{ $editProgram->icon }}" />
                                        </div>
                                        <div class="form-group">
                                            <label for="subjects">Mata Pelajaran (pisahkan dengan koma) <span class="required">*</span></label>
                                            <textarea id="subjects" name="subjects" class="form-control" required>@php
                                                $editSubjects = is_array($editProgram->subjects) ? $editProgram->subjects : json_decode($editProgram->subjects, true);
                                                echo is_array($editSubjects) ? implode(', ', $editSubjects) : '';
                                            @endphp</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="features">Fitur Program (pisahkan dengan koma)</label>
                                            <textarea id="features" name="features" class="form-control">{{ $editProgram->features->pluck('feature_text')->implode(', ') }}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="packages">Paket (format: type|description|icon|prices|info|extra_info, pisahkan item dengan koma, prices format: label:price;label:price)</label>
                                            <textarea id="packages" name="packages" class="form-control">@php
                                                $packages_str = '';
                                                foreach ($editProgram->packages as $package) {
                                                    $prices_str = '';
                                                    foreach ($package->prices as $price) {
                                                        $prices_str .= $price->price_label . ':' . $price->price . ';';
                                                    }
                                                    $packages_str .= $package->package_type . '|' . $package->description . '|' . $package->package_icon . '|' . rtrim($prices_str, ';') . '|' . ($package->info ?? '') . '|' . ($package->extra_info ?? '') . ',';
                                                }
                                                echo rtrim($packages_str, ',');
                                            @endphp</textarea>
                                        </div>
                                    </div>
                                    <button type="submit" class="primary-btn">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

    @elseif ($section == 'registration')
        <div class="content-section fade-in">
            <div class="section-header">
                <h3>Kelola Pendaftaran</h3>
            </div>
            <div class="section-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Jenjang</th>
                                <th>Kelas</th>
                                <th>Telepon</th>
                                <th>Email</th>
                                <th>Paket Program</th>
                                <th>Mata Pelajaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendaftaran as $registration)
                                <tr>
                                    <td>{{ $registration->nama_lengkap }}</td>
                                    <td>{{ strtoupper($registration->jenjang) }}</td>
                                    <td>{{ $registration->kelas }}</td>
                                    <td>{{ $registration->telepon }}</td>
                                    <td>{{ $registration->email ?: '-' }}</td>
                                    <td>{{ $registration->package_type }}</td>
                                    <td>{{ $registration->subjects->pluck('subject_name')->implode(', ') }}</td>
                                    <td>
                                        <form action="{{ route('admin.registration.update_status') }}" method="POST" style="display:inline;">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $registration->id }}">
                                            <select name="status" class="status-select" onchange="this.form.submit()" style="padding: 6px; border-radius: 4px; border: 1px solid #ccc;">
                                                <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ $registration->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.registration.delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pendaftaran ini?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $registration->id }}">
                                            <button type="submit" class="action-btn delete-btn">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 20px;">Tidak ada data pendaftaran ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="content-section" style="margin-top: 30px;">
                    <div class="section-header">
                        <h3>Rincian Biaya Pendaftaran</h3>
                    </div>
                    <div class="section-body">
                        <div class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Jenjang</th>
                                        <th>Paket</th>
                                        <th>Durasi</th>
                                        <th>Jumlah Hari</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Total Biaya</th>
                                        <th>Rincian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pendaftaran as $registration)
                                        @php
                                            $priceInfo = $registration->price_info;
                                        @endphp
                                        <tr>
                                            <td>{{ $registration->nama_lengkap }}</td>
                                            <td>{{ strtoupper($registration->jenjang) }}</td>
                                            <td>{{ $registration->package_type }}</td>
                                            <td>{{ $registration->durasi }}</td>
                                            <td>{{ $registration->durasi == 'harian' ? ($registration->jumlah_hari ?? '1') : '-' }}</td>
                                            <td>{{ $registration->subjects->pluck('subject_name')->implode(', ') }}</td>
                                            <td style="font-weight: 700;">Rp {{ number_format($priceInfo['total'], 0, ',', '.') }}</td>
                                            <td>
                                                @if (is_array($priceInfo['breakdown']))
                                                    <ul style="padding-left: 15px; margin: 0; font-size: 0.85rem; line-height: 1.5;">
                                                        @foreach ($priceInfo['breakdown'] as $key => $value)
                                                            <li><strong>{{ $key }}:</strong> {{ $value }}</li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    {{ $priceInfo['breakdown'] }}
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada rincian biaya pendaftaran.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @elseif ($section == 'ml')
        <h3 class="mb-4">Statistik Machine Learning</h3>
        
        <!-- ML Stat Cards Grid -->
        <div class="overview-grid fade-in">
            <div class="stat-card-custom">
                <div class="stat-details">
                    <h4>Jumlah Prediksi</h4>
                    <div class="stat-number">{{ $mlStats['total_predictions'] }}</div>
                    <div class="stat-desc">Total analisis dijalankan</div>
                </div>
                <div class="stat-icon-wrapper bg-blue-light">
                    <i class="fas fa-calculator"></i>
                </div>
            </div>

            <div class="stat-card-custom">
                <div class="stat-details">
                    <h4>Siswa Mahir</h4>
                    <div class="stat-number">{{ $mlStats['mahir'] }}</div>
                    <div class="stat-desc">Kategori kemampuan tinggi</div>
                </div>
                <div class="stat-icon-wrapper bg-green-light">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>

            <div class="stat-card-custom">
                <div class="stat-details">
                    <h4>Siswa Menengah</h4>
                    <div class="stat-number">{{ $mlStats['menengah'] }}</div>
                    <div class="stat-desc">Kategori kemampuan sedang</div>
                </div>
                <div class="stat-icon-wrapper bg-orange-light">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
            </div>

            <div class="stat-card-custom font-red-card" style="border-radius: 16px; border: 1px solid #e2e8f0; background: #ffffff; padding: 25px; display: flex; align-items: center; justify-content: space-between;">
                <div class="stat-details">
                    <h4 style="font-size: 0.85rem; color: #64748b; text-transform: uppercase; margin-bottom: 5px; font-weight: 600; letter-spacing: 0.5px;">Siswa Dasar</h4>
                    <div class="stat-number" style="font-size: 2.2rem; font-weight: 700; color: #0f172a; line-height: 1.1;">{{ $mlStats['dasar'] }}</div>
                    <div class="stat-desc" style="font-size: 0.8rem; color: #94a3b8; margin-top: 5px;">Butuh materi penguatan dasar</div>
                </div>
                <div class="stat-icon-wrapper" style="width: 60px; height: 60px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; background-color: #fef2f2; color: #dc2626;">
                    <i class="fas fa-hands-helping"></i>
                </div>
            </div>
        </div>

        <div class="overview-grid fade-in" style="margin-top: -15px;">
            <div class="stat-card-custom" style="grid-column: 1 / -1;">
                <div class="stat-details">
                    <h4>Rata-Rata Kepercayaan Prediksi (Confidence Score)</h4>
                    <div class="stat-number">{{ $mlStats['avg_confidence'] }}%</div>
                    <div class="stat-desc">Tingkat keyakinan klasifikasi Random Forest secara kumulatif</div>
                </div>
                <div class="stat-icon-wrapper" style="background-color: #f0fdf4; color: #16a34a; width: 60px; height: 60px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.6rem;">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="dashboard-panel fade-in" style="margin-top: 20px;">
            <div class="panel-header-custom">
                <h3>Grafik Distribusi Hasil Prediksi</h3>
                <span class="text-muted small">Visualisasi Sebaran Rekomendasi Program Belajar</span>
            </div>
            <div style="max-width: 480px; margin: 0 auto; padding: 15px 0;">
                <canvas id="predictionChart" width="400" height="400"></canvas>
            </div>
        </div>

    @elseif ($section == 'api_status')
        <h3 class="mb-4">Status Layanan Flask API</h3>
        
        <div class="dashboard-panel fade-in">
            <div class="panel-header-custom">
                <h3>Kondisi Server Machine Learning</h3>
                <span class="text-muted small">Status koneksi real-time ke modul Random Forest</span>
            </div>
            
            <div class="row g-4 mt-2">
                <!-- Status Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-4 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%;">
                        <div class="text-muted small mb-2" style="font-weight: 600; letter-spacing: 0.5px;">STATUS API</div>
                        <div class="d-inline-block">
                            @if($flaskStatus['status'] === 'Online')
                                <span class="badge rounded-pill px-4 py-2 text-success" style="background-color: #d1fae5; font-size: 14px; font-weight: 700; border: 1px solid rgba(16,185,129,0.2);">
                                    <i class="fas fa-check-circle me-1"></i> ONLINE
                                </span>
                            @else
                                <span class="badge rounded-pill px-4 py-2 text-danger" style="background-color: #fee2e2; font-size: 14px; font-weight: 700; border: 1px solid rgba(239,68,68,0.2);">
                                    <i class="fas fa-times-circle me-1"></i> OFFLINE
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Version Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-4 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%;">
                        <div class="text-muted small mb-2" style="font-weight: 600; letter-spacing: 0.5px;">VERSI MODEL</div>
                        <div class="fw-bold text-dark fs-4" style="font-size: 20px;">v{{ $flaskStatus['version'] }}</div>
                        <div class="text-muted small mt-1">Random Forest Classifier</div>
                    </div>
                </div>

                <!-- Requests Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-4 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%;">
                        <div class="text-muted small mb-2" style="font-weight: 600; letter-spacing: 0.5px;">JUMLAH REQUEST</div>
                        <div class="fw-bold text-dark fs-4" style="font-size: 20px;">{{ $flaskStatus['total_requests'] }}</div>
                        <div class="text-muted small mt-1">Prediksi terproses</div>
                    </div>
                </div>

                <!-- Last Prediction Card -->
                <div class="col-md-6 col-lg-3">
                    <div class="p-4 rounded-4 text-center" style="background-color: #f8fafc; border: 1px solid #e2e8f0; height: 100%;">
                        <div class="text-muted small mb-2" style="font-weight: 600; letter-spacing: 0.5px;">PREDIKSI TERAKHIR</div>
                        <div class="fw-semibold text-dark text-truncate" style="font-size: 13.5px;" title="{{ $flaskStatus['last_prediction_time'] }}">{{ $flaskStatus['last_prediction_time'] }}</div>
                        <div class="text-muted small mt-1">Waktu Server / DB</div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 p-3 rounded-3 text-secondary" style="background-color: #f1f5f9; font-size: 12.5px; line-height: 1.6;">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <strong>Catatan Integrasi:</strong> Flask API bertindak sebagai model klasifikasi biner/multi-kelas mandiri yang berjalan secara lokal pada port 5000. 
                Jika status menunjukkan <span class="text-danger fw-bold">OFFLINE</span>, periksa konsol server Anda dan pastikan perintah <code>python ML/app.py</code> telah dijalankan secara benar. Sistem Laravel dilengkapi fitur toleransi kesalahan sehingga aktivitas input belajar siswa tetap dapat berjalan normal walaupun status server Flask tidak terhubung.
            </div>
        </div>

    @elseif ($section == 'api_testing')
        <h3 class="mb-4">Live API Testing</h3>
        
        <div class="row g-4">
            <!-- Form Card -->
            <div class="col-md-6 col-12">
                <div class="dashboard-panel fade-in" style="height: 100%;">
                    <div class="panel-header-custom">
                        <h3>Form Simulasi Prediksi</h3>
                        <span class="text-muted small">Input data parameter siswa untuk dikirim ke Flask API</span>
                    </div>
                    
                    <form id="apiTestForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nilai_tugas" class="form-label" style="font-weight: 600; font-size: 13.5px; color: #4b5563;">Nilai Tugas (0 - 100)</label>
                            <input type="number" class="form-control" id="nilai_tugas" name="nilai_tugas" min="0" max="100" placeholder="Contoh: 85" required style="padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; width: 100%;">
                        </div>

                        <div class="mb-3">
                            <label for="nilai_kuis" class="form-label" style="font-weight: 600; font-size: 13.5px; color: #4b5563;">Nilai Kuis (0 - 100)</label>
                            <input type="number" class="form-control" id="nilai_kuis" name="nilai_kuis" min="0" max="100" placeholder="Contoh: 80" required style="padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; width: 100%;">
                        </div>

                        <div class="mb-3">
                            <label for="study_duration" class="form-label" style="font-weight: 600; font-size: 13.5px; color: #4b5563;">Durasi Belajar Mandiri (1 - 5 jam)</label>
                            <input type="number" class="form-control" id="study_duration" name="study_duration" min="1" max="5" step="0.5" placeholder="Contoh: 3" required style="padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; width: 100%;">
                        </div>

                        <div class="mb-3">
                            <label for="tingkat_kesulitan" class="form-label" style="font-weight: 600; font-size: 13.5px; color: #4b5563;">Tingkat Kesulitan</label>
                            <select class="form-select" id="tingkat_kesulitan" name="tingkat_kesulitan" required style="padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; width: 100%;">
                                <option value="">Pilih kesulitan materi</option>
                                <option value="Mudah">Mudah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Sulit">Sulit</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="kehadiran" class="form-label" style="font-weight: 600; font-size: 13.5px; color: #4b5563;">Kehadiran Siswa</label>
                            <select class="form-select" id="kehadiran" name="kehadiran" required style="padding: 10px; border-radius: 8px; border: 1px solid #d1d5db; width: 100%;">
                                <option value="">Pilih tingkat kehadiran</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup">Cukup</option>
                                <option value="Kurang">Kurang</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" style="padding: 11px; border-radius: 8px; font-weight: 600; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); border: none; color: white;">
                            <span id="btnText"><i class="fas fa-paper-plane me-2"></i>Kirim Prediksi</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none; margin: 0 auto;"></span>
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Result Card -->
            <div class="col-md-6 col-12">
                <div class="dashboard-panel fade-in" style="height: 100%; display: flex; flex-direction: column;">
                    <div class="panel-header-custom">
                        <h3>Raw JSON Response</h3>
                        <span class="text-muted small">Respon mentah yang dikembalikan oleh Flask API secara real-time</span>
                    </div>
                    
                    <div id="resultPlaceholder" class="flex-grow-1 d-flex flex-column align-items-center justify-content-center text-muted p-5 text-center" style="background-color: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; min-height: 250px;">
                        <i class="fas fa-code mb-3" style="font-size: 2.5rem; color: #94a3b8;"></i>
                        <p class="mb-0 fw-medium">Belum ada data pengujian yang dikirim.</p>
                        <p class="small text-muted mt-1">Isi form di samping lalu klik tombol prediksi untuk melihat output JSON.</p>
                    </div>

                    <div id="resultContainer" class="flex-grow-1" style="display: none;">
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1" id="resStatus">STATUS: 200 OK</span>
                            <button id="btnCopyJson" class="btn btn-sm btn-outline-secondary" style="font-size: 11.5px; border-radius: 6px;"><i class="fas fa-copy me-1"></i>Copy JSON</button>
                        </div>
                        <pre id="jsonResult" class="bg-dark text-light p-3 rounded-3" style="font-size: 12px; font-family: 'Courier New', Courier, monospace; max-height: 420px; overflow-y: auto; line-height: 1.5; margin: 0;"></pre>
                    </div>
                </div>
            </div>
        </div>

    @elseif ($section == 'hasil_belajar')
        <div class="content-section fade-in">
            <div class="section-header">
                <h3>Kelola Hasil Belajar</h3>
            </div>
            
            <div class="section-body">
                <!-- Form Tambah Hasil Belajar -->
                <div class="dashboard-panel" style="margin-bottom: 30px;">
                    <div class="panel-header-custom">
                        <h3>Input Hasil Belajar Baru</h3>
                        <span class="text-muted small">Kategori nilai tugas, nilai kuis, dan kehadiran diinput oleh Admin</span>
                    </div>
                    
                    <form action="{{ route('admin.hasil_belajar.add') }}" method="POST">
                        @csrf
                        <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                            <div class="form-group">
                                <label for="user_id" style="font-weight:600; font-size:13.5px; color:#4b5563;">Pilih Siswa <span class="required" style="color:red;">*</span></label>
                                <select id="user_id" name="user_id" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                    <option value="">-- Pilih Siswa --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="program_id" style="font-weight:600; font-size:13.5px; color:#4b5563;">Pilih Program <span class="required" style="color:red;">*</span></label>
                                <select id="program_id" name="program_id" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                    <option value="">-- Pilih Program --</option>
                                    @foreach ($programs as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->title }} ({{ strtoupper($prog->category) }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="nilai_tugas" style="font-weight:600; font-size:13.5px; color:#4b5563;">Nilai Tugas <span class="required" style="color:red;">*</span></label>
                                <input type="number" id="nilai_tugas" name="nilai_tugas" min="0" max="100" class="form-control" placeholder="Contoh: 85" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;" />
                            </div>
                            
                            <div class="form-group">
                                <label for="nilai_kuis" style="font-weight:600; font-size:13.5px; color:#4b5563;">Nilai Kuis <span class="required" style="color:red;">*</span></label>
                                <input type="number" id="nilai_kuis" name="nilai_kuis" min="0" max="100" class="form-control" placeholder="Contoh: 80" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;" />
                            </div>
                            
                            <div class="form-group">
                                <label for="kehadiran" style="font-weight:600; font-size:13.5px; color:#4b5563;">Kehadiran <span class="required" style="color:red;">*</span></label>
                                <select id="kehadiran" name="kehadiran" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                    <option value="">-- Pilih Kehadiran --</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup">Cukup</option>
                                    <option value="Kurang">Kurang</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-3" style="margin-top:20px;">
                            <button type="submit" class="primary-btn" style="padding:10px 24px; border-radius:8px; background:#2563eb; color:#fff; border:none; font-weight:600; cursor:pointer;">Simpan Hasil Belajar</button>
                        </div>
                    </form>
                </div>
                
                <!-- Tabel List Hasil Belajar -->
                <div class="table-container" style="background:#ffffff; border-radius:12px; border:1px solid #e2e8f0; padding:15px; overflow-x:auto;">
                    <table class="table" style="width:100%; border-collapse:collapse;">
                        <thead>
                            <tr style="border-bottom: 2px solid #f1f5f9; text-align: left;">
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600;">Siswa</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600;">Program</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Nilai Tugas</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Nilai Kuis</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Rata-rata</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Kehadiran</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Rekomendasi</th>
                                <th style="padding:12px 10px; color:#64748b; font-size:0.85rem; font-weight:600; text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($hasil_belajar as $record)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding:14px 10px; font-size:0.9rem; font-weight:600; color:#0f172a;">
                                        {{ $record->user->name ?? 'N/A' }}<br>
                                        <span class="text-muted" style="font-size:11px; font-weight:normal;">{{ $record->user->email ?? '' }}</span>
                                    </td>
                                    <td style="padding:14px 10px; font-size:0.85rem; color:#475569;">
                                        {{ $record->program->title ?? $record->mata_pelajaran }}<br>
                                        <span class="badge" style="font-size:10px; background:#f1f5f9; color:#64748b; padding:2px 8px; border-radius:12px;">{{ strtoupper($record->program->category ?? 'SD') }}</span>
                                    </td>
                                    <td style="padding:14px 10px; font-size:0.85rem; color:#475569; text-align:center; font-weight:600;">{{ $record->nilai_tugas ?? '-' }}</td>
                                    <td style="padding:14px 10px; font-size:0.85rem; color:#475569; text-align:center; font-weight:600;">{{ $record->nilai_kuis ?? '-' }}</td>
                                    <td style="padding:14px 10px; font-size:0.85rem; color:#475569; text-align:center; font-weight:600; color:#2563eb;">{{ number_format($record->nilai, 1) }}</td>
                                    <td style="padding:14px 10px; font-size:0.85rem; color:#475569; text-align:center;">
                                        <span class="badge" style="font-size:11px; padding:3px 10px; border-radius:20px; font-weight:600;
                                            {{ $record->kehadiran === 'Baik' ? 'background:#ecfdf5; color:#059669;' : ($record->kehadiran === 'Cukup' ? 'background:#fffbeb; color:#d97706;' : 'background:#fee2e2; color:#dc2626;') }}">
                                            {{ $record->kehadiran ?? '-' }}
                                        </span>
                                    </td>
                                    <td style="padding:14px 10px; font-size:0.85rem; text-align:center;">
                                        @if ($record->recommendation_result === 'Menunggu Input Siswa')
                                            <span class="badge" style="background:#f1f5f9; color:#64748b; padding:4px 12px; border-radius:50px; font-size:11px; font-weight:600; border: 1px dashed #cbd5e1;">
                                                <i class="fas fa-clock me-1"></i> Menunggu Input Siswa
                                            </span>
                                        @else
                                            <span class="badge" style="padding:4px 12px; border-radius:50px; font-size:11px; font-weight:700;
                                                {{ in_array($record->recommendation_result, ['Mahir', 'Program Pengayaan']) ? 'background:#ecfdf5; color:#059669;' : (in_array($record->recommendation_result, ['Menengah', 'Pendampingan Akademik', 'Program Reguler']) ? 'background:#eff6ff; color:#2563eb;' : 'background:#fee2e2; color:#dc2626;') }}">
                                                {{ $record->recommendation_result }}
                                            </span>
                                            @if ($record->confidence)
                                                <div style="font-size:10px; color:#94a3b8; margin-top:3px; font-weight:600;">Conf: {{ number_format($record->confidence, 2) }}%</div>
                                            @endif
                                        @endif
                                    </td>
                                    <td style="padding:14px 10px; text-align:center;">
                                        <a href="{{ route('admin.dashboard', ['section' => 'hasil_belajar', 'action' => 'edit', 'id' => $record->id]) }}" class="action-btn edit-btn" style="text-decoration:none; display:inline-block; text-align:center; padding:5px 12px; font-size:12px; font-weight:600; border-radius:6px;">Edit</a>
                                        
                                        <form action="{{ route('admin.hasil_belajar.delete') }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data hasil belajar ini?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $record->id }}">
                                            <button type="submit" class="action-btn delete-btn" style="padding:5px 12px; font-size:12px; font-weight:600; border-radius:6px;">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 25px; color: #94a3b8; font-size: 0.9rem;">Belum ada data hasil belajar diinput.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Form Edit Hasil Belajar -->
                @if ($action == 'edit' && request()->has('id'))
                    @php
                        $editRecord = $hasil_belajar->firstWhere('id', request()->input('id'));
                    @endphp
                    @if ($editRecord)
                        <div class="content-section" style="margin-top: 30px; border-top: 2px solid #edf2f7; padding-top: 30px;">
                            <div class="section-header">
                                <h3>Edit Hasil Belajar: {{ $editRecord->user->name ?? 'N/A' }}</h3>
                            </div>
                            <div class="section-body">
                                <form action="{{ route('admin.hasil_belajar.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $editRecord->id }}">
                                    <div class="form-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                                        <div class="form-group">
                                            <label for="edit_user_id" style="font-weight:600; font-size:13.5px; color:#4b5563;">Pilih Siswa <span class="required" style="color:red;">*</span></label>
                                            <select id="edit_user_id" name="user_id" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                                @foreach ($students as $student)
                                                    <option value="{{ $student->id }}" {{ $editRecord->user_id == $student->id ? 'selected' : '' }}>{{ $student->name }} ({{ $student->email }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_program_id" style="font-weight:600; font-size:13.5px; color:#4b5563;">Pilih Program <span class="required" style="color:red;">*</span></label>
                                            <select id="edit_program_id" name="program_id" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                                @foreach ($programs as $prog)
                                                    <option value="{{ $prog->id }}" {{ $editRecord->program_id == $prog->id ? 'selected' : '' }}>{{ $prog->title }} ({{ strtoupper($prog->category) }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_nilai_tugas" style="font-weight:600; font-size:13.5px; color:#4b5563;">Nilai Tugas <span class="required" style="color:red;">*</span></label>
                                            <input type="number" id="edit_nilai_tugas" name="nilai_tugas" min="0" max="100" class="form-control" value="{{ $editRecord->nilai_tugas }}" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_nilai_kuis" style="font-weight:600; font-size:13.5px; color:#4b5563;">Nilai Kuis <span class="required" style="color:red;">*</span></label>
                                            <input type="number" id="edit_nilai_kuis" name="nilai_kuis" min="0" max="100" class="form-control" value="{{ $editRecord->nilai_kuis }}" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="edit_kehadiran" style="font-weight:600; font-size:13.5px; color:#4b5563;">Kehadiran <span class="required" style="color:red;">*</span></label>
                                            <select id="edit_kehadiran" name="kehadiran" class="form-control" required style="width:100%; padding:10px; border-radius:8px; border:1px solid #d1d5db;">
                                                <option value="Baik" {{ $editRecord->kehadiran == 'Baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="Cukup" {{ $editRecord->kehadiran == 'Cukup' ? 'selected' : '' }}>Cukup</option>
                                                <option value="Kurang" {{ $editRecord->kehadiran == 'Kurang' ? 'selected' : '' }}>Kurang</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3" style="margin-top:20px; display:flex; gap:10px;">
                                        <button type="submit" class="primary-btn" style="padding:10px 24px; border-radius:8px; background:#2563eb; color:#fff; border:none; font-weight:600; cursor:pointer;">Simpan Perubahan</button>
                                        <a href="{{ route('admin.dashboard', ['section' => 'hasil_belajar']) }}" class="action-btn" style="padding:9px 20px; border-radius:8px; background:#cbd5e1; color:#0f172a; text-decoration:none; display:inline-block; font-weight:600; text-align:center;">Batal</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>

    @else
        <!-- Welcome Card -->
        <div class="welcome-card fade-in">
            <div class="welcome-text-content">
                <h2 class="welcome-title">Selamat datang di Portal Admin Peta Ilmu!</h2>
                <p class="welcome-desc">Kelola program bimbingan belajar, data pendaftaran siswa baru, dan profil lembaga Peta Ilmu secara terpusat dengan cepat dan mudah.</p>
            </div>
            <div class="welcome-icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>

        <!-- Stat Cards Grid -->
        <div class="overview-grid fade-in">
            <a href="{{ route('admin.dashboard', ['section' => 'program']) }}" class="stat-card-custom">
                <div class="stat-details">
                    <h4>Total Program</h4>
                    <div class="stat-number">{{ count($programs) }}</div>
                    <div class="stat-desc">Program belajar aktif</div>
                </div>
                <div class="stat-icon-wrapper bg-blue-light">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </a>
            
            <a href="{{ route('admin.dashboard', ['section' => 'registration']) }}" class="stat-card-custom">
                <div class="stat-details">
                    <h4>Total Pendaftaran</h4>
                    <div class="stat-number">{{ count($pendaftaran) }}</div>
                    <div class="stat-desc">Pendaftaran masuk</div>
                </div>
                <div class="stat-icon-wrapper bg-green-light">
                    <i class="fas fa-users"></i>
                </div>
            </a>

            <a href="{{ route('admin.dashboard', ['section' => 'registration']) }}" class="stat-card-custom">
                <div class="stat-details">
                    <h4>Pendaftaran Pending</h4>
                    <div class="stat-number">{{ count($pendaftaran->where('status', 'pending')) }}</div>
                    <div class="stat-desc">Menunggu konfirmasi</div>
                </div>
                <div class="stat-icon-wrapper bg-orange-light">
                    <i class="fas fa-clock"></i>
                </div>
            </a>
        </div>

        <!-- Recent Registrations Panel -->
        <div class="dashboard-panel fade-in" style="animation-delay: 0.1s;">
            <div class="panel-header-custom">
                <h3>Pendaftaran Terbaru</h3>
                <a href="{{ route('admin.dashboard', ['section' => 'registration']) }}" class="panel-action-btn">Lihat Semua <i class="fas fa-arrow-right" style="font-size: 0.8rem; margin-left: 2px;"></i></a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 2px solid #f1f5f9; text-align: left;">
                            <th style="padding: 12px 10px; color: #64748b; font-size: 0.85rem; font-weight: 600;">Nama Lengkap</th>
                            <th style="padding: 12px 10px; color: #64748b; font-size: 0.85rem; font-weight: 600;">Jenjang</th>
                            <th style="padding: 12px 10px; color: #64748b; font-size: 0.85rem; font-weight: 600;">Paket</th>
                            <th style="padding: 12px 10px; color: #64748b; font-size: 0.85rem; font-weight: 600;">Tanggal Masuk</th>
                            <th style="padding: 12px 10px; color: #64748b; font-size: 0.85rem; font-weight: 600;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendaftaran->take(5) as $registration)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 14px 10px; font-size: 0.9rem; font-weight: 600; color: #0f172a;">{{ $registration->nama_lengkap }}</td>
                                <td style="padding: 14px 10px; font-size: 0.85rem; color: #475569;">{{ strtoupper($registration->jenjang) }}</td>
                                <td style="padding: 14px 10px; font-size: 0.85rem; color: #475569;">{{ $registration->package_type }}</td>
                                <td style="padding: 14px 10px; font-size: 0.85rem; color: #64748b;">{{ \Carbon\Carbon::parse($registration->created_at)->translatedFormat('d M Y, H:i') }} WIB</td>
                                <td style="padding: 14px 10px;">
                                    <span class="badge-status badge-{{ $registration->status }}">{{ $registration->status }}</span>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 25px; color: #94a3b8; font-size: 0.9rem;">Belum ada pendaftaran masuk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if ($section == 'ml')
        const ctx = document.getElementById('predictionChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
            labels: ['Mahir', 'Menengah', 'Dasar'],
            datasets: [{
                label: 'Jumlah Prediksi',
                data: [
                    {{ $mlStats['mahir'] }},
                    {{ $mlStats['menengah'] }},
                    {{ $mlStats['dasar'] }}
                ],
                backgroundColor: [
                    '#10b981', // Green - Mahir
                    '#f59e0b', // Orange - Menengah
                    '#ef4444'  // Red - Dasar
                ],
                borderWidth: 1,
                borderColor: '#ffffff'
            }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: "'Poppins', sans-serif",
                                size: 12
                            },
                            padding: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                return ` ${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
        @elseif ($section == 'api_testing')
        const form = document.getElementById('apiTestForm');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const resultPlaceholder = document.getElementById('resultPlaceholder');
        const resultContainer = document.getElementById('resultContainer');
        const jsonResult = document.getElementById('jsonResult');
        const resStatus = document.getElementById('resStatus');
        const btnCopyJson = document.getElementById('btnCopyJson');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading spinner
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';
            form.querySelector('button[type="submit"]').disabled = true;
            
            const formData = {
                nilai_tugas: form.nilai_tugas.value,
                nilai_kuis: form.nilai_kuis.value,
                study_duration: form.study_duration.value,
                tingkat_kesulitan: form.tingkat_kesulitan.value,
                kehadiran: form.kehadiran.value,
                _token: form._token.value
            };
            
            fetch("{{ route('admin.api_testing.predict') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": formData._token
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                const statusText = `STATUS: ${response.status} ${response.statusText}`;
                resStatus.innerText = statusText;
                
                // Adjust status badge color
                if (response.ok) {
                    resStatus.className = "badge bg-success-subtle text-success border border-success-subtle px-2.5 py-1";
                } else {
                    resStatus.className = "badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1";
                }
                
                return response.json();
            })
            .then(data => {
                // Show JSON result
                resultPlaceholder.style.display = 'none';
                resultContainer.style.display = 'block';
                jsonResult.textContent = JSON.stringify(data, null, 4);
            })
            .catch(error => {
                resultPlaceholder.style.display = 'none';
                resultContainer.style.display = 'block';
                resStatus.innerText = "STATUS: ERROR";
                resStatus.className = "badge bg-danger-subtle text-danger border border-danger-subtle px-2.5 py-1";
                jsonResult.textContent = JSON.stringify({ error: error.message }, null, 4);
            })
            .finally(() => {
                // Hide spinner
                btnText.style.display = 'inline-block';
                btnSpinner.style.display = 'none';
                form.querySelector('button[type="submit"]').disabled = false;
            });
        });
        
        btnCopyJson.addEventListener('click', function() {
            navigator.clipboard.writeText(jsonResult.textContent)
                .then(() => {
                    const originalText = btnCopyJson.innerHTML;
                    btnCopyJson.innerHTML = '<i class="fas fa-check text-success me-1"></i>Copied!';
                    setTimeout(() => {
                        btnCopyJson.innerHTML = originalText;
                    }, 2000);
                });
        });
        @endif

        @if ($section == 'profil')
        const fileInput = document.getElementById('upload_foto_guru_input');
        const btnPilihFoto = document.getElementById('btn_pilih_foto');
        const uploadStatus = document.getElementById('upload_status');
        const uploadResultContainer = document.getElementById('upload_result_container');
        const uploadPreview = document.getElementById('upload_preview');
        const uploadedPathCode = document.getElementById('uploaded_path_code');
        const btnCopyPath = document.getElementById('btn_copy_path');

        if (btnPilihFoto) {
            btnPilihFoto.addEventListener('click', function() {
                fileInput.click();
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                if (fileInput.files.length === 0) return;
                
                const file = fileInput.files[0];
                const formData = new FormData();
                formData.append('foto', file);
                formData.append('_token', '{{ csrf_token() }}');

                uploadStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengunggah...';
                btnPilihFoto.disabled = true;

                fetch("{{ route('admin.foto_guru.upload') }}", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        uploadStatus.innerHTML = '<span style="color: #10b981;"><i class="fas fa-check-circle"></i> Selesai!</span>';
                        uploadPreview.src = data.url;
                        uploadedPathCode.textContent = data.path;
                        uploadResultContainer.style.display = 'flex';
                    } else {
                        uploadStatus.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Gagal: ' + data.message + '</span>';
                    }
                })
                .catch(error => {
                    uploadStatus.innerHTML = '<span style="color: #ef4444;"><i class="fas fa-times-circle"></i> Error: ' + error.message + '</span>';
                })
                .finally(() => {
                    btnPilihFoto.disabled = false;
                    fileInput.value = ''; // Reset file input
                });
            });
        }

        if (btnCopyPath) {
            btnCopyPath.addEventListener('click', function() {
                navigator.clipboard.writeText(uploadedPathCode.textContent)
                    .then(() => {
                        const originalText = btnCopyPath.innerHTML;
                        btnCopyPath.innerHTML = '<i class="fas fa-check"></i> Disalin!';
                        setTimeout(() => {
                            btnCopyPath.innerHTML = originalText;
                        }, 2000);
                    });
            });
        }
        @endif
    });
</script>
@endsection
