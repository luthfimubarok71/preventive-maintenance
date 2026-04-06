<?php

namespace App\Http\Controllers;
 use Illuminate\Support\Facades\Auth;
 use App\Models\Segment;
 use App\Models\PmSchedule;
 use App\Models\User;
class MaintenanceTaskController extends Controller
{



public function index()
{
    $userId = Auth::id();

  $segments = Segment::with(['schedules' => function ($query) use ($userId) {

    $query->where('status', 'approved')
          ->where(function ($q) use ($userId) {
              $q->where('teknisi_1', $userId)
                ->orWhere('teknisi_2', $userId);
          })
          ->with('inspeksiHeader')   // penting
          ->orderBy('planned_date');

}])->get();

    return view('task.index', compact('segments'));
}




public function show($schedule)
{
    $schedule = PmSchedule::with('segment')->findOrFail($schedule);

    $teknisi = User::where('role','teknisi')->get();
    $approver = User::whereIn('role',['kepala_ro','admin'])->get();

    return view('fmea-demo', compact('schedule','teknisi','approver'));
}

public function info()
{
    $segments = Segment::with(['schedules' => function ($query) {

        $query->where('status','approved')
              ->with('inspeksiHeader')
              ->orderBy('planned_date');

    }])->get();

    return view('maintenance.info', compact('segments'));
}

}