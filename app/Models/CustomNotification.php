<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Str;

/**
 * Notificación personalizada del sistema basada en DatabaseNotification.
 *
 * Extiende las notificaciones de Laravel para almacenar título, mensaje y
 * tipo en el campo 'data', con accessors de presentación para Filament.
 * Tabla 'notifications' en la conexión 'Obras' (SQL Server) con clave
 * primaria UUID string. Relación polimórfica 'notifiable' (User, Team...).
 */
class CustomNotification extends DatabaseNotification
{
    use HasFactory;
    protected $connection='Obras';
    protected $table='notifications';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = [
        'id',
        'type',
        'read_at',
        'data',
        'notifiable_type',
        'notifiable_id',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array',

    ];
    /** Genera automáticamente un UUID como clave primaria al crear el registro. */
    protected static function boot() {
        parent::boot();
        static::creating(function ($model)
        {
            if (!$model->getKey())
                { $model->{$model->getKeyName()} = (string) Str::uuid();
                }
        });
     }
    // Marcar como leída
    /** Marca la notificación como leída estableciendo read_at. */
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update([

                'read_at' => now(),
            ]);
        }
    }

    // Marcar como no leída
    /** Marca la notificación como no leída limpiando read_at. */
    public function markAsUnread(): void
    {

        if ($this->read_at) {
            $this->update([

                'read_at' => null,
            ]);
        }
    }
    // Scope para notificaciones no leídas
    /** Scope: filtra solo las notificaciones no leídas. */
    public function scopeUnread($query)
    {
        return $query->where('read_at', null);
    }

    // Scope para notificaciones de un usuario

    /** Relación polimórfica con la entidad notificada (User, Team...). */
    public function notifiable()
    {
        return $this->morphTo();
    }
    /** Crea una notificación garantizando que 'data' contenga título, mensaje y tipo. */
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
    /** Accessor: mensaje almacenado en 'data'. */
    public function getMessageAttribute(): string
    {
        return $this->data['message'] ?? '';
    }

    /** Accessor: título almacenado en 'data'. */
    public function getTitleAttribute(): string
    {
        return $this->data['title'] ?? '';
    }

    /** Accessor: tipo de la notificación (info, success, warning, danger). */
    public function getTypeAttribute(): string
    {
        return $this->data['type'] ?? $this->attributes['type'] ?? '';
    }

    /** Accessor: icono heroicon según el tipo de notificación. */
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

    /** Accessor: color de icono según el tipo de notificación. */
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

    /** Accessor: fecha de creación en formato relativo legible. */
    public function getDateAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /** Accessor: indica si la notificación ya ha sido leída. */
    public function getReadAttribute(): bool
    {
        return !is_null($this->read_at);
    }
}

