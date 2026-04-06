@extends('layouts.bar')

@section('content')
    <div class="container">
        <h1>Pending Schedules</h1>
        {{-- ================= ALERT SUCCESS ================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ================= ALERT ERROR (VALIDATION) ================= --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi Kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="pm-filter-bar">

            <div class="pm-filter-left">



                <select id="filterPriority" class="pm-filter-select">
                    <option value="">Prioritas</option>
                    <option value="KRITIS">KRITIS</option>
                    <option value="SEDANG">SEDANG</option>
                    <option value="RENDAH">RENDAH</option>
                </select>

                <select id="sortDate" class="pm-filter-select">
                    <option value="">Sort by Date</option>
                    <option value="asc">Tanggal Terdekat</option>
                    <option value="desc">Tanggal Terjauh</option>
                </select>

                <select id="filterPIC" class="pm-filter-select">
                    <option value="">PIC</option>

                    @foreach ($schedules as $group)
                        @php $first = $group->first(); @endphp
                        <option value="{{ $first->creator->username }}">
                            {{ $first->creator->username }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="pm-filter-right">
                <button id="applyFilter" class="pm-filter-apply">
                    <i class="fas fa-filter"></i> Terapkan
                </button>
            </div>

        </div>

        <table class="table table-striped" id="pendingTable">
            <thead>
                <tr>
                    <th>Segment</th>
                    <th>Date</th>
                    <th>Diajukan oleh</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($schedules as $group)
                    @php
                        $first = $group->first();
                    @endphp


                    <tr>
                        <td>{{ $first->segment->nama_segment }}</td>
                        <td>{{ \Carbon\Carbon::parse($first->planned_date)->translatedFormat('F Y') }}</td>
                        <td>{{ $first->creator->username }}</td>
                        <td>{{ $first->priority }}</td>
                        <td><span class="badge bg-warning">Pending</span></td>

                        <td>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#approveModal{{ $first->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @foreach ($schedules as $group)
        @php
            $first = $group->first();
        @endphp

        <div class="modal fade" id="approveModal{{ $first->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detail Jadwal PM</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <p>
                            <b>Segment:</b>
                            {{ $first->segment->kode_segment }} -
                            {{ $first->segment->nama_segment }}
                        </p>

                        <p>
                            <b>Bulan:</b>
                            {{ \Carbon\Carbon::parse($first->planned_date)->translatedFormat('F Y') }}
                        </p>

                        <p><b>Prioritas:</b> {{ $first->priority }}</p>

                        <p><b>Dibuat oleh:</b> {{ $first->creator->username }}</p>

                        <hr>

                        <b>Tanggal Jadwal</b>

                        <div class="mt-2">

                            @foreach ($group->sortBy('planned_date') as $item)
                                <span class="badge bg-primary me-1 mb-1">

                                    {{ \Carbon\Carbon::parse($item->planned_date)->format('d') }}

                                </span>
                            @endforeach

                        </div>

                        <hr>

                        <b>Tanda Tangan Teknisi</b><br>

                        @if ($first->signature_teknisi)
                            <img src="{{ asset('storage/' . $first->signature_teknisi) }}" style="max-height:120px">
                        @endif

                        <hr>

                        <b>Tanda Tangan Kepala RO</b>

                        <canvas id="signature-pad-{{ $first->id }}"
                            style="border:1px solid #ccc;width:100%;height:150px"></canvas>
                    </div>

                    <div class="modal-footer">

                        <form action="{{ route('approval.reject-group') }}" method="POST">
                            @csrf
                            <input type="hidden" name="group_id"
                                value="{{ $first->segment_id }}|{{ date('Y-m', strtotime($first->planned_date)) }}">
                            <button class="btn btn-danger">Reject</button>
                        </form>

                        <form action="{{ route('approval.approve-group') }}" method="POST"
                            onsubmit="saveSignature({{ $first->id }})">

                            @csrf

                            <input type="hidden" name="signature_ro" id="signature-input-{{ $first->id }}">

                            <input type="hidden" name="group_id"
                                value="{{ $first->segment_id }}|{{ date('Y-m', strtotime($first->planned_date)) }}">

                            <button class="btn btn-success">Approve</button>

                        </form>



                        </form>

                    </div>

                </div>
            </div>
        </div>
    @endforeach
    <style>
        .pm-filter-bar {
            background: #f5f7fa;
            border-radius: 12px;
            padding: 15px 18px;
            margin-bottom: 20px;

            display: flex;
            justify-content: space-between;
            align-items: center;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .pm-filter-left {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pm-filter-btn {
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 7px 14px;
            font-size: 14px;
        }

        .pm-filter-select {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 7px 12px;
            font-size: 14px;
            background: white;
        }

        .pm-filter-apply {
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 18px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <script>
        document.getElementById("applyFilter").addEventListener("click", function() {

            let priority = document.getElementById("filterPriority").value;
            let pic = document.getElementById("filterPIC").value;

            let rows = document.querySelectorAll("#pendingTable tbody tr");

            rows.forEach(row => {

                let rowPriority = row.children[3].innerText.trim();
                let rowPIC = row.children[2].innerText.trim();

                let show = true;

                if (priority && !rowPriority.includes(priority)) {
                    show = false;
                }

                if (pic && rowPIC !== pic) {
                    show = false;
                }

                row.style.display = show ? "" : "none";

            });

        });
        document.addEventListener("DOMContentLoaded", function() {

            let signaturePads = {};

            document.querySelectorAll(".modal").forEach(modal => {

                modal.addEventListener("shown.bs.modal", function() {

                    let canvas = modal.querySelector("canvas");

                    if (!canvas) return;

                    let id = canvas.id.replace('signature-pad-', '');

                    canvas.width = canvas.offsetWidth;
                    canvas.height = 150;

                    signaturePads[id] = new SignaturePad(canvas);

                });

            });

            window.clearSignature = function(id) {
                if (signaturePads[id]) {
                    signaturePads[id].clear();
                }
            }

            window.saveSignature = function(id) {

                if (signaturePads[id] && !signaturePads[id].isEmpty()) {

                    let data = signaturePads[id].toDataURL();

                    document.getElementById('signature-input-' + id).value = data;

                }

            }

        });
    </script>
@endsection
