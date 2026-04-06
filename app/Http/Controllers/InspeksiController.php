<?php

namespace App\Http\Controllers;

use App\Models\InspeksiHeader;
use App\Models\PmSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InspeksiController extends Controller
{
    /**
     * Store new inspection - enforces schedule requirement and sets workflow status
     */
public function store(Request $request)
{
    $request->validate([
        'segment_inspeksi' => 'required|string|max:150',
        'tanggal_inspeksi' => 'required|date',
        'schedule_id' => 'required|exists:pm_schedules,id'
    ]);

    $schedule = PmSchedule::findOrFail($request->schedule_id);

    $status = $request->action === 'submit_ro'
        ? 'pending_ro'
        : 'draft';

    DB::transaction(function () use ($request, $schedule, $status) {

        $inspeksi = InspeksiHeader::create([
            'segment_inspeksi' => $request->segment_inspeksi,
            'jalur_fo' => $request->jalur_fo,
            'nama_pelaksana' => $request->nama_pelaksana,
            'driver' => $request->driver,
            'cara_patroli' => $request->cara_patroli,
            'cara_patroli_lainnya' => $request->cara_patroli_lainnya,
            'tanggal_inspeksi' => $request->tanggal_inspeksi,
            'prepared_by' => Auth::id(),
            'approved_by' => $request->approved_by,
            'prepared_signature' => $request->prepared_canvas,
            'approved_signature' => $request->approved_canvas,
            'schedule_id' => $schedule->id,
            'status_workflow' => $status
        ]);

        $objects = [
            'kabel_putus',
            'kabel_expose',
            'penyangga',
            'tiang',
            'clamp',
            'lingkungan',
            'vegetasi',
            'marker_post',
            'hand_hole',
            'aksesoris_ku',
            'jc_odp'
        ];

        foreach ($objects as $obj) {

            if ($request->has($obj)) {

                $data = $request->$obj;

                if (!is_array($data)) {
                    $data = ['status' => $data];
                }

                $inspeksi->details()->create([
                    'objek' => $obj,
                    'status' => json_encode($data),
                    'catatan' => $request->kondisi[$obj]['catatan'] ?? null
                ]);
            }
        }

        if ($request->has('kondisi_umum')) {
            $inspeksi->kondisiUmum()->create($request->kondisi_umum);
        }

        if ($request->has('fmea_details')) {
            foreach ($request->fmea_details as $detail) {
                $inspeksi->fmeaDetails()->create($detail);
            }
        }

    });

    return redirect('/tasks')->with(
        'success',
        'Laporan berhasil disimpan'
    );



}

    /**
     * Submit inspection for RO approval (draft → pending_ro)
     */
    public function submitForApproval($id)
    {
        $inspeksi = InspeksiHeader::findOrFail($id);
        
        // Validate current status
        if ($inspeksi->status_workflow !== 'draft') {
            return back()->with('error', 'Inspeksi tidak dapat dikirim untuk approval dalam status saat ini.');
        }

        // Check if has schedule
        if (!$inspeksi->schedule_id) {
            return back()->with('error', 'Inspeksi harus terhubung dengan jadwal PM yang disetujui.');
        }

        // Update status to pending_ro
        $inspeksi->update(['status_workflow' => 'pending_ro']);

        return back()->with('success', 'Inspeksi berhasil dikirim untuk approval.');
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

    /**
     * Display inspection reports created by the logged-in technician
     */
    public function myReports()
    {
        // Get only reports created by the logged-in technician
        $reports = InspeksiHeader::where('prepared_by', Auth::id())
            ->with(['pmSchedule', 'approvals'])
            ->orderBy('tanggal_inspeksi', 'desc')
            ->get();

        return view('inspeksi.my-reports', compact('reports'));
    }

  public function approveByRo(Request $request,$id)
{
    $report = InspeksiHeader::findOrFail($id);

    $report->update([
        'approved_signature' => $request->signature_ro,
         // simpan user yang approve
        'status_workflow' => 'pending_pusat'
    ]);

    return back()->with('success','Report disetujui oleh Kepala RO.');
}

public function rejectByRo(Request $request,$id)
{

$report = InspeksiHeader::findOrFail($id);

$report->update([

'status_workflow'=>'rejected'

]);

return back()->with('success','Report ditolak oleh Kepala RO.');

}

public function approveByPusat($id)
{
    $report = InspeksiHeader::findOrFail($id);

    if ($report->status_workflow !== 'pending_pusat') {
        return back()->with('error','Report tidak dalam status pending pusat.');
    }

    $report->update([
        'status_workflow' => 'approved'
    ]);

    return back()->with('success','Report disetujui oleh pusat.');
}


public function rejectByPusat($id)
{
    $report = InspeksiHeader::findOrFail($id);

    $report->update([
        'status_workflow' => 'rejected'
    ]);

    return back()->with('success','Report ditolak oleh pusat.');
}

public function pendingRO()
{
    $reports = InspeksiHeader::where('status_workflow','pending_ro')
        ->with(['pmSchedule.segment'])
        ->latest()
        ->get();

    return view('approval.ro-reports', compact('reports'));
}

public function pendingPusat()
{
    $reports = InspeksiHeader::where('status_workflow','pending_pusat')
        ->with(['pmSchedule.segment'])
        ->latest()
        ->get();

    return view('approval.pusat-reports', compact('reports'));

}

public function modal($id)
{
    $report = InspeksiHeader::with([
        'pmSchedule.segment',
        'kondisiUmum',
        'fmeaDetails',
        'details'
    ])->findOrFail($id);

    return view('inspeksi.modal-report', compact('report'));
}
}