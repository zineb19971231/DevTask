<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->members()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Allow if role is lead OR if role is not set (defaulting to lead for safety during migration)
        return $user->role === 'lead' || is_null($user->role);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->members()->where('user_id', $user->id)->wherePivot('role', 'lead')->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    public function restore(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return $this->update($user, $project);
    }
}
