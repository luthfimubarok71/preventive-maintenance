@extends('layouts.bar')

@section('content')
    <div class="container">
        <h4>Edit Jadwal PM</h4>

        <form method="POST" action="{{ route('pm-schedules.update', $schedule->id) }}">
            @csrf
            @method('PUT')

            <label>Segment</label>
            <select name="segment_id" class="form-control">

                @foreach ($segments as $segment)
                    <option value="{{ $segment->id }}" @if ($schedule->segment_id == $segment->id) selected @endif>

                        {{ $segment->kode_segment }} - {{ $segment->nama_segment }}

                    </option>
                @endforeach

            </select>

            <label class="mt-3">Priority</label>
            <input type="text" name="priority" class="form-control" value="{{ $schedule->priority }}">

            <label class="mt-3">Catatan</label>
            <textarea name="notes" class="form-control">{{ $schedule->notes }}</textarea>

            <button class="btn btn-primary mt-3">
                Update
            </button>

        </form>

    </div>
@endsection
