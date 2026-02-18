@extends('layouts.bar')

@section('title', 'Hasil FMEA')

@push('style')
<style>

/* ================= WRAPPER ================= */
.fmea-wrapper {
    background: #f4f6f9;
    padding: 25px;
    border-radius: 12px;
}

/* ================= TITLE ================= */
.fmea-title {
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 20px;
}

/* ================= TABLE ================= */
.fmea-table {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* HEADER */
.fmea-table thead {
    background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    color: white;
}

.fmea-table thead th {
    text-align: center;
    vertical-align: middle;
    font-weight: 600;
    padding: 14px;
}

/* BODY */
.fmea-table tbody td {
    vertical-align: middle;
    padding: 10px 14px;
}

/* CATEGORY COLUMN */
.fmea-category {
    font-weight: 700;
    background: #eef3ff;
    color: #0d6efd;
    text-align: center;
}

/* STRIPED */
.fmea-table tbody tr:nth-child(even) {
    background-color: #f9fbff;
}

/* HOVER */
.fmea-table tbody tr:hover {
    background-color: #eef4ff;
    transition: 0.2s;
}

/* RESPONSIVE */
.table-responsive {
    border-radius: 12px;
    overflow: hidden;
}

/* PRINT */
@media print {
    body {
        background: white;
    }
    .fmea-wrapper {
        box-shadow: none;
        padding: 0;
    }
}

</style>
@endpush

@section('content')
<div class="container mt-4">

    <div class="fmea-wrapper">

        <h2 class="fmea-title">📋 Hasil Inspeksi FMEA</h2>

        <div class="table-responsive">
            <table class="table table-bordered fmea-table">

                <thead>
                    <tr>
                        <th width="20%">Kategori</th>
                        <th width="45%">Detail</th>
                        <th width="35%">Nilai</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- ================= A. DATA INSPEKSI ================= -->
                    <tr>
                        <td rowspan="6" class="fmea-category">A. Data Inspeksi</td>
                        <td>Segment Inspeksi</td>
                        <td>{{ $data['segment_inspeksi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Jalur FO</td>
                        <td>{{ $data['jalur_fo'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Pelaksana</td>
                        <td>{{ $data['nama_pelaksana'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Driver</td>
                        <td>{{ $data['driver'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Cara Patroli</td>
                        <td>{{ $data['cara_patroli'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Inspeksi</td>
                        <td>{{ $data['tanggal_inspeksi'] ?? '-' }}</td>
                    </tr>

                    <!-- ================= B. KONDISI UMUM ================= -->
                    <tr>
                        <td rowspan="30" class="fmea-category">B. Kondisi Umum</td>
                        <td>Kabel Putus - Status</td>
                        <td>{{ $data['kabel_putus']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Putus - Jalur Backup</td>
                        <td>{{ $data['kabel_putus']['backup'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Putus - Dampak</td>
                        <td>{{ $data['kabel_putus']['dampak'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Putus - Catatan</td>
                        <td>{{ $data['kondisi']['kabel_putus']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Kabel Expose - Status</td>
                        <td>{{ $data['kabel_expose']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Expose - Kondisi Pelindung</td>
                        <td>{{ $data['kabel_expose']['pelindung'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Expose - Kondisi Lingkungan</td>
                        <td>{{ $data['kabel_expose']['lingkungan'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel Expose - Catatan</td>
                        <td>{{ $data['kondisi']['kabel_expose']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Penyangga Jembatan - Status</td>
                        <td>{{ $data['penyangga']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Penyangga Jembatan - Kondisi Penyangga</td>
                        <td>{{ $data['penyangga']['kondisi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Penyangga Jembatan - Kondisi Kabel</td>
                        <td>{{ $data['penyangga']['kabel'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Penyangga Jembatan - Catatan</td>
                        <td>{{ $data['kondisi']['penyangga']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Tiang KU - Posisi</td>
                        <td>{{ $data['tiang']['posisi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tiang KU - Kondisi</td>
                        <td>{{ $data['tiang']['kondisi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tiang KU - Tingkat Kemiringan</td>
                        <td>{{ $data['tiang']['miring'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tiang KU - Catatan</td>
                        <td>{{ $data['kondisi']['tiang']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Kabel di Clamp - Status</td>
                        <td>{{ $data['clamp']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel di Clamp - Kondisi Kabel</td>
                        <td>{{ $data['clamp']['kondisi'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kabel di Clamp - Catatan</td>
                        <td>{{ $data['kondisi']['clamp']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Lingkungan - Status</td>
                        <td>{{ $data['lingkungan']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lingkungan - Dampak</td>
                        <td>{{ $data['lingkungan']['dampak'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Lingkungan - Catatan</td>
                        <td>{{ $data['kondisi']['lingkungan']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Vegetasi - Status</td>
                        <td>{{ $data['vegetasi']['status'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Vegetasi - Jarak</td>
                        <td>{{ $data['vegetasi']['jarak'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Vegetasi - Catatan</td>
                        <td>{{ $data['kondisi']['vegetasi']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Marker Post / Patok</td>
                        <td>{{ $data['marker_post'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Marker Post - Catatan</td>
                        <td>{{ $data['kondisi']['marker_post']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Hand Hole (HH)</td>
                        <td>{{ $data['hand_hole'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Hand Hole - Catatan</td>
                        <td>{{ $data['kondisi']['hand_hole']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>Aksesoris KU</td>
                        <td>{{ $data['aksesoris_ku'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Aksesoris KU - Catatan</td>
                        <td>{{ $data['kondisi']['aksesoris_ku']['catatan'] ?? '-' }}</td>
                    </tr>

                    <tr>
                        <td>JC / ODP</td>
                        <td>{{ $data['jc_odp'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>JC / ODP - Catatan</td>
                        <td>{{ $data['kondisi']['jc_odp']['catatan'] ?? '-' }}</td>
                    </tr>

                    <!-- ================= C. PENGESAHAN ================= -->
                    <tr>
                        <td rowspan="2" class="fmea-category">C. Pengesahan</td>
                        <td>Prepared By</td>
                        <td>{{ $data['prepared_by'] ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Approved By</td>
                        <td>{{ $data['approved_by'] ?? '-' }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
