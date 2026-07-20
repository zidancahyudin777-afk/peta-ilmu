@extends('layouts.app-student')

@section('title', 'Riwayat Prediksi - Bimbingan Belajar Peta Ilmu')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .riwayat-container {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }
        .riwayat-header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 28px;
        }
        .riwayat-header h2 {
            color: #0f172a;
            font-size: 22px;
            font-weight: 700;
            margin: 0;
        }
        .riwayat-header p {
            color: #64748b;
            font-size: 14px;
            margin: 5px 0 0 0;
        }
        /* Category badges */
        .badge-kategori {
            font-size: 11.5px;
            font-weight: 600;
            padding: 5px 11px;
            border-radius: 20px;
            border: 1px solid;
            white-space: nowrap;
        }
        .badge-remedial   { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
        .badge-pendampingan { background:#ffedd5; color:#9a3412; border-color:#fdba74; }
        .badge-reguler    { background:#dbeafe; color:#1e40af; border-color:#93c5fd; }
        .badge-pengayaan  { background:#dcfce7; color:#14532d; border-color:#86efac; }
        /* Detail button */
        .btn-detail {
            background: #2563eb;
            color: #fff;
            border: 1px solid #2563eb;
            border-radius: 6px;
            padding: 5px 13px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn-detail:hover { background: #1d4ed8; border-color: #1d4ed8; color: #fff; }
        /* DataTable overrides */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0 !important;
            margin: 0 !important;
        }
        .page-link { border-radius: 6px; margin: 0 2px; }
        .btn-primary-custom {
            background: #2563eb;
            border: 1px solid #2563eb;
            color: white;
            padding: 9px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-block;
        }
        .btn-primary-custom:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            color: white;
        }
        .dataTables_filter input {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 13.5px;
            outline: none;
        }
        .dataTables_filter input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .dataTables_length select {
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px 8px;
            font-size: 13.5px;
            outline: none;
        }
        @media (max-width: 576px) {
            .riwayat-container {
                padding: 15px;
            }
            .riwayat-header h2 {
                font-size: 18px;
            }
            .riwayat-header p {
                font-size: 12px;
            }
            .btn-primary-custom {
                width: 100%;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="riwayat-container container-fluid">
        <div class="riwayat-header d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h2><i class="fas fa-history me-2" style="color:#2563eb;"></i>Riwayat Prediksi Pembelajaran</h2>
                <p>Semua hasil prediksi Random Forest beserta rekomendasi belajar personal Anda</p>
            </div>
            <a href="{{ route('siswa.input') }}" class="btn-primary-custom">
                <i class="fas fa-plus me-1"></i> Input Data Baru
            </a>
        </div>

        <div class="table-responsive">
            <table id="riwayatTable" class="table table-hover align-middle" style="width:100%">
                <thead>
                    <tr class="table-light text-secondary" style="font-size:13px; font-weight:600;">
                        <th>Tanggal</th>
                        <th>Program Belajar</th>
                        <th>Nilai Rata-rata</th>
                        <th>Tingkat Kesulitan</th>
                        <th>Hasil Rekomendasi</th>
                        <th>Confidence</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size:13.5px; color:#4a5568;">
                    @foreach ($learningData as $data)
                        <tr>
                            {{-- Tanggal --}}
                            <td class="text-nowrap" style="font-size:12.5px; color:#64748b;">
                                {{ $data->prediction_date
                                    ? $data->prediction_date->translatedFormat('d M Y, H:i')
                                    : ($data->created_at ? $data->created_at->translatedFormat('d M Y, H:i') : '-') }}
                            </td>

                            {{-- Program Belajar --}}
                            <td class="fw-semibold text-dark">
                                <i class="fas fa-book-open me-1" style="color:#2563eb; font-size:12px;"></i>
                                {{ $data->mata_pelajaran }}
                            </td>

                            {{-- Nilai Rerata --}}
                            <td>
                                <span class="fw-bold {{ $data->nilai >= 80 ? 'text-success' : ($data->nilai >= 60 ? 'text-warning' : 'text-danger') }}">
                                    {{ number_format($data->nilai, 1) }}
                                </span>
                            </td>

                            {{-- Tingkat Kesulitan --}}
                            <td>
                                <span class="badge rounded-pill px-2 py-1
                                    {{ $data->tingkat_kesulitan === 'Sulit'  ? 'bg-danger-subtle text-danger' :
                                       ($data->tingkat_kesulitan === 'Sedang' ? 'bg-warning-subtle text-warning' : 'bg-success-subtle text-success') }}"
                                    style="font-size:12px;">
                                    {{ $data->tingkat_kesulitan }}
                                </span>
                            </td>

                            {{-- Kategori Rekomendasi --}}
                            <td>
                                @if ($data->recommendation_result === 'Program Remedial Intensif' || $data->recommendation_result === 'Dasar')
                                    <span class="badge-kategori badge-remedial">
                                        <i class="fas fa-exclamation-triangle me-1"></i>Dasar
                                    </span>
                                @elseif ($data->recommendation_result === 'Pendampingan Akademik' || $data->recommendation_result === 'Menengah' || $data->recommendation_result === 'Program Reguler')
                                    <span class="badge-kategori badge-pendampingan">
                                        <i class="fas fa-hands-helping me-1"></i>Menengah
                                    </span>
                                @elseif ($data->recommendation_result === 'Program Pengayaan' || $data->recommendation_result === 'Mahir')
                                    <span class="badge-kategori badge-pengayaan">
                                        <i class="fas fa-star me-1"></i>Mahir
                                    </span>
                                @else
                                    <span class="badge bg-secondary-subtle text-secondary" style="font-size:12px;">
                                        Belum Dianalisis
                                    </span>
                                @endif
                            </td>

                            {{-- Confidence --}}
                            <td>
                                @if ($data->confidence)
                                    <span class="fw-semibold text-dark">{{ number_format($data->confidence, 2) }}%</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Tombol Detail --}}
                            <td class="text-center">
                                <a href="{{ route('siswa.rekomendasi.show', $data->id) }}" class="btn-detail">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#riwayatTable').DataTable({
                "language": {
                    "emptyTable":    "Belum ada riwayat prediksi.",
                    "info":          "Menampilkan _START_ – _END_ dari _TOTAL_ data",
                    "infoEmpty":     "Menampilkan 0 data",
                    "infoFiltered":  "(difilter dari _MAX_ total data)",
                    "lengthMenu":    "Tampilkan _MENU_ data",
                    "loadingRecords":"Memuat...",
                    "processing":    "Memproses...",
                    "search":        "Cari:",
                    "zeroRecords":   "Data tidak ditemukan.",
                    "paginate": {
                        "first":    "Pertama",
                        "last":     "Terakhir",
                        "next":     "›",
                        "previous": "‹"
                    }
                },
                "order":      [[0, "desc"]],
                "columnDefs": [{ "orderable": false, "targets": [4, 5, 6] }],
                "pageLength": 10,
                "lengthMenu": [5, 10, 25, 50]
            });
        });
    </script>
@endsection
