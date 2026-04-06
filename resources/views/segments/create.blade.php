@extends('layouts.bar')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">

        <div style="width:100%; max-width:500px;">

            <h2 class="fw-bold mb-4 text-center">Tambah Segment</h2>

            <div class="card border-0 shadow-sm" style="border-radius:14px;">

                <div class="card-body" style="background: var(--card);">

                    <form action="{{ route('segments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nama Segment</label>
                            <input type="text" name="nama_segment" class="form-control"
                                placeholder="Masukkan nama segment" style="border-radius:8px;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Kode Segment</label>
                            <input type="text" name="kode_segment" class="form-control" placeholder="Contoh: SG-001"
                                style="border-radius:8px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Jalur</label>
                            <select name="jalur" class="form-select" style="border-radius:8px;">
                                <option value="">-- Pilih Jalur --</option>
                                <option value="backbone">Backbone</option>
                                <option value="non_backbone">Non Backbone</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('segments.index') }}" class="btn"
                                style="background:#e2e8f0; border-radius:8px;">
                                Kembali
                            </a>

                            <button type="submit" class="btn"
                                style="background: var(--gradient); color:white; border-radius:8px;">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>
@endsection
