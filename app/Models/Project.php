<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'deadline',
    ];

    //  BONUS — Mutator : stocke le titre en Ucfirst automatiquement
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = ucfirst($value);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    //  Un projet a plusieurs tâches
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    //  Helper : retourne uniquement le lead du projet
    public function lead()
    {
        return $this->members()
                    ->wherePivot('role', 'lead')
                    ->first();
    }
}