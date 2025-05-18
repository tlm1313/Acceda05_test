<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    //

     protected $fillable = ['user_id', 'tipo'];
     // app/Models/Registro.php
     protected $casts = [
        'fecha_hora' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
        ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
