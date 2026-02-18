<?php

namespace App\Http\Controllers;

use App\Models\PmSchedule;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PmScheduleController extends Controller
{
    public function index()
    {
        $schedules = PmSchedule::with(['creator', 'approver'])->get();
        return view('admin.pm-schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('admin.pm-schedules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'segment_inspeksi' => 'required|string|max:150',
            'planned_date' => 'required|date|after:today',
            'priority' => 'nullable|string|max:50',
        ]);

        $schedule = PmSchedule::create([
            'segment_inspeksi' => $request->segment_inspeksi,
            'planned_date' => $request->planned_date,
            'priority' => $request->priority,
            'created_by' => Auth::id(),
            'status' => 'draft',
        ]);

        return redirect()->route('pm-schedules.index')->with('success', 'Jadwal PM berhasil dibuat.');
    }

    public function submitForApproval($id)
    {
        $schedule = PmSchedule::findOrFail($id);

        if ($schedule->status !== 'draft') {
            return back()->with('error', 'Jadwal sudah dalam proses approval.');
        }

        $schedule->update(['status' => 'pending']);

        // Create approval for teknisi (assuming current user is teknisi)
        Approval::create([
            'approvable_type' => PmSchedule::class,
            'approvable_id' => $schedule->id,
            'approver_id' => Auth::id(),
            'role' => 'teknisi',
            'status' => 'pending',
            'approved_at' => null,
        ]);

        return back()->with('success', 'Jadwal PM dikirim untuk approval.');
    }

    public function approve($id, Request $request)
    {
        $schedule = PmSchedule::findOrFail($id);
        $user = Auth::user();

        DB::transaction(function () use ($schedule, $user, $request) {
            $role = $this->getUserRole($user);

            if (!$role) {
                throw new \Exception('User tidak memiliki role yang valid untuk approval.');
            }

            // Create approval record
            Approval::create([
                'approvable_type' => PmSchedule::class,
                'approvable_id' => $schedule->id,
                'approver_id' => $user->id,
                'role' => $role,
                'status' => 'approved',
                'comments' => $request->comments,
                'signature' => $request->signature,
                'approved_at' => now(),
            ]);

            // Update schedule status based on approval hierarchy
            $this->updateScheduleStatus($schedule, $role);
        });

        return back()->with('success', 'Jadwal PM berhasil disetujui.');
    }

    public function reject($id, Request $request)
    {
        $schedule = PmSchedule::findOrFail($id);
        $user = Auth::user();

        $role = $this->getUserRole($user);

        Approval::create([
            'approvable_type' => PmSchedule::class,
            'approvable_id' => $schedule->id,
            'approver_id' => $user->id,
            'role' => $role,
            'status' => 'rejected',
            'comments' => $request->comments,
        ]);

        $schedule->update(['status' => 'rejected']);

        return back()->with('success', 'Jadwal PM ditolak.');
    }

    private function getUserRole($user)
    {
        // Assuming role is stored in user model
        return $user->role;
    }

    private function updateScheduleStatus($schedule, $role)
    {
        if ($role === 'pusat') {
            $schedule->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
            ]);
        } elseif ($role === 'kepala_ro') {
            $schedule->update(['status' => 'pending_pusat']);
        }
        // teknisi approval already handled in submitForApproval
    }

    public function show($id)
    {
        $schedule = PmSchedule::with(['creator', 'approver', 'approvals.approver', 'inspeksiHeaders'])->findOrFail($id);
        return view('admin.pm-schedules.show', compact('schedule'));
    }

    public function edit($id)
    {
        $schedule = PmSchedule::findOrFail($id);
        return view('admin.pm-schedules.edit', compact('schedule'));
    }

    public function update(Request $request, $id)
    {
        $schedule = PmSchedule::findOrFail($id);

        $request->validate([
            'segment_inspeksi' => 'required|string|max:150',
            'planned_date' => 'required|date',
            'priority' => 'nullable|string|max:50',
        ]);

        $schedule->update($request->only(['segment_inspeksi', 'planned_date', 'priority']));

        return redirect()->route('pm-schedules.index')->with('success', 'Jadwal PM berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $schedule = PmSchedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('pm-schedules.index')->with('success', 'Jadwal PM berhasil dihapus.');
    }
}