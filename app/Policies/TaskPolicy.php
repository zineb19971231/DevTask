<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        return $task->project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user, Project $project): bool
    {
        return $project->members()->where('user_id', $user->id)->wherePivot('role', 'lead')->exists();
    }

    public function update(User $user, Task $task): bool
    {
        return $task->project->members()->where('user_id', $user->id)->wherePivot('role', 'lead')->exists();
    }

    public function updateStatus(User $user, Task $task): bool
    {
        // Assigned developer can update status
        if ($task->user_id === $user->id) {
            return true;
        }
        
        // Lead can also update status
        return $this->update($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }
}
