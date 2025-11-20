<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/check-filament-notifications', function() {
    $user = auth()->user();
    
    // 1. Ver notificaciones del modelo estándar
    $standardNotifications = $user->notifications;
    
    // 2. Ver notificaciones de tu modelo personalizado
    $customNotifications = \App\Models\CustomNotification::where('notifiable_id', $user->id)
        ->orWhere('notifiable_id', $user->id)
        ->get();
    
    // 3. Ver estructura de datos
    $sampleStandard = $standardNotifications->first();
    $sampleCustom = $customNotifications->first();
    
    return [
        'user_id' => $user->id,
        'standard_count' => $standardNotifications->count(),
        'custom_count' => $customNotifications->count(),
        'standard_sample' => $sampleStandard ? [
            'id' => $sampleStandard->id,
            'type' => $sampleStandard->type,
            'data' => $sampleStandard->data,
            'read_at' => $sampleStandard->read_at,
        ] : null,
        'custom_sample' => $sampleCustom ? [
            'id' => $sampleCustom->id,
            'type' => $sampleCustom->type,
            'data' => $sampleCustom->data,
            'read_at' => $sampleCustom->read_at,
            //'is_read' => $sampleCustom->is_read,
        ] : null,
    ];
});
