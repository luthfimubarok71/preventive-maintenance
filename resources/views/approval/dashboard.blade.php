@extends('layouts.bar')

@section('content')
<div class="container">
    <h1>Approval Dashboard</h1>
    <div class="row">
        <div class="col-md-3">
            <a href="{{ route('approval.pending.schedules') }}" class="btn btn-primary btn-block">Pending Schedules</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('approval.pending.reports') }}" class="btn btn-primary btn-block">Pending Reports</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('approval.history') }}" class="btn btn-success btn-block">Approved History</a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('approval.rejected') }}" class="btn btn-danger btn-block">Rejected Data</a>
        </div>
    </div>
</div>
@endsection
