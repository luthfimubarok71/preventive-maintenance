<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use App\Models\PmSchedule;
use App\Models\InspeksiHeader;
use App\Models\Approval;

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Workflow Relations and Logic\n";
echo "=====================================\n\n";

// Test 1: Create a PM Schedule
echo "1. Creating PM Schedule...\n";
$schedule = PmSchedule::create([
    'segment_inspeksi' => 'Test Segment A',
    'planned_date' => now()->addDays(1),
    'priority' => 'SEDANG',
    'created_by' => 1, // Assuming user ID 1 exists
    'status' => 'draft'
]);
echo "   Schedule created with ID: {$schedule->id}\n\n";

// Test 2: Create an Inspection linked to schedule
echo "2. Creating Inspection linked to schedule...\n";
$inspection = InspeksiHeader::create([
    'segment_inspeksi' => 'Test Segment A',
    'tanggal_inspeksi' => now(),
    'nama_pelaksana' => 'Test User',
    'schedule_id' => $schedule->id,
    'status_workflow' => 'draft'
]);
echo "   Inspection created with ID: {$inspection->id}\n\n";

// Test 3: Test relations
echo "3. Testing Relations...\n";
$schedule->refresh();
$inspection->refresh();

$scheduleInspections = $schedule->inspeksiHeaders()->count();
$inspectionSchedule = $inspection->pmSchedule ? $inspection->pmSchedule->segment_inspeksi : 'None';

echo "   Schedule has {$scheduleInspections} inspections\n";
echo "   Inspection belongs to schedule: {$inspectionSchedule}\n\n";

// Test 4: Test approval creation
echo "4. Testing Approval Creation...\n";
$approval = Approval::create([
    'approvable_type' => PmSchedule::class,
    'approvable_id' => $schedule->id,
    'approver_id' => 1,
    'role' => 'teknisi',
    'status' => 'approved',
    'approved_at' => now()
]);
echo "   Approval created with ID: {$approval->id}\n\n";

// Test 5: Test risk summary
echo "5. Testing Risk Summary...\n";
// Create some FMEA details
$inspection->fmeaDetails()->create([
    'item' => 'Test Item 1',
    'severity' => 8,
    'occurrence' => 5,
    'detection' => 3,
    'rpn' => 120,
    'risk_index' => 0.85
]);

$inspection->fmeaDetails()->create([
    'item' => 'Test Item 2',
    'severity' => 6,
    'occurrence' => 4,
    'detection' => 2,
    'rpn' => 48,
    'risk_index' => 0.45
]);

$inspection->refresh();
$riskSummary = $inspection->risk_summary;
echo "   Risk Summary: Priority - {$riskSummary['priority']}, Recommendation - {$riskSummary['recommendation']}\n\n";

// Test 6: Test inspection restriction logic (simulate)
echo "6. Testing Inspection Restriction Logic...\n";
$approvedSchedule = PmSchedule::where('segment_inspeksi', 'Test Segment A')
    ->where('status', 'approved')
    ->where('planned_date', today())
    ->first();

if ($approvedSchedule) {
    echo "   Approved schedule found for today - inspection allowed\n";
} else {
    echo "   No approved schedule for today - inspection blocked\n";
}

echo "\nWorkflow Testing Complete!\n";