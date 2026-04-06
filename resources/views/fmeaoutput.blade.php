@extends('layouts.bar')

@section('title', 'Hasil Perhitungan FMEA')

@section('content')
    <div class="container mt-4">

        <h2 class="mb-4">Daftar Segment FMEA</h2>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Segment</th>
                    <th>Prioritas</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($segments as $segment)
                    <tr>

                        <td>{{ $segment }}</td>

                        @php
                            $inspeksi = \App\Models\InspeksiHeader::where('segment_inspeksi', $segment)
                                ->latest()
                                ->first();
                        @endphp

                        <td>

                            @if ($inspeksi)
                                @if ($inspeksi->priority == 'KRITIS')
                                    <span class="badge bg-danger">KRITIS</span>
                                @elseif($inspeksi->priority == 'SEDANG')
                                    <span class="badge bg-warning">SEDANG</span>
                                @else
                                    <span class="badge bg-success">RENDAH</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Belum dihitung</span>
                            @endif

                        </td>

                        <td>

                            <button class="btn btn-primary btn-sm viewFmea" data-segment="{{ $segment }}"
                                data-bs-toggle="modal" data-bs-target="#fmeaModal">

                                View

                            </button>

                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
        <div class="modal fade" id="fmeaModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Hasil Perhitungan FMEA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" id="fmeaContent">

                        <div class="text-center p-4">
                            Loading data...
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FMEA -->
    <div class="modal fade" id="fmeaModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Hasil Perhitungan FMEA</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div id="fmeaResult">

                        @if ($results)

                            <div class="mb-3">

                                <h5>Prioritas: <strong>{{ $priority ?? '-' }}</strong></h5>
                                <h5>Jadwal PM: <strong>{{ $schedule ?? '-' }}</strong></h5>
                                <h5>Indeks Maksimum: <strong>{{ round($maxIndex, 2) }}</strong></h5>

                            </div>

                            <table class="table table-bordered">

                                <thead class="table-dark">
                                    <tr>
                                        <th>Item</th>
                                        <th>Severity</th>
                                        <th>Occurrence</th>
                                        <th>Detection</th>
                                        <th>RPN</th>
                                        <th>Risk Index</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    @foreach ($results as $result)
                                        <tr>

                                            <td>{{ $result['item'] }}</td>
                                            <td>{{ $result['S'] }}</td>
                                            <td>{{ $result['O'] }}</td>
                                            <td>{{ $result['D'] }}</td>
                                            <td>{{ $result['RPN'] }}</td>
                                            <td>{{ round($result['index'], 2) }}</td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        @else
                            <p>Tidak ada data FMEA untuk segment ini.</p>

                        @endif
                    </div>


                </div>

                <div class="modal-footer">
                    <a href="/fmea-demo" class="btn btn-primary">Input FMEA Baru</a>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.viewFmea').forEach(btn => {

            btn.addEventListener('click', function() {

                let segment = this.dataset.segment

                fetch("/fmeaoutput?segment=" + segment)
                    .then(res => res.text())
                    .then(html => {

                        let parser = new DOMParser()
                        let doc = parser.parseFromString(html, "text/html")

                        let resultTable = doc.querySelector("#fmeaResult")

                        document.getElementById("fmeaContent").innerHTML = resultTable.outerHTML

                    })

            })

        })
    </script>


@endsection
