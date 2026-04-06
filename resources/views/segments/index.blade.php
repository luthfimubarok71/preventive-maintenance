@extends('layouts.bar')

@section('content')
    <div class="container mt-4">

        <h2 class="fw-bold mb-3">Master Segment</h2>

        <a href="{{ route('segments.create') }}" class="btn mb-4"
            style="
                background: var(--gradient);
                color:white;
                border:none;
                border-radius:8px;
            ">
            + Tambah Segment
        </a>

        <div class="card border-0 shadow-sm" style="border-radius:14px; overflow:hidden;">

            <div class="table-responsive">
                <table class="table mb-0 align-middle">

                    <thead style="background: var(--gradient); color:white;">
                        <tr>
                            <th class="px-3 py-3">Nama Segment</th>
                            <th class="px-3 py-3">Kode</th>
                            <th class="px-3 py-3">Jalur</th>
                        </tr>
                    </thead>

                    <tbody style="background: var(--card);">

                        @forelse ($segments as $segment)
                            <tr style="border-bottom:1px solid #e2e8f0;">
                                <td class="px-3 py-3 fw-semibold">
                                    {{ $segment->nama_segment }}
                                </td>
                                <td class="px-3 py-3 text-muted">
                                    {{ $segment->kode_segment }}
                                </td>
                                <td class="px-3 py-3">
                                    <span
                                        style="
                                        background: var(--bg);
                                        padding:6px 12px;
                                        border-radius:999px;
                                        font-size:13px;
                                        color: var(--blue);
                                        font-weight:500;
                                    ">
                                        {{ $segment->jalur }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    Belum ada data segment
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>

        </div>

    </div>
@endsection
