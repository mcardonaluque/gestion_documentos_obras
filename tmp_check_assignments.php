<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$assignments = App\Models\ExpedienteUserAssignment::query()->with(['expediente', 'user'])->orderByDesc('created_at')->limit(10)->get();

foreach ($assignments as $assignment) {
    $expediente = $assignment->expediente;
    echo 'ASSIGNMENT=' . $assignment->id . ' | expediente_id=' . $assignment->expediente_id . ' | related=' . ($expediente ? 'yes' : 'no') . ' | nombre_obra=' . json_encode($expediente?->nombre_obra) . PHP_EOL;
}
