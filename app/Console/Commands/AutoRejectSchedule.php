<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoRejectSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-reject-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
   
{
    $today = now()->toDateString();

    $schedules = \App\Models\PmSchedule::where('status', 'approved')
        ->whereDate('planned_date', '<', $today)
        ->get();

    foreach ($schedules as $schedule) {

        // ✅ CEK: apakah sudah pernah dibuat inspeksi (status apapun)
        $hasInspeksi = \App\Models\InspeksiHeader::where('schedule_id', $schedule->id)
            ->exists();

        // 🔥 HANYA reject kalau BELUM ADA inspeksi sama sekali
        if (!$hasInspeksi) {

            $schedule->update([
                'status' => 'rejected'
            ]);

        }
    }

    return 0;
}
    }