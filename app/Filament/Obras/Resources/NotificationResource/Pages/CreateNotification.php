<?php

namespace App\Filament\Obras\Resources\NotificationResource\Pages;


use App\Filament\Obras\Resources\NotificationResource;
use App\Models\CustomNotification;
use App\Models\Team;
use App\Models\User;
use App\Notifications\GenericDatabaseNotification;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;
use Filament\Notifications\Notification as FilamentNotification;


class CreateNotification extends CreateRecord
{
    protected static string $resource =NotificationResource::class;
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // 🔹 Lógica de CustomNotificationes

       
        // 1. A todos los usuarios
        if (!empty($data['to_all']) && $data['to_all']) {
           foreach(User::all() as $userId){
                $notification=New GenericDatabaseNotification(
                   // 'id' => Str::uuid(),
                   // 'notifiable_type' => User::class,
                   // 'notifiable_id' => $userId->id,
                   // 'recipient_id' => $userId->id,
                    //'title' =>
                     $data['title'],
                    //'message' =>
                     $data['message'],
                    //'sender_id' => auth()->id(),
                    //'type' => 
                    $data['type'],
                    //'data' => [],
                );
               
                Notification::send($userId, $notification);
                FilamentNotification::make()->title($data['title'])
                    ->body($data['message'])
                    ->success()
                    ->sendToDatabase($userId);
              }
                 return CustomNotification::where('notifiable_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
            // Devuelve el primer registro creado (para que Filament no falle)
            
        

        // 2. A un equipo
        if (!empty($data['team_id'])) {
            $team = Team::find($data['team_id']);
            foreach ($team->users as $userId) {
                $notification=New GenericDatabaseNotification(
                    
                    $data['title'],
                     $data['message'],
                      $data['type'],
                    
                );
                Notification::send($userId, $notification);
                FilamentNotification::make()->title($data['title'])
                    ->body($data['message'])
                    ->success()
                    ->sendToDatabase($userId);
            }
            return CustomNotification::where('notifiable_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // 3. A usuarios seleccionados
        if (!empty($data['user_ids'])) {
            $users = User::whereIn('id', $data['user_ids'])->get();
            foreach ($users as $userId) {
                $notification=New GenericDatabaseNotification(
                    
                    $data['title'],
                    $data['message'],
                    $data['type'],
                    
                );
                
                Notification::send($userId, $notification);
                FilamentNotification::make()->title($data['title'])
                ->body($data['message'])
                ->success()
                ->sendToDatabase($userId);
            }
            return CustomNotification::where('notifiable_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->first();
        }

        
        return CustomNotification::where('notifiable_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->first();
    }
}
