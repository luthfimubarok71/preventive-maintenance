@extends('layouts.bar')

@section('title', 'Hasil Perhitungan FMEA')

@section('content')
<div class="container mt-4">
    <h2>Hasil Perhitungan FMEA (SOD)</h2>

    <!-- Form Pilih Segment -->
    <form method="GET" action="{{ route('fmeaoutput') }}" class="mb-4">
        <div class="form-group">
            <label for="segment">Pilih Segment Inspeksi:</label>
            <select name="segment" id="segment" class="form-control" onchange="this.form.submit()">
                @foreach($segments as $segment)
                    <option value="{{ $segment }}" {{ $selectedSegment == $segment ? 'selected' : '' }}>{{ $segment }}</option>
                @endforeach
            </select>
        </div>
    </form>

    @if($results)
        <div class="mb-4">
            <h4>Prioritas: <strong>{{ $priority ?? 'Tidak Diketahui' }}</strong></h4>
            <h4>Jadwal PM: <strong>{{ $schedule ?? 'Tidak Diketahui' }}</strong></h4>
            <h4>Indeks Maksimum: <strong>{{ round($maxIndex, 2) }}</strong></h4>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Severity (S)</th>
                    <th>Occurrence (O)</th>
                    <th>Detection (D)</th>
                    <th>RPN (S x O x D)</th>
                    <th>Risk Index</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
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
        <p>Tidak ada data perhitungan FMEA yang tersedia.</p>
    @endif

    <a href="/fmea-demo" class="btn btn-primary">Kembali ke Form FMEA</a>
</div>
@endsection
