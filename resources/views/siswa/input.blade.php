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

    </style>
@endsection

@section('content')
    <div class="input-container container-fluid">
        <div class="input-header">
            <h2><i class="fas fa-pencil-alt me-2" style="color:#2563eb;"></i>Input Data Belajar</h2>
            <p>Masukkan data belajar Anda untuk dianalisis oleh sistem Random Forest dan mendapatkan rekomendasi personal</p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:8px; font-weight:500;">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:8px; font-weight:500;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('learning.store') }}" method="POST" id="form-input-belajar">
            @csrf

            <div class="row">
                {{-- Mata Pelajaran --}}
                <div class="col-md-6 mb-3">
                    <label for="mata_pelajaran" class="form-label">
                        Mata Pelajaran <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('mata_pelajaran') is-invalid @enderror"
                            id="mata_pelajaran" name="mata_pelajaran" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaranList as $mapel)
                            <option value="{{ $mapel }}" {{ old('mata_pelajaran') === $mapel ? 'selected' : '' }}>
                                {{ $mapel }}
                            </option>
                        @endforeach
                    </select>
                    @error('mata_pelajaran')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nilai --}}
                <div class="col-md-6 mb-3">
                    <label for="nilai" class="form-label">
                        Nilai <span class="text-danger">*</span>
                    </label>
                    <input type="number"
                           class="form-control @error('nilai') is-invalid @enderror"
                           id="nilai" name="nilai"
                           min="0" max="100" step="1"
                           placeholder="Masukkan nilai 0–100"
                           value="{{ old('nilai') }}"
                           required>
                    @error('nilai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
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

                {{-- Gaya Belajar --}}
                <div class="col-md-6 mb-3">
                    <label for="gaya_belajar" class="form-label">
                        Gaya Belajar <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('gaya_belajar') is-invalid @enderror"
                            id="gaya_belajar" name="gaya_belajar" required>
                        <option value="">-- Pilih Gaya Belajar --</option>
                        <option value="Visual"     {{ old('gaya_belajar') === 'Visual'     ? 'selected' : '' }}>Visual</option>
                        <option value="Audio"      {{ old('gaya_belajar') === 'Audio'      ? 'selected' : '' }}>Audio</option>
                        <option value="Kinestetik" {{ old('gaya_belajar') === 'Kinestetik' ? 'selected' : '' }}>Kinestetik</option>
                    </select>
                    @error('gaya_belajar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Catatan --}}
            <div class="mb-4">
                <label for="catatan" class="form-label">Catatan Tambahan</label>
                <textarea class="form-control @error('catatan') is-invalid @enderror"
                          id="catatan" name="catatan" rows="3"
                          maxlength="1000"
                          placeholder="Tuliskan topik yang dipelajari, kendala yang dihadapi, atau catatan lainnya...">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit --}}
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted"><span class="text-danger">*</span> Wajib diisi</small>
                <button type="submit" class="btn btn-primary-custom" id="btn-submit">
                    <i class="fas fa-brain me-2"></i> Analisis & Dapatkan Rekomendasi
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        // Tampilkan loading state saat form disubmit
        document.getElementById('form-input-belajar').addEventListener('submit', function () {
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Menganalisis...';
        });
    </script>
@endsection
