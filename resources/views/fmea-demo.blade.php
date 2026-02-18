<!doctype html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Form Inspeksi Jaringan Fiber Optik</title>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
:root{
    --blue: #2563eb;
    --blue-light: #60a5fa;
    --bg: #eff6ff;
    --card: #ffffff;
    --text: #1e293b;
    --muted: #64748b;
    --gradient: linear-gradient(135deg, #60a5fa, #2563eb);
}

/* ================= GLOBAL ================= */
body{
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 40px;
    background: var(--bg);
    color: var(--text);
}

h2{
    text-align: center;
    margin-bottom: 30px;
}

h3{
    margin-top: 0;
    color: var(--blue);
}

h4{
    margin-top: 25px;
    color: var(--text);
}

hr{
    margin: 25px 0;
    border: none;
    border-top: 1px solid #cbd5e1;
}

/* ================= CARD / SECTION ================= */
.section{
    background: var(--card);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* ================= FORM ================= */
label{
    font-weight: 600;
    font-size: 14px;
    color: var(--muted);
}

input,
select,
textarea,
button{
    padding: 8px 10px;
    margin-top: 5px;
    margin-bottom: 12px;
    width: 100%;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus{
    outline: none;
    border-color: var(--blue);
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

/* ================= GRID ================= */
.grid{
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

/* ================= CANVAS ================= */
canvas{
    border: 1px solid #94a3b8;
    background: #fff;
    border-radius: 6px;
}

/* ================= BUTTON ================= */
button{
    background: var(--gradient);
    color: white;
    border: none;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

button:hover{
    opacity: 0.9;
}

/* Submit button spacing */
form > button[type="submit"]{
    margin-top: 20px;
    padding: 12px;
    font-size: 16px;
}
</style>
</head>

<body>

<h2>Form Inspeksi Jaringan Fiber Optik</h2>

<form method="POST" enctype="multipart/form-data">
@csrf

<!-- ===================== A. DATA INSPEKSI ===================== -->
<div class="section">
<h3>A. Data Inspeksi</h3>

<label>Segment Inspeksi</label>
<select name="segment_inspeksi" class="select2">
    <option value="">-- Pilih Segment --</option>
    <option value="FO-A-01">FO-A-01</option>
    <option value="FO-A-02">FO-A-02</option>
    <option value="FO-B-01">FO-B-01</option>
    <option value="FO-B-02">FO-B-02</option>
</select>

<label>Jenis Jalur FO</label>
<select name="jalur_fo">
    <option value="non_backbone">Non Backbone</option>
    <option value="backbone">Backbone</option>
</select>

<label>Nama Pelaksana</label>
<select name="nama_pelaksana" id="nama_pelaksana" class="select2">
    <option value="">-- Pilih Teknisi --</option>
    @foreach($teknisi as $t)
        <option value="{{ $t->username }}">{{ $t->username }}</option>
    @endforeach
</select>

<label>Driver</label>
<input type="text" name="driver">

<label>Cara Patroli</label>
<select name="cara_patroli" id="cara_patroli">
    <option value="mobil">Mobil</option>
    <option value="motor">Motor</option>
    <option value="jalan_kaki">Jalan Kaki</option>
    <option value="lainnya">Lain-lain</option>
</select>

<label id="label_cara_patroli_lainnya" style="display:none">
    Keterangan Lain-lain
</label>
<input type="text"
       name="cara_patroli_lainnya"
       id="cara_patroli_lainnya"
       placeholder="Isi keterangan cara patroli lain..."
       style="display:none">

<label>Tanggal Inspeksi</label>
<input type="date" name="tanggal_inspeksi">
</div>

<hr>

<!-- ===================== B. KONDISI UMUM ===================== -->
<div class="section">
<h3>B. Kondisi Umum Jaringan Fiber Optik</h3>

<h4>1. Kabel Putus</h4>
<select name="kabel_putus[status]">
    <option value="tidak">Tidak</option>
    <option value="ya">Ya</option>
</select>

<label>Jalur Backup</label>
<select name="kabel_putus[backup]">
    <option value="ada">Ada</option>
    <option value="tidak">Tidak</option>
</select>

<label>Dampak</label>
<select name="kabel_putus[dampak]">
    <option value="normal">Tidak terganggu</option>
    <option value="sebagian">Sebagian terganggu</option>
    <option value="down">Segmen down total</option>
</select>

<textarea name="kondisi[kabel_putus][catatan]"
          placeholder="Catatan kabel putus..."
          rows="3"></textarea>

<hr>
<!-- 2. KABEL EXPOSE -->
<h4>2. Kabel Expose</h4>
<select name="kabel_expose[status]">
    <option value="tidak">Tidak</option>
    <option value="ada">Ada</option>
</select>

<label>Kondisi Pelindung</label>
<select name="kabel_expose[pelindung]">
    <option value="utuh">Utuh</option>
    <option value="retak">Retak</option>
    <option value="rusak">Rusak</option>
</select>

<label>Kondisi Lingkungan</label>
<select name="kabel_expose[lingkungan]">
    <option value="aman">Aman</option>
    <option value="tanah_air">Tanah / Air</option>
    <option value="beban">Beban Lalu Lintas</option>
</select>
<textarea name="kondisi[kabel_expose][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>
<hr>

<!-- 3. PENYANGGA JEMBATAN -->
<h4>3. Penyangga Kabel di Jembatan</h4>
<select name="penyangga[status]">
    <option value="baik">Baik</option>
    <option value="rusak">Ada Kerusakan</option>
</select>

<label>Kondisi Penyangga</label>
<select name="penyangga[kondisi]">
    <option value="karat">Karat Ringan</option>
    <option value="retak">Retak</option>
    <option value="lepas">Hampir Lepas</option>
</select>

<label>Kondisi Kabel</label>
<select name="penyangga[kabel]">
    <option value="aman">Kabel Aman</option>
    <option value="menurun">Kabel Menurun</option>
    <option value="tertarik">Kabel Tertarik</option>
</select>
<textarea name="kondisi[penyangga][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>
<hr>

<!-- 4. TIANG KU -->
<h4>4. Tiang KU</h4>
<select name="tiang[posisi]">
    <option value="tegak">Tegak</option>
    <option value="miring">Miring</option>
</select>

<label>Kondisi Tiang</label>
<select name="tiang[kondisi]">
    <option value="aman">Aman</option>
    <option value="parah">Parah</option>
    <option value="sangat_parah">Sangat Parah</option>
</select>

<label>Tingkat Kemiringan</label>
<select name="tiang[miring]">
    <option value="ringan">Ringan</option>
    <option value="sedang">Sedang</option>
    <option value="berat">Berat</option>
</select>
<textarea name="kondisi[tiang][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>
<hr>

<!-- 5. KABEL DI CLAMP -->
<h4>5. Kabel di Clamp</h4>
<select name="clamp[status]">
    <option value="baik">Baik</option>
    <option value="rusak">Ada Kerusakan</option>
</select>

<label>Kondisi Kabel</label>
<select name="clamp[kondisi]">
    <option value="kendur">Kendur</option>
    <option value="tergesek">Tergesek</option>
    <option value="tertekan">Tertekan</option>
</select>
<textarea name="kondisi[clamp][catatan]"
          placeholder="Catatan....."
          rows="3"></textarea>
<hr>

<!-- 6. LINGKUNGAN -->
<h4>6. Lingkungan</h4>
<select name="lingkungan[status]">
    <option value="aman">Aman</option>
    <option value="tidak_aman">Tidak Aman</option>
</select>

<label>Dampak Lingkungan</label>
<select name="lingkungan[dampak]">
    <option value="belum">Belum Terdampak</option>
    <option value="potensi">Berpotensi</option>
    <option value="sudah">Sudah Terdampak</option>
</select>
<textarea name="kondisi[lingkungan][catatan]"
placeholder="Catatan....."
rows="3"></textarea>
<hr>

<!-- 7. VEGETASI -->
<h4>7. Vegetasi</h4>
<select name="vegetasi[status]">
    <option value="aman">Aman</option>
    <option value="tidak_aman">Tidak Aman</option>
</select>

<label>Jarak Vegetasi</label>
<select name="vegetasi[jarak]">
    <option value="dekat">Dekat</option>
    <option value="sentuh">Menyentuh</option>
    <option value="tekan">Menekan</option>
    <option value="tumbang">Risiko Tumbang</option>
</select>
<textarea name="kondisi[vegetasi][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>

<hr>

<h4>8. Marker Post / Patok</h4>
<select name="marker_post">
    <option value="baik">Baik</option>
    <option value="rusak">Rusak</option>
</select>
<textarea name="kondisi[marker_post][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>

<h4>9. Hand Hole (HH)</h4>
<select name="hand_hole">
    <option value="baik">Baik</option>
    <option value="rusak">Rusak</option>
</select>

<textarea name="kondisi[hand_hole][catatan]"" 
          placeholder="Catatan....."
          rows="3"></textarea>

<h4>10. Aksesoris KU</h4>
<select name="aksesoris_ku">
    <option value="baik">Baik</option>
    <option value="rusak">Rusak</option>
</select>
<textarea name="kondisi[aksesoris_ku][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>

<h4>11. JC / ODP</h4>
<select name="jc_odp">
    <option value="baik">Baik</option>
    <option value="rusak">Rusak</option>
</select>

<textarea name="kondisi[jc_odp][catatan]" 
          placeholder="Catatan....."
          rows="3"></textarea>


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
    <div>
        <label>Approved By</label>
        <select name="approved_by" class="select2">
            <option value="">-- Pilih Approver --</option>
            @foreach($approver as $a)
                <option value="{{ $a->username }}">{{ $a->username }}</option>
            @endforeach
        </select>

        <label>Tanda Tangan Approved (Upload)</label>
        <input type="file" name="approved_signature" accept="image/*">

        <p><b>atau tanda tangan manual:</b></p>
        <canvas id="canvas_approved" width="320" height="160"></canvas>
        <input type="hidden" name="approved_canvas" id="approved_canvas">
        <button type="button" onclick="clearApproved()">Clear</button>
    </div>

</div>
</div>

<hr>

<button type="submit">Simpan & Hitung Risiko</button>

</form>

<!-- ================= JS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<script>
$('.select2').select2({ placeholder:'Cari...', allowClear:true });

$('#nama_pelaksana').on('change.select2', function () {
    const nama = $(this).val();
    $('#prepared_by').val(nama);
});
</script>

</body>
</html>
