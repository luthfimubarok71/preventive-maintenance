@extends('layouts.bar')

@push('style')
    <style>
        /* FILTER BAR */
        .pm-filter-bar {
            background: var(--card);
            border-radius: 14px;
            padding: 16px 18px;
            margin-bottom: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 10px 30px rgba(37, 99, 235, 0.1);
        }

        .pm-filter-left {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pm-filter-select {
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 8px 12px;
            background: white;
            min-width: 200px;
        }

        /* BUTTON */
        .pm-filter-apply {
            background: linear-gradient(135deg, #60a5fa, #2563eb);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 600;
        }

        .pm-filter-reset {
            background: #e2e8f0;
            border: none;
            border-radius: 10px;
            padding: 8px 18px;
            font-weight: 500;
            color: #334155;
        }

        .pm-filter-reset:hover {
            background: #cbd5f5;
        }

        /* WRAPPER */
        .fmea-wrapper {
            padding-top: 20px;
        }

        .fmea-card {
            background: var(--card);
            border-radius: 18px;
            padding: 28px;
            box-shadow: 0 20px 50px rgba(37, 99, 235, 0.15);
        }

        /* HEADER */
        .fmea-header h4 {
            font-weight: 700;
            margin-bottom: 4px;
        }

        .fmea-header p {
            color: var(--muted);
            font-size: 14px;
        }

        /* TABLE */
        .table-modern table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-modern th {
            text-align: left;
            font-size: 13px;
            color: var(--muted);
            padding-bottom: 10px;
        }

        .table-modern td {
            padding: 14px 0;
            border-top: 1px solid #e2e8f0;
        }

        .table-modern tr:hover {
            background: rgba(37, 99, 235, 0.05);
        }

        /* BUTTON */
        .btn-outline-modern {
            border: 1px solid #2563eb;
            color: #2563eb;
            border-radius: 8px;
        }

        .btn-outline-modern:hover {
            background: #2563eb;
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="fmea-wrapper">
        <div class="fmea-card">

            <div class="fmea-header">
                <h4>Laporan Menunggu Approval Pusat</h4>
                <p>Daftar laporan yang menunggu persetujuan pusat</p>
            </div>
            <form method="GET" class="mb-3">
                <div class="pm-filter-bar">

                    <form method="GET" style="display:flex; width:100%; justify-content:space-between; align-items:center;">

                        <div class="pm-filter-left">

                            {{-- FILTER REGIONAL --}}
                            <select name="regional" class="pm-filter-select">
                                <option value="">Semua Regional</option>

                                @foreach ($regionals as $reg)
                                    <option value="{{ $reg->id }}"
                                        {{ request('regional') == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->nama_regional }}
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div style="display:flex; gap:10px;">

                            <button class="pm-filter-apply">
                                <i class="bi bi-funnel"></i> Terapkan
                            </button>

                            <a href="{{ url()->current() }}" class="pm-filter-reset">
                                Reset
                            </a>

                        </div>

                    </form>

                </div>
            </form>
            <div class="table-modern">
                <table>
                    <thead>
                        <tr>
                            <th>Regional</th>
                            <th>Segment</th>
                            <th>Tanggal</th>
                            <th>Pelaksana</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($reports as $r)
                            <tr>
                                <td>{{ $r->creator->regional->nama_regional ?? '-' }}</td>
                                <td>{{ $r->segment_inspeksi }}</td>

                                <td>{{ \Carbon\Carbon::parse($r->tanggal_inspeksi)->format('d F Y') }}</td>

                                <td>{{ $r->nama_pelaksana }}</td>

                                <td>
                                    <button class="btn btn-sm btn-outline-modern view-report" data-id="{{ $r->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL --}}
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Laporan</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="reportContent">
                    Loading...
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '.view-report', function() {

            let id = $(this).data('id');

            let modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();

            $('#reportContent').html('Loading...');

            let url = "{{ route('report.modal', ['id' => 'ID']) }}".replace('ID', id);

            $.get(url, function(data) {
                $('#reportContent').html(data);
            });

        });
    </script>
@endpush
