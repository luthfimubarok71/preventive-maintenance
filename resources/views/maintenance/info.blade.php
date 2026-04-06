@extends('layouts.bar')

@section('content')
    <div class="container">

        <h2 class="mb-4">Informasi Maintenance</h2>

        <table class="table table-bordered table-striped">

            <thead class="table-dark">
                <tr>
                    <th>Segment</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($segments as $segment)
                    @foreach ($segment->schedules as $schedule)
                        <tr>

                            <td>{{ $segment->nama_segment }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($schedule->planned_date)->format('d F Y') }}
                            </td>

                            <td>

                                @if (!$schedule->inspeksiHeader)
                                    <span class="badge bg-success">Belum Dikerjakan</span>
                                @elseif ($schedule->inspeksiHeader->status_workflow == 'draft')
                                    <span class="badge bg-warning">Draft</span>
                                @elseif ($schedule->inspeksiHeader->status_workflow == 'pending_ro')
                                    <span class="badge bg-info">Pending RO</span>
                                @elseif ($schedule->inspeksiHeader->status_workflow == 'pending_pusat')
                                    <span class="badge bg-primary">Pending Pusat</span>
                                @elseif ($schedule->inspeksiHeader->status_workflow == 'approved')
                                    <span class="badge bg-secondary">Selesai</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif

                            </td>

                            <td>

                                @if ($schedule->inspeksiHeader)
                                    <button class="btn btn-sm btn-primary view-report"
                                        data-id="{{ $schedule->inspeksiHeader->id }}">
                                        View
                                    </button>
                                @else
                                    <span class="text-muted">Belum ada laporan</span>
                                @endif

                            </td>

                        </tr>
                    @endforeach
                @endforeach

            </tbody>
        </table>


        <!-- MODAL -->
        <div class="modal fade" id="reportModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Detail Laporan Inspeksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="reportContent">
                        Loading...
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).on('click', '.view-report', function() {

            let id = $(this).data('id');

            $('#reportContent').html('Loading...');

            $('#reportModal').modal('show');

            $.get('/report/modal/' + id, function(data) {

                $('#reportContent').html(data);

            });

        });
    </script>
@endpush
