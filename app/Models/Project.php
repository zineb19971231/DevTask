<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'projets';

    protected $fillable = [
        'titre',
        'description',
        'deadline',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function members()
    {
        return $this->users();
    }

   

    public function getTitleAttribute()
    {
        return $this->titre;
    }

    public function setTitleAttribute($value)
    {
        $this->titre = ucfirst($value);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user', 'projet_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    //  Un projet a plusieurs tâches
    public function tasks()
    {
        return $this->hasMany(Task::class, 'projet_id');
    }

    //  Helper : retourne uniquement le lead du projet
    public function lead()
    {
        return $this->members()
                    ->wherePivot('role', 'lead')
                    ->first();
    }
}