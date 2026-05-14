<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
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

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

     public function project()
    {
        return $this->belongsTo(Project::class, 'projet_id');
    }

    public function getTitleAttribute() { return $this->titre; }
    public function setTitleAttribute($value) { $this->titre = $value; }

    public function getStatusAttribute()
    {
        return str_replace(['to do', 'doing'], ['todo', 'in_progress'], $this->statut);
    }

    public function setStatusAttribute($value)
    {
        $this->statut = str_replace(['todo', 'in_progress'], ['to do', 'doing'], $value);
    }

    public function getPriorityAttribute()
    {
        return str_replace(['basse', 'moyenne', 'elevee'], ['low', 'medium', 'high'], $this->priorite);
    }

    public function setPriorityAttribute($value)
    {
        $this->priorite = str_replace(['low', 'medium', 'high'], ['basse', 'moyenne', 'elevee'], $value);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsUrgentAttribute()
    {
        return $this->statut !== 'done' 
            && $this->deadline 
            && \Carbon\Carbon::parse($this->deadline)->isBefore(now()->addHours(48));
    }

    public function scopeUrgent($query)
    {
        return $query->where('statut', '!=', 'done')
                     ->where('deadline', '<=', now()->addHours(48));
    }

    public function scopeForDeveloper($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
