<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class task extends Model
{
     protected $fillable = [
        'titre',
        'description',
        'deadline',
        'priorite',
        'statut',
        'projet_id',
        'user_id'
    ];

     public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
