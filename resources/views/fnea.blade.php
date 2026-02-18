<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Demo FMEA – Preventive Maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            margin: 40px auto;
            width: 700px;
            border-radius: 6px;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 12px;
        }
        select, button {
            width: 100%;
            padding: 8px;
            margin-top: 6px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #333;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .badge {
            padding: 6px 10px;
            color: #fff;
            border-radius: 4px;
            font-weight: bold;
        }
        .tinggi { background: #c40406; }
        .sedang { background: #e67e22; }
        .rendah { background: #3498db; }
        .aman { background: #2ecc71; }
    </style>
</head>
<body>

<div class="container">
    <h2>Demo FMEA – Preventive Maintenance FO</h2>

    <form method="POST">
        @csrf

        <label>Jenis Segmen</label>
        <select name="segment_type" required>
            <option value="non-backbone">Non Backbone</option>
            <option value="backbone">Backbone</option>
        </select>

        <label>Kabel Putus</label>
        <select name="kabel_putus">
            <option value="tidak">Tidak</option>
            <option value="ya">Ya</option>
        </select>

        <label>Dampak Layanan</label>
        <select name="impact">
            <option value="normal">Tidak Terganggu</option>
            <option value="sebagian">Sebagian Terganggu</option>
            <option value="total">Segmen Down</option>
        </select>

        <label>Occurrence (Frekuensi / Bulan)</label>
        <select name="occurrence">
            <option value="sering">Sering (≥ 5 kali)</option>
            <option value="sedang">Sedang (2–4 kali)</option>
            <option value="jarang">Jarang (1 kali)</option>
            <option value="tidak_pernah">Tidak Pernah</option>
        </select>

        <button type="submit">Hitung RPN</button>
    </form>

    @if($_SERVER['REQUEST_METHOD'] === 'POST')
        @php
            // OCCURRENCE MAPPING
            $occurrenceMap = [
                'sering' => 5,
                'sedang' => 3,
                'jarang' => 2,
                'tidak_pernah' => 1
            ];
            $O = $occurrenceMap[$_POST['occurrence']];

            // DEFAULT
            $S = 1;
            $D = 1;

            // SEVERITY LOGIC
            if ($_POST['kabel_putus'] === 'ya') {
                if ($_POST['impact'] === 'normal') $S = 2;
                if ($_POST['impact'] === 'sebagian') $S = 3;
                if ($_POST['impact'] === 'total') $S = 4;
                $D = 1;
            }

            // BACKBONE ADJUSTMENT
            if ($_POST['segment_type'] === 'backbone') {
                $S += 1;
            }

            // RPN
            $RPN = $S * $O * $D;

            // PRIORITY & SCHEDULE
            if ($RPN >= 80) {
                $priority = 'TINGGI';
                $class = 'tinggi';
                $schedule = '4–1 kali / bulan';
            } elseif ($RPN >= 40) {
                $priority = 'SEDANG';
                $class = 'sedang';
                $schedule = '2–1 kali / bulan';
            } elseif ($RPN >= 20) {
                $priority = 'RENDAH';
                $class = 'rendah';
                $schedule = '1 kali / bulan';
            } else {
                $priority = 'AMAN';
                $class = 'aman';
                $schedule = 'Monitoring rutin';
            }
        @endphp

        <table>
            <tr>
                <th>Severity (S)</th>
                <td>{{ $S }}</td>
            </tr>
            <tr>
                <th>Occurrence (O)</th>
                <td>{{ $O }}</td>
            </tr>
            <tr>
                <th>Detection (D)</th>
                <td>{{ $D }}</td>
            </tr>
            <tr>
                <th>RPN</th>
                <td><strong>{{ $RPN }}</strong></td>
            </tr>
            <tr>
                <th>Prioritas Segmen</th>
                <td><span class="badge {{ $class }}">{{ $priority }}</span></td>
            </tr>
            <tr>
                <th>Rekomendasi Jadwal PM</th>
                <td>{{ $schedule }}</td>
            </tr>
        </table>
    @endif

</div>

</body>
</html>
