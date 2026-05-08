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

public function scopeUrgent($query)
    {
        return $query->where('status', '!=', 'done')
                     ->where('deadline', '<=', now()->addHours(48));
    }

    public function scopeForDeveloper($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

}
