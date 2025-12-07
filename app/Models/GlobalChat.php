<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalChat extends Model
{
    use HasFactory;

    protected $table = 'global_chat'; // Nombre de tabla en minúsculas

    protected $fillable = [
        'user_id',
        'message',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
