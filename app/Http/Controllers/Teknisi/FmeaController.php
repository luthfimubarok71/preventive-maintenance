<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\InspeksiHeader;
use App\Models\InspeksiFmeaDetail;
use App\Models\InspeksiKondisiUmum;
use App\Models\InspeksiDetail;
use App\Models\PmSchedule;

class FmeaController extends Controller
{
    /**
     * Get available approved schedules for today
     */
    private function getAvailableSchedules()
    {
        return PmSchedule::where('status', 'approved')
            ->whereDate('planned_date', today())
            ->get();
    }

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

        // Get available approved schedules for the day
        $availableSchedules = $this->getAvailableSchedules();

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

            // ========== TASK 1: ENFORCE SCHEDULE REQUIREMENT ==========
            $selectedScheduleId = $request->schedule_id;
            
            // Validate that schedule_id is provided
            if (!$selectedScheduleId) {
                return back()->with('error', 'Schedule ID wajib dipilih.')->withInput();
            }
            
            // Find the approved schedule
            $approvedSchedule = PmSchedule::where('id', $selectedScheduleId)
                ->where('status', 'approved')
                ->whereDate('planned_date', today())
                ->first();

            // Check if approved schedule exists
            if (!$approvedSchedule) {
                return back()->with('error', 'No approved schedule available for today.')->withInput();
            }

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
                // Get occurrence from request (allow manual input for demo mode)
                $O = $request->kabel_putus['occurrence'] ?? 2;
                $hitung('Kabel Putus', $S, $O, 1);
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
                
                // Get occurrence from request
                $O = $request->kabel_expose['occurrence'] ?? 2;
                $hitung('Kabel Expose', $S, $O, 2);
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
                
                // Get occurrence from request
                $O = $request->penyangga['occurrence'] ?? 2;
                $hitung('Penyangga Kabel di Jembatan', $S, $O, 2);
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
                
                // Get occurrence from request
                $O = $request->tiang['occurrence'] ?? 2;
                $hitung('Tiang KU', $S, $O, 1);
            }

            // 5. Kabel di Clamp
            if ($request->clamp['status'] === 'rusak') {
                $S = match ($request->clamp['kondisi']) {
                    'tertekan' => 4,
                    'tergesek' => 3,
                    default => 2
                };
                $S = $adjustBackbone($S);
                
                // Get occurrence from request
                $O = $request->clamp['occurrence'] ?? 2;
                $hitung('Kabel di Clamp', $S, $O, 2);
            }

            // 6. Lingkungan
            if ($request->lingkungan['status'] === 'tidak_aman') {
                $S = match ($request->lingkungan['dampak']) {
                    'sudah' => 4,
                    'potensi' => 3,
                    default => 2
                };
                $S = $adjustBackbone($S);
                
                // Get occurrence from request
                $O = $request->lingkungan['occurrence'] ?? 2;
                $hitung('Lingkungan', $S, $O, 3);
            }

            // 7. Vegetasi
            if ($request->vegetasi['status'] === 'tidak_aman') {
                $S = match ($request->vegetasi['jarak']) {
                    'tumbnail' => 4,
                    'tekan' => 3,
                    'sentuh' => 2,
                    default => 1
                };
                $S = $adjustBackbone($S);
                
                // Get occurrence from request
                $O = $request->vegetasi['occurrence'] ?? 2;
                $hitung('Vegetasi', $S, $O, 3);
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

            // ========== TASK 2 & 3: LINK TO SCHEDULE & SET WORKFLOW STATUS ==========
            // Create inspection with approved schedule and pending_ro status
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
                // ========== TASK 2: LINK FMEA TO SCHEDULE ==========
                'schedule_id' => $approvedSchedule->id, // Mandatory from approved schedule
                // ========== TASK 4: SET WORKFLOW STATUS ==========
                'status_workflow' => 'pending_ro', // Change from draft to pending_ro
            ]);

            // ========== TASK 7: DYNAMIC OCCURRENCE ==========
            // Save FMEA Details with dynamic occurrence values
            foreach ($results as $result) {
                InspeksiFmeaDetail::create([
                    'inspeksi_id' => $inspeksi->id,
                    'item' => $result['item'],
                    'severity' => $result['S'],
                    'occurrence' => $result['O'], // Dynamic value from request
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
    'selected_segment' => $request->segment_inspeksi
]);

            return redirect('/fmeaoutput')->with('success', 'Data inspeksi berhasil disimpan dan menunggu approval Kepala RO.');
        }
        
        return view('fmea-demo', compact(
            'teknisi','approver','results','maxIndex','priority','schedule','availableSchedules'
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

    // ambil inspeksi terakhir dari segment
    $latestInspeksi = InspeksiHeader::where('segment_inspeksi', $selectedSegment)
        ->latest()
        ->first();

    if(!$latestInspeksi){
        return view('fmeaoutput',compact('segments','selectedSegment'))
            ->with('error','Data inspeksi belum ada');
    }
    // ambil detail inspeksi
    $details = $latestInspeksi->details;

    $results = [];

    foreach($details as $d){

        $status = json_decode($d->status,true);

        $S = 1;
        $O = 1;
        $D = 1;

        // ===== Kabel Putus =====
        if($d->objek == 'kabel_putus' && $status['status'] == 'ya'){
            $S = 5;

$O = $this->hitungOccurrence(
    $selectedSegment,
    'kabel_putus',
    'status',
    'ya'
);         
   $D = 1;
        }
if($d->objek == 'kabel_expose' && $status['status'] == 'ada'){
    $S = 4;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'kabel_expose',
    'status',
    'ada'
);    $D = 2;
}

if($d->objek == 'penyangga' && $status['status'] == 'rusak'){
    $S = 4;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'penyangga',
    'status',
    'rusak'
);
    $D = 2;
}

        // ===== Tiang =====
        if($d->objek == 'tiang' && $status['posisi'] == 'miring'){
            $S = 3;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'tiang',
    'posisi',
    'miring'
);            
$D = 1;
        }

        // ===== Clamp =====
        if($d->objek == 'clamp' && $status['status'] == 'rusak'){
            $S = 4;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'clamp',
    'status',
    'rusak'
);
            $D = 2;
        }
if($d->objek == 'lingkungan' && $status['status'] == 'tidak_aman'){
    $S = 3;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'lingkungan',
    'status',
    'tidak_aman'
);    $D = 3;
}
if($d->objek == 'vegetasi' && $status['status'] == 'tidak_aman'){
    $S = 3;
$O = $this->hitungOccurrence(
    $selectedSegment,
    'vegetasi',
    'status',
    'tidak_aman'
);
    $D = 3;
}

       if($S == 1) continue;

        $RPN = $S * $O * $D;
        $index = $RPN / 75;

      

        $results[] = [
            'item'=>$d->objek,
            'S'=>$S,
            'O'=>$O,
            'D'=>$D,
            'RPN'=>$RPN,
            'index'=>$index
        ];
    }

    if(empty($results)){
    return view('fmeaoutput',compact(
        'segments',
        'selectedSegment'
    ))->with('info','Tidak ada kerusakan pada inspeksi ini');
}

    $maxIndex = collect($results)->max('index');

    if($maxIndex >= 0.8){
        $priority = 'KRITIS';
        $schedule = 'minimal pm 3x sebulan';
    }elseif($maxIndex >= 0.4){
        $priority = 'SEDANG';
        $schedule = 'minimal pm 2x sebulan';
    }else{
        $priority = 'RENDAH';
        $schedule = 'minimal pm 1x sebulan';
    }

    $latestInspeksi->update([
        'priority'=>$priority,
        'schedule_pm'=>$schedule
    ]);

    return view('fmeaoutput',compact(
        'results',
        'priority',
        'schedule',
        'maxIndex',
        'segments',
        'selectedSegment'
    ));
}
private function hitungOccurrence($segment, $objek, $field, $value)
{
    $inspeksiIds = InspeksiHeader::where('segment_inspeksi', $segment)
        ->pluck('id');

$details = InspeksiDetail::whereIn('inspeksi_id',$inspeksiIds)   
     ->where('objek',$objek)
        ->get();

    $jumlah = 0;

    foreach($details as $d){

        $status = json_decode($d->status,true);

        if(isset($status[$field]) && $status[$field] == $value){
            $jumlah++;
        }
    }

    // konversi jumlah kejadian ke skala FMEA
    if($jumlah >=5) return 5;
    if($jumlah >=3) return 4;
    if($jumlah >=2) return 3;
    if($jumlah ==1) return 2;

    return 1;
}


}