<?php

namespace App\Http\Controllers;

use App\Models\PmSchedule;
use App\Models\InspeksiHeader;
use App\Models\Approval;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalDashboardController extends Controller
{
    public function dashboard()
    {
        return view('approval.dashboard');
    }

    public function pendingSchedules()
    {
        $schedules = PmSchedule::where('status', 'pending')
            ->with('creator')
            ->get();

        return view('approval.pending-schedules', compact('schedules'));
    }

    public function pendingReports()
    {
        $reports = InspeksiHeader::whereIn('status_workflow', ['pending_ro', 'pending_pusat'])
            ->with('pmSchedule.creator')
            ->get();

        return view('approval.pending-reports', compact('reports'));
    }

    public function approvedHistory()
    {
        $approvedSchedules = PmSchedule::where('status', 'approved')
            ->with('creator', 'approvals')
            ->get();

        $approvedReports = InspeksiHeader::where('status_workflow', 'approved')
            ->with('pmSchedule.creator', 'approvals')
            ->get();

        return view('approval.history', compact('approvedSchedules', 'approvedReports'));
    }

    public function rejectedData()
    {
        $rejectedSchedules = PmSchedule::where('status', 'rejected')
            ->with('creator', 'approvals')
            ->get();

        $rejectedReports = InspeksiHeader::where('status_workflow', 'rejected')
            ->with('pmSchedule.creator', 'approvals')
            ->get();

        return view('approval.rejected', compact('rejectedSchedules', 'rejectedReports'));
    }

    public function approveSchedule($id)
    {
        $schedule = PmSchedule::findOrFail($id);
        $schedule->update(['status' => 'approved']);

        Approval::create([
            'approvable_type' => PmSchedule::class,
            'approvable_id' => $id,
            'approver_id' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => 'approved',
            'signature' => Auth::user()->username,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Schedule approved successfully.');
    }

    public function rejectSchedule($id)
    {
        $schedule = PmSchedule::findOrFail($id);
        $schedule->update(['status' => 'rejected']);

        Approval::create([
            'approvable_type' => PmSchedule::class,
            'approvable_id' => $id,
            'approver_id' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => 'rejected',
            'signature' => Auth::user()->username,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Schedule rejected.');
    }

    public function approveReport($id)
    {
        $report = InspeksiHeader::findOrFail($id);
        $report->update(['status_workflow' => 'approved']);

        Approval::create([
            'approvable_type' => InspeksiHeader::class,
            'approvable_id' => $id,
            'approver_id' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => 'approved',
            'signature' => Auth::user()->username,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Report approved successfully.');
    }

    public function rejectReport($id)
    {
        $report = InspeksiHeader::findOrFail($id);
        $report->update(['status_workflow' => 'rejected']);

        Approval::create([
            'approvable_type' => InspeksiHeader::class,
            'approvable_id' => $id,
            'approver_id' => Auth::id(),
            'role' => Auth::user()->role,
            'status' => 'rejected',
            'signature' => Auth::user()->username,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Report rejected.');
    }
}