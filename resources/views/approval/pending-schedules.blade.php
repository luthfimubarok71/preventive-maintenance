@extends('layouts.bar')

@section('content')
<div class="container">
    <h1>Pending Schedules</h1>
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
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->segment_inspeksi }}</td>
                <td>{{ $schedule->planned_date->format('Y-m-d') }}</td>
                <td>{{ $schedule->creator->name }}</td>
                <td>{{ $schedule->priority }}</td>
                <td>{{ $schedule->status }}</td>
                <td>
                    <a href="{{ route('approval.approve.schedule', $schedule->id) }}" class="btn btn-success btn-sm">Approve</a>
                    <a href="{{ route('approval.reject.schedule', $schedule->id) }}" class="btn btn-danger btn-sm">Reject</a>
                    <a href="#" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
