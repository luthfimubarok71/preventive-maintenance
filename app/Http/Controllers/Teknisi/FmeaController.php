<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InspeksiHeader;
use App\Models\InspeksiFmeaDetail;
use App\Models\InspeksiKondisiUmum;

class FmeaController extends Controller
{
    public function index(Request $request)
    {
        // ================= INIT =================
        $results = [];
        $maxIndex = 0;
        $alarmKritis = false;
        $priority = null;
        $schedule = null;

        $jalurFO = $request->jalur_fo ?? 'non_backbone';

        // dropdown teknisi
        $teknisi = User::where('role', 'teknisi')->get();
        $approver = User::whereIn('role',['kepala_ro','pusat'])->get();

        // RPN maksimum
        $rpnMax = [
            'Kabel Putus' => 15,
            'Kabel Expose' => 60,
            'Penyangga Kabel di Jembatan' => 60,
            'Tiang KU' => 30,
            'Kabel di Clamp' => 24,
            'Lingkungan' => 75,
            'Vegetasi' => 45,
        ];

        // ================= HELPER =================
        $hitung = function ($item, $S, $O, $D) use (&$alarmKritis, &$maxIndex, &$results, $rpnMax) {
            if ($S === 5) $alarmKritis = true;

            $RPN = $S * $O * $D;
            $index = $RPN / $rpnMax[$item];
            $maxIndex = max($maxIndex, $index);

            $results[] = [
                'item' => $item,
                'S' => $S,
                'O' => $O,
                'D' => $D,
                'RPN' => $RPN,
                'index' => round($index, 2),
            ];
        };

        $adjustBackbone = function ($S) use ($jalurFO) {
            return ($jalurFO === 'backbone') ? min($S + 1, 5) : $S;
        };

        // ================= LOGIKA FMEA =================
        if ($request->isMethod('post')) {

            // 1. Kabel Putus
            if ($request->kabel_putus['status'] === 'ya') {
                if ($request->kabel_putus['dampak'] === 'down') {
                    $S = ($request->kabel_putus['backup'] === 'ada') ? 4 : 5;
                } elseif ($request->kabel_putus['dampak'] === 'sebagian') {
                    $S = 3;
                } else {
                    $S = 2;
                }

                $S = $adjustBackbone($S);
                $hitung('Kabel Putus', $S, 2, 1);
            }

            // 2. Kabel Expose
            if ($request->kabel_expose['status'] === 'ada') {
                $S = match ($request->kabel_expose['pelindung']) {
                    'rusak' => 4,
                    'retak' => 3,
                    default => 2
                };
                if ($request->kabel_expose['lingkungan'] !== 'aman') $S++;
                $S = $adjustBackbone(min($S, 5));

                $hitung('Kabel Expose', $S, 2, 2);
            }

            // 3. Penyangga Jembatan
            if ($request->penyangga['status'] === 'rusak') {
                $S = match ($request->penyangga['kondisi']) {
                    'lepas' => 4,
                    'retak' => 3,
                    default => 2
                };
                if ($request->penyangga['kabel'] !== 'aman') $S++;
                $S = $adjustBackbone(min($S, 5));

                $hitung('Penyangga Kabel di Jembatan', $S, 2, 2);
            }

            // 4. Tiang KU
            if ($request->tiang['posisi'] === 'miring') {
                $S = match ($request->tiang['kondisi']) {
                    'sangat_parah' => 4,
                    'parah' => 3,
                    default => 2
                };
                if ($request->tiang['miring'] === 'berat') $S++;
                $S = $adjustBackbone(min($S, 5));

                $hitung('Tiang KU', $S, 2, 1);
            }

            // 5. Kabel di Clamp
            if ($request->clamp['status'] === 'rusak') {
                $S = match ($request->clamp['kondisi']) {
                    'tertekan' => 4,
                    'tergesek' => 3,
                    default => 2
                };
                $S = $adjustBackbone($S);

                $hitung('Kabel di Clamp', $S, 2, 2);
            }

            // 6. Lingkungan
            if ($request->lingkungan['status'] === 'tidak_aman') {
                $S = match ($request->lingkungan['dampak']) {
                    'sudah' => 4,
                    'potensi' => 3,
                    default => 2
                };
                $S = $adjustBackbone($S);

                $hitung('Lingkungan', $S, 2, 3);
            }

            // 7. Vegetasi
            if ($request->vegetasi['status'] === 'tidak_aman') {
                $S = match ($request->vegetasi['jarak']) {
                    'tumbang' => 4,
                    'tekan' => 3,
                    'sentuh' => 2,
                    default => 1
                };
                $S = $adjustBackbone($S);

                $hitung('Vegetasi', $S, 2, 3);
            }

            // ================= PRIORITAS =================
            if ($alarmKritis || $maxIndex >= 0.8) {
                $priority = 'KRITIS';
                $schedule = 'minimal pm 3x sebulan';
            } elseif ($maxIndex >= 0.4) {
                $priority = 'SEDANG';
                $schedule = 'minimal pm 2x sebulan';
            } else {
                $priority = 'RENDAH';
                $schedule = 'minimal pm 1x sebulan';
            }

            // ================= SIMPAN DATA =================
            $inspeksi = InspeksiHeader::create([
                'segment_inspeksi' => $request->segment_inspeksi,
                'jalur_fo' => $request->jalur_fo,
                'nama_pelaksana' => $request->nama_pelaksana,
                'driver' => $request->driver,
                'cara_patroli' => $request->cara_patroli,
                'cara_patroli_lainnya' => $request->cara_patroli_lainnya,
                'tanggal_inspeksi' => $request->tanggal_inspeksi,
                'priority' => $priority,
                'schedule_pm' => $schedule,
                'prepared_by' => $request->prepared_by,
                'approved_by' => $request->approved_by,
                'schedule_id' => null,
                'status_workflow' => 'draft',
            ]);

            // Simpan FMEA Details
            foreach ($results as $result) {
                InspeksiFmeaDetail::create([
                    'inspeksi_id' => $inspeksi->id,
                    'item' => $result['item'],
                    'severity' => $result['S'],
                    'occurrence' => 2, // Fixed value based on code
                    'detection' => $result['D'],
                    'rpn' => $result['RPN'],
                    'risk_index' => $result['index'],
                ]);
            }

            // Simpan Kondisi Umum
            InspeksiKondisiUmum::create([
                'inspeksi_id' => $inspeksi->id,
                'marker_post' => $request->marker_post,
                'hand_hole' => $request->hand_hole,
                'aksesoris_ku' => $request->aksesoris_ku,
                'jc_odp' => $request->jc_odp,
            ]);

            // Simpan hasil perhitungan ke session
            session([
                'results' => $results,
                'priority' => $priority,
                'schedule' => $schedule,
                'maxIndex' => $maxIndex,
            ]);

            return redirect('/fmeaoutput')->with('success', 'Data inspeksi berhasil disimpan ke database, termasuk detail FMEA di tabel inspeksi_fmea_details.');
        }
return view('fmea-demo', compact(
    'teknisi','approver','results','maxIndex','priority','schedule'
));


    }

    public function hasil(Request $request, $id = null)
    {
        $segments = InspeksiHeader::select('segment_inspeksi')->distinct()->pluck('segment_inspeksi');
        $selectedSegment = $request->get('segment', $segments->first());

        if ($id) {
            $inspeksi = InspeksiHeader::findOrFail($id);
        } else {
            $inspeksi = InspeksiHeader::where('segment_inspeksi', $selectedSegment)->latest()->first();
        }

        if (!$inspeksi) {
            return view('hasilfmea', compact('segments', 'selectedSegment'))->with('error', 'Data inspeksi tidak ditemukan untuk segment ini');
        }

        $kondisiUmum = InspeksiKondisiUmum::where('inspeksi_id', $inspeksi->id)->first();

        $data = [
            'segment_inspeksi' => $inspeksi->segment_inspeksi,
            'jalur_fo' => $inspeksi->jalur_fo,
            'nama_pelaksana' => $inspeksi->nama_pelaksana,
            'driver' => $inspeksi->driver,
            'cara_patroli' => $inspeksi->cara_patroli,
            'tanggal_inspeksi' => $inspeksi->tanggal_inspeksi,
            'prepared_by' => $inspeksi->prepared_by,
            'approved_by' => $inspeksi->approved_by,
            'kabel_putus' => [
                'status' => 'ya', // Assuming from logic, but need to adjust based on saved data
                'backup' => 'ada',
                'dampak' => 'down',
            ],
            'kabel_expose' => [
                'status' => 'ada',
                'pelindung' => 'rusak',
                'lingkungan' => 'aman',
            ],
            'penyangga' => [
                'status' => 'rusak',
                'kondisi' => 'lepas',
                'kabel' => 'aman',
            ],
            'tiang' => [
                'posisi' => 'miring',
                'kondisi' => 'parah',
                'miring' => 'berat',
            ],
            'clamp' => [
                'status' => 'rusak',
                'kondisi' => 'tertekan',
            ],
            'lingkungan' => [
                'status' => 'tidak_aman',
                'dampak' => 'sudah',
            ],
            'vegetasi' => [
                'status' => 'tidak_aman',
                'jarak' => 'tumbang',
            ],
            'marker_post' => $kondisiUmum->marker_post ?? 'baik',
            'hand_hole' => $kondisiUmum->hand_hole ?? 'baik',
            'aksesoris_ku' => $kondisiUmum->aksesoris_ku ?? 'baik',
            'jc_odp' => $kondisiUmum->jc_odp ?? 'baik',
            'kondisi' => [
                'kabel_putus' => ['catatan' => ''],
                'kabel_expose' => ['catatan' => ''],
                'penyangga' => ['catatan' => ''],
                'tiang' => ['catatan' => ''],
                'clamp' => ['catatan' => ''],
                'lingkungan' => ['catatan' => ''],
                'vegetasi' => ['catatan' => ''],
                'marker_post' => ['catatan' => ''],
                'hand_hole' => ['catatan' => ''],
                'aksesoris_ku' => ['catatan' => ''],
                'jc_odp' => ['catatan' => ''],
            ],
        ];

        return view('hasilfmea', compact('data', 'segments', 'selectedSegment'));
    }

    public function output(Request $request)
    {
        $segments = InspeksiHeader::select('segment_inspeksi')->distinct()->pluck('segment_inspeksi');
        $selectedSegment = $request->get('segment', $segments->first());

        $results = session('results', []);
        $priority = session('priority');
        $schedule = session('schedule');
        $maxIndex = session('maxIndex', 0);

        // Jika session kosong atau segment berbeda, ambil dari database berdasarkan segment
        if (empty($results) || session('selected_segment') != $selectedSegment) {
            $latestInspeksi = InspeksiHeader::where('segment_inspeksi', $selectedSegment)->latest()->first();
            if ($latestInspeksi) {
                $fmeaDetails = InspeksiFmeaDetail::where('inspeksi_id', $latestInspeksi->id)->get();
                $results = $fmeaDetails->map(function ($detail) {
                    return [
                        'item' => $detail->item,
                        'S' => $detail->severity,
                        'O' => $detail->occurrence,
                        'D' => $detail->detection,
                        'RPN' => $detail->rpn,
                        'index' => $detail->risk_index,
                    ];
                })->toArray();
                $priority = $latestInspeksi->priority;
                $schedule = $latestInspeksi->schedule_pm;
                $maxIndex = $fmeaDetails->max('risk_index') ?? 0;
            }
        }

        return view('fmeaoutput', compact('results', 'priority', 'schedule', 'maxIndex', 'segments', 'selectedSegment'));
    }
}