<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventLog extends Model
{
    protected $fillable = [
        'type',
        'payload',
        'tenant_id',
        'user_id',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
