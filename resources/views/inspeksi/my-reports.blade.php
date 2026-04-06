@extends('layouts.bar')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">📋 Laporan Inspeksi Saya</h4>
                    <p class="text-muted mb-0">Daftar laporan inspeksi yang telah Anda buat</p>
                </div>
                <div class="card-body">
                    @if($reports->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Belum ada laporan inspeksi yang Anda buat.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Segment</th>
                                        <th>Tanggal Inspeksi</th>
                                        <th>Priority</th>
                                        <th>Status Workflow</th>
                                        <th>Progress Approval</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr>
                                        <td>{{ $report->segment_inspeksi }}</td>
                                        <td>{{ \Carbon\Carbon::parse($report->tanggal_inspeksi)->format('d-m-Y') }}</td>
                                        <td>
                                            @if($report->priority === 'KRITIS')
                                                <span class="badge bg-danger">KRITIS</span>
                                            @elseif($report->priority === 'SEDANG')
                                                <span class="badge bg-warning text-dark">SEDANG</span>
                                            @else
                                                <span class="badge bg-success">RENDAH</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($report->status_workflow)
                                                @case('draft')
                                                    <span class="badge bg-secondary">Belum dikirim</span>
                                                    @break
                                                @case('pending_ro')
                                                    <span class="badge bg-primary">Menunggu Kepala RO</span>
                                                    @break
                                                @case('pending_pusat')
                                                    <span class="badge bg-info text-dark">Menunggu Pusat</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $report->status_workflow }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @php
                                                $approvals = $report->approvals;
                                                $approvedCount = $approvals->where('status', 'approved')->count();
                                                $totalSteps = 2; // Kepala RO and Pusat
                                                $progress = ($approvedCount / $totalSteps) * 100;
                                            @endphp
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-success" role="progressbar" 
                                                     style="width: {{ $progress }}%" 
                                                     aria-valuenow="{{ $progress }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                    {{ $approvedCount }}/{{ $totalSteps }}
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                @if($report->status_workflow === 'draft')
                                                    Belum submission
                                                @elseif($report->status_workflow === 'pending_ro')
                                                    Menunggu persetujuan Kepala RO
                                                @elseif($report->status_workflow === 'pending_pusat')
                                                    Menunggu persetujuan Pusat
                                                @elseif($report->status_workflow === 'approved')
                                                    Selesai - Fully Approved
                                                @elseif($report->status_workflow === 'rejected')
                                                    Ditolak
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <a href="{{ route('hasilfmea', $report->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
