<?php

namespace App\Http\Controllers;

use App\Models\InspeksiHeader;
use App\Models\PmSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InspeksiController extends Controller
{
    public function store(Request $request)
    {
        // Check if there's an approved schedule for today and segment
        $approvedSchedule = PmSchedule::where('segment_inspeksi', $request->segment_inspeksi)
            ->where('status', 'approved')
            ->where('planned_date', today())
            ->first();

        if (!$approvedSchedule) {
            return back()->with('error', 'Tidak ada jadwal PM yang disetujui untuk segment ini hari ini.');
        }

        DB::transaction(function () use ($request, $approvedSchedule) {
            // Create inspection header linked to approved schedule
            $inspeksi = InspeksiHeader::create([
                'segment_inspeksi' => $request->segment_inspeksi,
                'jalur_fo' => $request->jalur_fo,
                'nama_pelaksana' => $request->nama_pelaksana,
                'driver' => $request->driver,
                'cara_patroli' => $request->cara_patroli,
                'cara_patroli_lainnya' => $request->cara_patroli_lainnya,
                'tanggal_inspeksi' => $request->tanggal_inspeksi,
                'priority' => $request->priority,
                'schedule_pm' => $request->schedule_pm,
                'prepared_by' => $request->prepared_by,
                'approved_by' => $request->approved_by,
                'prepared_signature' => $request->prepared_signature,
                'approved_signature' => $request->approved_signature,
                'schedule_id' => $approvedSchedule->id,
                'status_workflow' => 'draft', // Start as draft, will go through approval
            ]);

            // Save kondisi umum if provided
            if ($request->has('kondisi_umum')) {
                $inspeksi->kondisiUmum()->create($request->kondisi_umum);
            }

            // Save FMEA details if provided
            if ($request->has('fmea_details')) {
                foreach ($request->fmea_details as $detail) {
                    $inspeksi->fmeaDetails()->create($detail);
                }
            }
        });

        return back()->with('success', 'Data inspeksi berhasil disimpan dan menunggu approval.');
    }

    public function riskSummary()
    {
        $segments = InspeksiHeader::with('fmeaDetails')
            ->select('segment_inspeksi')
            ->groupBy('segment_inspeksi')
            ->get()
            ->map(function ($segment) {
                $inspeksi = InspeksiHeader::where('segment_inspeksi', $segment->segment_inspeksi)
                    ->with('fmeaDetails')
                    ->latest()
                    ->first();

                return [
                    'segment' => $segment->segment_inspeksi,
                    'risk_summary' => $inspeksi ? $inspeksi->risk_summary : null,
                    'last_inspection' => $inspeksi ? $inspeksi->tanggal_inspeksi : null,
                ];
            });

        return view('admin.risk-summary', compact('segments'));
    }
}