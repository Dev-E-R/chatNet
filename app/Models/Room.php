<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'allText', 'allowedusers'];

    // Obtener último mensaje
    public function getLastMessageAttribute()
    {
        if (empty($this->allText)) return null;

        $messages = explode('|', $this->allText);
        $last = end($messages);

        if (preg_match('/\{id:(\d+),text:"(.+?)",time:(\d+)\}/', $last, $m)) {
            return [
                'user_id' => $m[1],
                'text' => str_replace('\\"', '"', $m[2]),
                'time' => $m[3],
            ];
        }
        return null;
    }
}
