<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$columns = collect(DB::connection('Obras')->getDoctrineSchemaManager()->listTableColumns('users'))
    ->keys()
    ->sort()
    ->values()
    ->all();

echo 'COLUMNS=' . json_encode($columns) . PHP_EOL;

$hasInterno = in_array('interno', $columns, true);
echo 'HAS_INTERNO=' . ($hasInterno ? 'yes' : 'no') . PHP_EOL;

if ($hasInterno) {
    $users = App\Models\User::query()->orderBy('name')->get(['id','name','email','interno']);
    foreach ($users as $user) {
        echo $user->id . ' | ' . $user->name . ' | ' . $user->email . ' | ' . ($user->interno ? 'true' : 'false') . PHP_EOL;
    }
} else {
    $users = App\Models\User::query()->orderBy('name')->get(['id','name','email']);
    foreach ($users as $user) {
        echo $user->id . ' | ' . $user->name . ' | ' . $user->email . PHP_EOL;
    }
}
