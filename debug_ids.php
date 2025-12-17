<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "---DATA_START---" . PHP_EOL;
echo json_encode([
    'shifts' => App\Models\Shift::all(['id', 'name', 'type', 'start_time', 'end_time', 'break_start_time', 'break_end_time']),
    'employees' => App\Models\Employee::with('shift')->get()->map(function($e) {
        return [
            'id' => $e->id,
            'name' => $e->personalInfo->first_name ?? 'Unknown',
            'shift' => $e->shift ? $e->shift->name : 'None'
        ];
    })
], JSON_PRETTY_PRINT);
echo PHP_EOL . "---DATA_END---";
