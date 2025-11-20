<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class CustomNotification extends Model
{
    use HasFactory,Notifiable;
    protected $connection='Obras';
    protected $table='notifications';
    protected $keyType = 'string'; 
    public $incrementing = false;
    protected $dateFormat = 'Y-m-d H:i:s'; 
    protected $fillable = [
        'type',
        'title',
        'message',
        'sender_id',
        'recipient_id',
        'is_read',
        'read_at',
        'data',
        'notifiable_type',
        'notifiable_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'data' => 'array',
        
    ];
    protected static function boot() { parent::boot(); static::creating(function ($model) { if (!$model->getKey()) { $model->{$model->getKeyName()} = (string) Str::uuid(); } }); }
    // Relación con el usuario que envía
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relación con el usuario que recibe
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    // Marcar como leída
    public function markAsRead(): void
    {  
        if (!$this->is_read) {
            $this->update([
               
                'read_at' => now(),
            ]);
        }
    }

    // Marcar como no leída
    public function markAsUnread(): void
    {
      
        if ($this->is_read) {
            $this->update([
                
                'read_at' => null,
            ]); 
        }
    }
    public function via(object $notifiable): array
    {
        return ['database']; // Puedes añadir 'mail', 'broadcast', etc.
    }
    // Scope para notificaciones no leídas
    public function scopeUnread($query)
    {
        return $query->where('read_at', null);
    }

    // Scope para notificaciones de un usuario
    
    public function notifiable()
    {
        return $this->morphTo();
    }
    public static function createCustomNotification(array $attributes)
    {
        // Asegurar que 'data' sea un array
        $attributes['data'] = $attributes['data'] ?? [
            'title' => $attributes['title'] ?? '',
            'message' => $attributes['message'] ?? '',
            'type' => $attributes['type'] ?? 'info',
        ];

        return static::create($attributes);
    }
    public function getMessageAttribute(): string 
    {
        return $this->data['message'] ?? '';
    }

    public function getTitleAttribute(): string 
    {
        return $this->data['title'] ?? '';
    }

    public function getSenderIdAttribute(): ?int
    {
        return $this->data['sender_id'] ?? null;
    }
    public function getTypeAttribute(): string 
{
    return $this->data['type'] ?? '';
}
public function getIconAttribute(): string
{
    return match ($this->data['type'] ?? 'info') {
        'success' => 'heroicon-o-check-circle',
        'warning' => 'heroicon-o-exclamation-triangle', 
        'danger' => 'heroicon-o-x-circle',
        'info' => 'heroicon-o-information-circle',
        default => 'heroicon-o-bell',
    };
}

public function getIconColorAttribute(): string
{
    return match ($this->data['type'] ?? 'info') {
        'success' => 'success',
        'warning' => 'warning',
        'danger' => 'danger',
        'info' => 'info',
        default => 'primary',
    };
}

public function getDateAttribute(): string
{
    return $this->created_at->diffForHumans();
}

// ✅ Asegurar que Filament pueda determinar si está leída
public function getReadAttribute(): bool
{
    return !is_null($this->read_at) || $this->is_read;
}
}

