@extends('layouts.app-student')

@section('title', 'Input Data Belajar - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <style>
        .input-container {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .input-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .input-header h2 {
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .input-header p {
            color: #64748b;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 13.5px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .form-label .text-danger {
            margin-left: 2px;
        }
        .form-control, .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 14px;
            color: #0f172a;
            background-color: #f8fafc;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-control::placeholder {
            color: #94a3b8;
        }
        .form-control:hover, .form-select:hover {
            border-color: #94a3b8;
            background-color: #f1f5f9;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }
        .btn-primary-custom {
            background: #2563eb;
            border: 1px solid #2563eb;
            color: white;
            padding: 12px 32px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn-primary-custom:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.12), 0 2px 4px -1px rgba(37, 99, 235, 0.08);
        }
        .btn-primary-custom:active {
            transform: translateY(0);
        }
        @media (max-width: 576px) {
            .input-container {
                padding: 15px;
            }
            .input-header {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            .input-header h2 {
                font-size: 18px;
            }
            .input-header p {
                font-size: 12px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="input-container container-fluid">
        <div class="input-header">
            <h2><i class="fas fa-pencil-alt me-2" style="color:#2563eb;"></i>Input Data Belajar</h2>
            <p>Lengkapi data belajar mandiri Anda untuk dianalisis oleh sistem Random Forest dan memperoleh rekomendasi belajar personal.</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px; font-weight:500;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert" style="border-radius:8px; font-weight:500;">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px; font-weight:500;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($pendingRecords->isEmpty())
            <div class="text-center p-5 border rounded-3 bg-light" style="border-style: dashed !important; border-color: #cbd5e1 !important; border-radius: 16px !important;">
                <i class="fas fa-clipboard-list mb-3" style="font-size: 3rem; color: #94a3b8;"></i>
                <h4 class="fw-bold" style="color: #334155;">Belum Ada Hasil Belajar dari Admin</h4>
                <p class="text-muted mb-2">Administrator belum menginput nilai tugas, nilai kuis, atau kehadiran Anda.</p>
                <p class="text-muted small">Silakan hubungi tutor atau admin Anda untuk menginput data nilai terlebih dahulu agar Anda dapat melengkapinya dan menerima rekomendasi.</p>
                <div class="mt-4">
                    <a href="{{ route('siswa.riwayat') }}" class="btn btn-outline-primary px-4" style="border-radius: 8px; font-weight: 600;">Lihat Riwayat Belajar</a>
                </div>
            </div>
        @else
            <form action="{{ route('learning.store') }}" method="POST" id="form-input-belajar">
                @csrf

                <div class="row">
                    {{-- Program Belajar Pending --}}
                    <div class="col-md-12 mb-4">
                        <label for="learning_data_id" class="form-label">
                            Pilih Program Belajar yang Ingin Dilengkapi <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('learning_data_id') is-invalid @enderror"
                                id="learning_data_id" name="learning_data_id" required style="padding:14px; font-size:15px; border-radius:10px; border:1px solid #cbd5e1; background-color:#eff6ff;">
                            <option value="">-- Pilih Program Belajar --</option>
                            @foreach ($pendingRecords as $record)
                                <option value="{{ $record->id }}"
                                        data-tugas="{{ $record->nilai_tugas }}"
                                        data-kuis="{{ $record->nilai_kuis }}"
                                        data-rerata="{{ number_format($record->nilai, 1) }}"
                                        data-kehadiran="{{ $record->kehadiran }}">
                                    {{ $record->mata_pelajaran }} (Diinput Admin: {{ \Carbon\Carbon::parse($record->created_at)->translatedFormat('d M Y') }})
                                </option>
                            @endforeach
                        </select>
                        @error('learning_data_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row Data Akademik dari Admin (Read-only Card) --}}
                <div class="card p-4 mb-4 animate-fade-in" id="academic-data-card" style="display: none; border-radius: 12px; background: #f8fafc; border: 1px solid #e2e8f0;">
                    <h5 class="fw-bold mb-3" style="font-size: 15px; color: #1e293b;"><i class="fas fa-user-shield me-2 text-primary"></i>Data Akademik (Diinput oleh Admin)</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label" style="font-size: 12px; color: #64748b; text-transform: uppercase;">Nilai Tugas</label>
                            <input type="text" id="display_nilai_tugas" class="form-control bg-white text-dark fw-bold" readonly style="border-color:#e2e8f0; font-size: 15px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" style="font-size: 12px; color: #64748b; text-transform: uppercase;">Nilai Kuis</label>
                            <input type="text" id="display_nilai_kuis" class="form-control bg-white text-dark fw-bold" readonly style="border-color:#e2e8f0; font-size: 15px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" style="font-size: 12px; color: #64748b; text-transform: uppercase;">Rata-rata Nilai</label>
                            <input type="text" id="display_nilai_rerata" class="form-control bg-white text-dark fw-bold text-primary" readonly style="border-color:#e2e8f0; font-size: 15px;">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" style="font-size: 12px; color: #64748b; text-transform: uppercase;">Kehadiran Kelas</label>
                            <input type="text" id="display_kehadiran" class="form-control bg-white text-dark fw-bold" readonly style="border-color:#e2e8f0; font-size: 15px;">
                        </div>
                    </div>
                </div>

                {{-- Row Data Sekunder Input Siswa --}}
                <div id="student-input-section" style="display: none;">
                    <div class="row">
                        {{-- Study Duration --}}
                        <div class="col-md-6 mb-3">
                            <label for="study_duration" class="form-label">
                                Durasi Belajar Mandiri (Jam/Hari) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control @error('study_duration') is-invalid @enderror"
                                   id="study_duration" name="study_duration"
                                   min="1" max="5" step="0.5"
                                   placeholder="Masukkan durasi belajar mandiri (1–5 jam)"
                                   value="{{ old('study_duration') }}"
                                   required>
                            @error('study_duration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Tingkat Kesulitan --}}
                        <div class="col-md-6 mb-3">
                            <label for="tingkat_kesulitan" class="form-label">
                                Tingkat Kesulitan Materi <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('tingkat_kesulitan') is-invalid @enderror"
                                    id="tingkat_kesulitan" name="tingkat_kesulitan" required>
                                <option value="">-- Pilih Tingkat Kesulitan --</option>
                                <option value="Mudah"  {{ old('tingkat_kesulitan') === 'Mudah'  ? 'selected' : '' }}>Mudah</option>
                                <option value="Sedang" {{ old('tingkat_kesulitan') === 'Sedang' ? 'selected' : '' }}>Sedang</option>
                                <option value="Sulit"  {{ old('tingkat_kesulitan') === 'Sulit'  ? 'selected' : '' }}>Sulit</option>
                            </select>
                            @error('tingkat_kesulitan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-4">
                        <label for="catatan" class="form-label">Catatan Tambahan (Opsional)</label>
                        <textarea class="form-control @error('catatan') is-invalid @enderror"
                                  id="catatan" name="catatan" rows="3"
                                  maxlength="1000"
                                  placeholder="Tuliskan topik yang dipelajari, kendala yang dihadapi, atau catatan lainnya...">{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted"><span class="text-danger">*</span> Wajib diisi</small>
                        <button type="submit" class="btn btn-primary-custom" id="btn-submit">
                            <i class="fas fa-brain me-2"></i> Dapatkan Rekomendasi Belajar
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectRecord = document.getElementById('learning_data_id');
            const academicCard = document.getElementById('academic-data-card');
            const studentSection = document.getElementById('student-input-section');

            const valTugas = document.getElementById('display_nilai_tugas');
            const valKuis = document.getElementById('display_nilai_kuis');
            const valRerata = document.getElementById('display_nilai_rerata');
            const valKehadiran = document.getElementById('display_kehadiran');

            selectRecord.addEventListener('change', function() {
                const selectedOption = selectRecord.options[selectRecord.selectedIndex];
                
                if (selectedOption.value) {
                    // Populate values
                    valTugas.value = selectedOption.getAttribute('data-tugas') + ' / 100';
                    valKuis.value = selectedOption.getAttribute('data-kuis') + ' / 100';
                    valRerata.value = selectedOption.getAttribute('data-rerata') + ' / 100';
                    
                    const kehadiranVal = selectedOption.getAttribute('data-kehadiran');
                    valKehadiran.value = kehadiranVal;
                    
                    // Style Kehadiran Badge/Input
                    if (kehadiranVal === 'Baik') {
                        valKehadiran.style.color = '#059669';
                    } else if (kehadiranVal === 'Cukup') {
                        valKehadiran.style.color = '#d97706';
                    } else {
                        valKehadiran.style.color = '#dc2626';
                    }

                    // Show sections
                    academicCard.style.display = 'block';
                    studentSection.style.display = 'block';
                } else {
                    // Hide sections
                    academicCard.style.display = 'none';
                    studentSection.style.display = 'none';
                }
            });
        });
    </script>
@endsection
