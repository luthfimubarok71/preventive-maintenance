@extends('layouts.bar')

@section('content')
    <div class="container">

        <h2 class="mb-4">Approval Laporan Inspeksi (Kepala RO)</h2>

        {{-- SUCCESS MESSAGE --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ERROR MESSAGE --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">

            <div class="card-body">

                <table class="table table-bordered table-striped">

                    <thead class="table-dark">
                        <tr>
                            <th>Segment</th>
                            <th>Tanggal</th>
                            <th>Teknisi</th>
                            <th>Jalur</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($reports as $report)
                            <tr>

                                <td>{{ $report->segment_inspeksi }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($report->tanggal_inspeksi)->format('d F Y') }}
                                </td>

                                <td>{{ $report->nama_pelaksana }}</td>

                                <td>{{ ucfirst(str_replace('_', ' ', $report->jalur_fo)) }}</td>

                                <td>

                                    @if ($report->status_workflow == 'pending_ro')
                                        <span class="badge bg-warning text-dark">
                                            Pending RO
                                        </span>
                                    @endif

                                    @if ($report->status_workflow == 'pending_pusat')
                                        <span class="badge bg-primary">
                                            Pending Pusat
                                        </span>
                                    @endif

                                    @if ($report->status_workflow == 'approved')
                                        <span class="badge bg-success">
                                            Approved
                                        </span>
                                    @endif

                                    @if ($report->status_workflow == 'rejected')
                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>
                                    @endif

                                </td>





                                {{-- APPROVE --}}
                                <td>

                                    <button class="btn btn-sm btn-primary view-report" data-id="{{ $report->id }}">
                                        View
                                    </button>

                                </td>





                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="text-center">
                                    Tidak ada laporan menunggu approval
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>
        <div class="modal fade" id="reportModal" tabindex="-1">

            <div class="modal-dialog modal-xl">

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Detail Laporan Inspeksi</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="reportContent">

                        Loading...

                    </div>

                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            $(document).on('click', '.view-report', function() {

                let id = $(this).data('id');

                let modal = new bootstrap.Modal(document.getElementById('reportModal'));
                modal.show();

                $('#reportContent').html('Loading...');

                $.ajax({
                    url: '/report/modal/' + id,
                    type: 'GET',
                    success: function(data) {
                        $('#reportContent').html(data);
                    },
                    error: function() {
                        $('#reportContent').html('<div class="text-danger">Gagal memuat laporan</div>');
                    }
                });

            });
        </script>
    @endpush
@endsection
