<!doctype html>

<html lang="id">


<head>
    <meta charset="UTF-8">
    <title>Form Inspeksi Jaringan Fiber Optik</title>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        :root {
            --blue: #2563eb;
            --blue-soft: #3b82f6;
            --blue-light: #93c5fd;
            --bg-gradient: linear-gradient(135deg, #e0f2fe, #dbeafe, #eff6ff);
            --card: #ffffff;
            --text: #1e293b;
            --muted: #64748b;
        }

        .container-form {

            max-width: 900px;

            margin: auto;

        }

        /* ================= BODY ================= */

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            margin: 0;
            padding: 50px;
            background: var(--bg-gradient);
            color: var(--text);
        }

        /* ================= TITLE ================= */

        h2 {
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            font-size: 28px;
            color: var(--blue);
        }

        h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--blue);
        }

        h4 {
            margin-top: 25px;
            margin-bottom: 10px;
            color: #334155;
        }

        /* ================= SECTION CARD ================= */

        .section {
            background: var(--card);
            border-radius: 14px;
            padding: 25px;
            margin-bottom: 25px;

            box-shadow:
                0 10px 25px rgba(0, 0, 0, 0.05),
                0 2px 6px rgba(0, 0, 0, 0.04);

            transition: all .2s ease;
        }

        .section:hover {
            transform: translateY(-2px);
        }

        /* ================= FORM ================= */

        label {
            font-weight: 600;
            font-size: 13px;
            color: var(--muted);
            display: block;
            margin-bottom: 2px;
        }

        /* ================= INPUT ================= */

        input,
        select,
        textarea {

            width: 100%;
            padding: 10px 12px;

            border-radius: 8px;
            border: 1px solid #e2e8f0;

            font-size: 14px;
            background: #f8fafc;

            transition: all .2s ease;
        }

        textarea {
            resize: vertical;
        }

        /* focus */

        input:focus,
        select:focus,
        textarea:focus {

            outline: none;

            border-color: var(--blue);

            background: white;

            box-shadow:
                0 0 0 2px rgba(37, 99, 235, 0.15);
        }

        /* readonly */

        input[readonly] {
            background: #eef2ff;
            font-weight: 600;
        }

        /* ================= GRID ================= */

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        /* ================= CANVAS ================= */

        canvas {

            border: 1px dashed #94a3b8;

            background: white;

            border-radius: 10px;

            margin-top: 10px;

        }

        /* ================= BUTTON ================= */

        button {

            padding: 10px 18px;

            border: none;

            border-radius: 8px;

            font-weight: 600;

            background: linear-gradient(135deg, #3b82f6, #2563eb);

            color: white;

            cursor: pointer;

            transition: all .2s ease;

            box-shadow: 0 6px 14px rgba(37, 99, 235, 0.25);
        }

        button:hover {

            transform: translateY(-1px);

            box-shadow: 0 10px 18px rgba(37, 99, 235, 0.3);
        }

        /* submit */

        form>button[type="submit"] {

            width: 100%;

            padding: 14px;

            font-size: 16px;

            margin-top: 20px;
        }

        /* ================= HR ================= */

        hr {

            border: none;

            border-top: 1px solid #e2e8f0;

            margin: 25px 0;

        }

        /* ================= SELECT2 ================= */

        .select2-container--default .select2-selection--single {

            height: 40px;

            border-radius: 8px;

            border: 1px solid #e2e8f0;

        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {

            line-height: 38px;

        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {

            height: 38px;
        }

        .form-group {

            display: grid;

            grid-template-columns: 180px 1fr;

            align-items: center;

            gap: 12px;

            margin-bottom: 10px;

        }

        .form-group label {

            margin: 0;

            font-weight: 600;

            font-size: 13px;

        }

        .form-group input,
        .form-group select,
        .form-group textarea {

            width: 100%;

        }
    </style>
</head>

<body>

    <div class="container-form">

        <div style="max-width:900px;margin:auto"></div>
        <h2>Form Inspeksi Jaringan Fiber Optik</h2>

        <form method="POST" action="{{ route('tasks.store', $schedule->id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">


            <!-- ===================== A. DATA INSPEKSI ===================== -->


            <div class="section">
                <h3>A. Data Inspeksi</h3>

                <input type="text" value="{{ $schedule->segment->nama_segment }}" readonly>

                <input type="hidden" name="segment_inspeksi" value="{{ $schedule->segment->nama_segment }}">

                <label>Jenis Jalur FO</label>
                <div class="form-group">
                    <input type="text" value="{{ ucfirst(str_replace('_', ' ', $schedule->segment->jalur)) }}"
                        readonly>
                </div>

                <div class="form-group">
                    <input type="hidden" name="jalur_fo" value="{{ $schedule->segment->jalur }}">
                </div>

                <label>Nama Pelaksana</label>
                <div class="form-group">
                    <select name="nama_pelaksana" id="nama_pelaksana" class="select2">
                        <option value="">-- Pilih Teknisi --</option>
                        @foreach ($teknisi as $t)
                            <option value="{{ $t->username }}">{{ $t->username }}</option>
                        @endforeach
                    </select>
                </div>

                <label>Driver</label>
                <div class="form-group">
                    <input type="text" name="driver">
                </div>

                <label>Cara Patroli</label>
                <div class="form-group">
                    <select name="cara_patroli" id="cara_patroli">
                        <option value="mobil">Mobil</option>
                        <option value="motor">Motor</option>
                        <option value="jalan_kaki">Jalan Kaki</option>
                        <option value="lainnya">Lain-lain</option>
                    </select>
                </div>
                <label id="label_cara_patroli_lainnya" style="display:none">
                    Keterangan Lain-lain
                </label>
                <div class="form-group">
                    <input type="text" name="cara_patroli_lainnya" id="cara_patroli_lainnya"
                        placeholder="Isi keterangan cara patroli lain..." style="display:none">

                    <input type="date" name="tanggal_inspeksi"
                        value="{{ $schedule->planned_date->format('Y-m-d') }}">
                </div>
            </div>
            <hr>

            <!-- ===================== B. KONDISI UMUM ===================== -->
            <div class="section">
                <h3>B. Kondisi Umum Jaringan Fiber Optik</h3>

                <h4>1. Kabel Putus</h4>
                <div class="form-group">
                    <select name="kabel_putus[status]">
                        <option value="tidak">Tidak</option>
                        <option value="ya">Ya</option>
                    </select>
                </div>


                <label>Jalur Backup</label>
                <div class="form-group">
                    <select name="kabel_putus[backup]">
                        <option value="ada">Ada</option>
                        <option value="tidak">Tidak</option>
                    </select>
                </div>

                <label>Dampak</label>
                <div class="form-group">
                    <select name="kabel_putus[dampak]">
                        <option value="normal">Tidak terganggu</option>
                        <option value="sebagian">Sebagian terganggu</option>
                        <option value="down">Segmen down total</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="kondisi[kabel_putus][catatan]" placeholder="Catatan kabel putus..." rows="3"></textarea>
                </div>
                <hr>


                <!-- 2. KABEL EXPOSE -->
                <h4>2. Kabel Expose</h4>
                <div class="form-group">
                    <select name="kabel_expose[status]">
                        <option value="tidak">Tidak</option>
                        <option value="ada">Ada</option>
                    </select>
                </div>

                <label>Kondisi Pelindung</label>
                <div class="form-group">
                    <select name="kabel_expose[pelindung]">
                        <option value="utuh">Utuh</option>
                        <option value="retak">Retak</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>


                <label>Kondisi Lingkungan</label>
                <div class="form-group">
                    <select name="kabel_expose[lingkungan]">
                        <option value="aman">Aman</option>
                        <option value="tanah_air">Tanah / Air</option>
                        <option value="beban">Beban Lalu Lintas</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="kondisi[kabel_expose][catatan]" placeholder="Catatan....." rows="3"></textarea>
                </div>
                <hr>


                <!-- 3. PENYANGGA JEMBATAN -->

                <h4>3. Penyangga Kabel di Jembatan</h4>
                <div class="form-group">
                    <div class="form-group">
                        <select name="penyangga[status]">
                            <option value="baik">Baik</option>
                            <option value="rusak">Ada Kerusakan</option>
                        </select>
                    </div>
                </div>

                <label>Kondisi Penyangga</label>
                <div class="form-group">
                    <select name="penyangga[kondisi]">
                        <option value="karat">Karat Ringan</option>
                        <option value="retak">Retak</option>
                        <option value="lepas">Hampir Lepas</option>
                    </select>
                </div>

                <label>Kondisi Kabel</label>
                <div class="form-group">
                    <select name="penyangga[kabel]">
                        <option value="aman">Kabel Aman</option>
                        <option value="menurun">Kabel Menurun</option>
                        <option value="tertarik">Kabel Tertarik</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="kondisi[penyangga][catatan]" placeholder="Catatan....." rows="3"></textarea>
                </div>
                <hr>

                <!-- 4. TIANG KU -->
                <h4>4. Tiang KU</h4>
                <div class="form-group">
                    <select name="tiang[posisi]">
                        <option value="tegak">Tegak</option>
                        <option value="miring">Miring</option>
                    </select>
                </div>


                <label>Kondisi Tiang</label>
                <div class="form-group">
                    <select name="tiang[kondisi]">
                        <option value="aman">Aman</option>
                        <option value="parah">Parah</option>
                        <option value="sangat_parah">Sangat Parah</option>
                    </select>
                </div>


                <label>Tingkat Kemiringan</label>
                <div class="form-group">
                    <select name="tiang[miring]">
                        <option value="ringan">Ringan</option>
                        <option value="sedang">Sedang</option>
                        <option value="berat">Berat</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea name="kondisi[tiang][catatan]" placeholder="Catatan....." rows="3"></textarea>
                </div>
                <hr>


                <!-- 5. KABEL DI CLAMP -->

                <h4>5. Kabel di Clamp</h4>
                <div class="form-group">
                    <select name="clamp[status]">
                        <option value="baik">Baik</option>
                        <option value="rusak">Ada Kerusakan</option>
                    </select>
                </div>


                <label>Kondisi Kabel</label>
                <div class="form-group">
                    <select name="clamp[kondisi]">
                        <option value="kendur">Kendur</option>
                        <option value="tergesek">Tergesek</option>
                        <option value="tertekan">Tertekan</option>
                    </select>
                </div>

                <div class="form-group">
                    <textarea name="kondisi[clamp][catatan]" placeholder="Catatan....." rows="3"></textarea>

                </div>
                <hr>

                <!-- 6. LINGKUNGAN -->
                <h4>6. Lingkungan</h4>
                <div class="form-group">
                    <select name="lingkungan[status]">
                        <option value="aman">Aman</option>
                        <option value="tidak_aman">Tidak Aman</option>
                    </select>
                </div>


                <label>Dampak Lingkungan</label>
                <div class="form-group">
                    <select name="lingkungan[dampak]">
                        <option value="belum">Belum Terdampak</option>
                        <option value="potensi">Berpotensi</option>
                        <option value="sudah">Sudah Terdampak</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="kondisi[lingkungan][catatan]" placeholder="Catatan....." rows="3"></textarea>

                </div>
                <hr>

                <!-- 7. VEGETASI -->
                <h4>7. Vegetasi</h4>
                <div class="form-group">
                    <select name="vegetasi[status]">
                        <option value="aman">Aman</option>
                        <option value="tidak_aman">Tidak Aman</option>
                    </select>
                </div>


                <label>Jarak Vegetasi</label>
                <div class="form-group">
                    <select name="vegetasi[jarak]">
                        <option value="dekat">Dekat</option>
                        <option value="sentuh">Menyentuh</option>
                        <option value="tekan">Menekan</option>
                        <option value="tumbang">Risiko Tumbang</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="kondisi[vegetasi][catatan]" placeholder="Catatan....." rows="3"></textarea>

                </div>

                <hr>

                <h4>8. Marker Post / Patok</h4>
                <div class="form-group">
                    <select name="marker_post">
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>


            </div>
            <div class="form-group">
                <textarea name="kondisi[marker_post][catatan]" placeholder="Catatan....." rows="3"></textarea>

            </div>

            <h4>9. Hand Hole (HH)</h4>
            <div class="form-group">
                <select name="hand_hole">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <textarea name="kondisi[hand_hole][catatan]"" placeholder="Catatan....." rows="3"></textarea>

            </div>

            <h4>10. Aksesoris KU</h4>
            <div class="form-group">
                <select name="aksesoris_ku">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <textarea name="kondisi[aksesoris_ku][catatan]" placeholder="Catatan....." rows="3"></textarea>

            </div>

            <h4>11. JC / ODP</h4>
            <div class="form-group">
                <select name="jc_odp">
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </div>

            <div class="form-group">
                <textarea name="kondisi[jc_odp][catatan]" placeholder="Catatan....." rows="3"></textarea>

            </div>


    </div>

    <hr>
    <hr>

    <!-- ===================== C. PENGESAHAN ===================== -->
    <div class="section">
        <h3>C. Pengesahan</h3>

        <div class="grid">

            <!-- Prepared -->
            <div>
                <label>Prepared By</label>
                <input type="text" name="prepared_by" id="prepared_by" readonly>

                <label>Tanda Tangan Prepared (Upload)</label>
                <input type="file" name="prepared_signature" accept="image/*">

                <p><b>atau tanda tangan manual:</b></p>
                <canvas id="canvas_prepared" width="320" height="160"></canvas>
                <input type="hidden" name="prepared_canvas" id="prepared_canvas">
                <button type="button" onclick="clearPrepared()">Clear</button>
            </div>

            <!-- Approved -->
            {{-- <div>
                    <label>Approved By</label>
                    <select name="approved_by" class="select2">
                        <option value="">-- Pilih Approver --</option>
                        @foreach ($approver as $a)
                            <option value="{{ $a->username }}">{{ $a->username }}</option>
                        @endforeach
                    </select>

                    <label>Tanda Tangan Approved (Upload)</label>
                    <input type="file" name="approved_signature" accept="image/*">

                    <p><b>atau tanda tangan manual:</b></p>
                    <canvas id="canvas_approved" width="320" height="160"></canvas>
                    <input type="hidden" name="approved_canvas" id="approved_canvas">
                    <button type="button" onclick="clearApproved()">Clear</button>
                </div> --}}

        </div>
    </div>

    <hr>

    <button type="submit" name="action" value="draft">
        Simpan Draft
    </button>

    <button type="submit" name="action" value="submit_ro">
        Kirim ke Kepala RO
    </button>
    </form>



</html>
</div>
</body>

<!-- ================= JS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
    $('.select2').select2({
        placeholder: 'Cari...',
        allowClear: true
    });

    $('#nama_pelaksana').on('change.select2', function() {
        const nama = $(this).val();
        $('#prepared_by').val(nama);
    });
</script>

<script>
    function setupCanvas(canvasId, inputId) {

        const canvas = document.getElementById(canvasId);
        const ctx = canvas.getContext("2d");

        let drawing = false;

        canvas.addEventListener("mousedown", start);
        canvas.addEventListener("mouseup", stop);
        canvas.addEventListener("mouseout", stop);
        canvas.addEventListener("mousemove", draw);

        function start(e) {
            drawing = true;
            draw(e);
        }

        function stop() {
            drawing = false;
            ctx.beginPath();
            save();
        }

        function draw(e) {
            if (!drawing) return;

            const rect = canvas.getBoundingClientRect();

            ctx.lineWidth = 2;
            ctx.lineCap = "round";
            ctx.strokeStyle = "#000";

            ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
        }

        function save() {
            document.getElementById(inputId).value = canvas.toDataURL();
        }

    }

    // aktifkan canvas
    setupCanvas("canvas_prepared", "prepared_canvas");
    setupCanvas("canvas_approved", "approved_canvas");

    function clearPrepared() {
        const canvas = document.getElementById("canvas_prepared");
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function clearApproved() {
        const canvas = document.getElementById("canvas_approved");
        const ctx = canvas.getContext("2d");
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }
</script>
