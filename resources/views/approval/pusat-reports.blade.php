@extends('layouts.bar')

@section('content')
    <h2>Laporan Menunggu Approval Pusat</h2>

    <table class="table table-bordered">

        <tr>
            <th>Segment</th>
            <th>Tanggal</th>
            <th>Pelaksana</th>
            <th>Aksi</th>
        </tr>

        @foreach ($reports as $r)
            <tr>

                <td>{{ $r->segment_inspeksi }}</td>

                <td>{{ \Carbon\Carbon::parse($r->tanggal_inspeksi)->format('d F Y') }}</td>

                <td>{{ $r->nama_pelaksana }}</td>

                <td>

                    <button class="btn btn-primary btn-sm view-report" data-id="{{ $r->id }}">
                        View
                    </button>

                </td>

            </tr>
        @endforeach

    </table>


    {{-- MODAL --}}
    <div class="modal fade" id="reportModal">

        <div class="modal-dialog modal-xl">

            <div class="modal-content">

                <div class="modal-header">

                    <h5>Detail Laporan</h5>

                    <button class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body" id="reportContent">

                    Loading...

                </div>

            </div>

        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).on('click', '.view-report', function() {

            let id = $(this).data('id');

            let modal = new bootstrap.Modal(document.getElementById('reportModal'));

            modal.show();

            $('#reportContent').html('Loading...');

            let url = "{{ route('report.modal', 'ID') }}".replace('ID', id);

            $.get(url, function(data) {

                $('#reportContent').html(data);

            });

        });
    </script>
@endsection
