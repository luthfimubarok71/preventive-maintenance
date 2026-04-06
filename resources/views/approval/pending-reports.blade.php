@extends('layouts.bar')

@section('content')
    <div class="container">
        <h1>Pending Reports</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Segment</th>
                    <th>Date</th>
                    <th>Teknisi</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reports as $report)
                    <tr>
                        <td>{{ $report->segment_inspeksi }}</td>
                        <td>{{ $report->tanggal_inspeksi->format('Y-m-d') }}</td>
                        <td>{{ $report->pmSchedule->creator->name }}</td>
                        <td>{{ $report->priority }}</td>
                        <td>{{ $report->status_workflow }}</td>
                        <td>
                            <a href="{{ route('approval.approve.report', $report->id) }}"
                                class="btn btn-success btn-sm">Approve</a>
                            <a href="{{ route('approval.reject.report', $report->id) }}"
                                class="btn btn-danger btn-sm">Reject</a>
                            <a href="#" class="btn btn-info btn-sm">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
