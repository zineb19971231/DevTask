<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'deadline',
        'project_id',
        'assigned_to',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'todo'        => 'À faire',
            'in_progress' => 'En cours',
            'done'        => 'Terminé',
            default       => $this->status,
        };
    }

    public function getDeadlineStatusAttribute(): string
    {
        if (!$this->deadline) return 'normal';

        return $this->deadline->diffInHours(now()) < 48 && $this->status !== 'done'
            ? 'urgent'
            : 'normal';
    }

    public function scopeUrgent($query)
    {
        return $query->where('deadline', '<=', now()->addHours(48))
                     ->where('status', '!=', 'done');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}