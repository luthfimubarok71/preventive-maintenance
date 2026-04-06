@extends('layouts.bar')

@section('title', 'Dashboard Teknisi')

@section('content')

    <style>
        .card:hover {
            transform: translateY(-3px);
            transition: 0.2s;
        }
    </style>
    <div class="container mt-4" style="background:#eff6ff; padding:20px; border-radius:12px;">

        <h5 class="mb-1 text-muted">Halo, {{ Auth::user()->name }} 👋</h5>
        <h2 class="mb-4 fw-semibold text-dark">Dashboard Teknisi</h2>

        <!-- CARD STATISTIK -->
        <div class="row g-3">

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Tugas</p>
                        <h3 class="fw-bold">{{ $totalTugas }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Tugas Hari Ini</p>
                        <h3 class="fw-bold">{{ $tugasHariIni }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Belum Selesai</p>
                        <h3 class="fw-bold">{{ $belumSelesai }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                    <div class="card-body">
                        <p class="text-muted mb-1">Selesai</p>
                        <h3 class="fw-bold">{{ $selesai }}</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- TUGAS PRIORITAS -->
        <div class="card shadow-sm mt-4 border-0">
            <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                <h6 class="mb-0">🔥 Tugas Prioritas (KRITIS)</h6>
            </div>
            <div class="card-body">

                <table class="table table-borderless align-middle">
                    <thead style="background:#f3f4f6;">
                        <tr>
                            <th>Segment</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @php
                            $kritikal = $tugas->where('priority', 'KRITIS');
                        @endphp

                        @forelse($kritikal as $item)
                            <tr style="background:#fef2f2;">
                                <td>{{ $item->segment_inspeksi }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_inspeksi)->format('d M Y') }}</td>
                                <td>
                                    <span style="background:#fee2e2; color:#991b1b; padding:4px 10px; border-radius:6px;">
                                        KRITIS
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Tidak ada tugas kritis
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

        <!-- TABEL UTAMA -->
        <div class="card shadow-sm mt-4 border-0">
            <div class="card border-0 shadow-sm" style="background:white; border-left:4px solid #2563eb;">
                <h6 class="mb-0">Daftar Tugas Teknisi</h6>
            </div>
            <div class="card-body">

                <table class="table table-hover align-middle">
                    <thead style="background:#f3f4f6;">
                        <tr>
                            <th>Tanggal</th>
                            <th>Segment</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Prioritas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($tugas as $item)
                            <tr class="{{ $item->priority == 'KRITIS' ? 'table-danger' : '' }}">

                                <td>{{ \Carbon\Carbon::parse($item->tanggal_inspeksi)->format('d M Y') }}</td>
                                <td>{{ $item->segment_inspeksi }}</td>
                                <td>{{ $item->jalur_fo }}</td>

                                <td>
                                    @if ($item->status_workflow == 'approved')
                                        <span
                                            style="background:#dcfce7; color:#166534; padding:4px 10px; border-radius:6px;">
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:6px;">
                                            Proses
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    @if ($item->priority == 'KRITIS')
                                        <span
                                            style="background:#fee2e2; color:#991b1b; padding:4px 10px; border-radius:6px;">
                                            KRITIS
                                        </span>
                                    @elseif($item->priority == 'SEDANG')
                                        <span
                                            style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:6px;">
                                            SEDANG
                                        </span>
                                    @else
                                        <span
                                            style="background:#e5e7eb; color:#374151; padding:4px 10px; border-radius:6px;">
                                            RENDAH
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a href="#" class="btn btn-sm" style="background:#e0e7ff; color:#3730a3;">
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Tidak ada tugas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection
