<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FirmanteGenerico extends Model
{
    use HasFactory;

    public function user(){
        return $this->belongsTo(Usuario::class);
    }
    public function teams(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
