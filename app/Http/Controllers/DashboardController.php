<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Projects where this user is a member (any role)
        $projects = $user->projects()
            ->with(['members'])
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn ($q) => $q->where('statut', 'done'),
                'tasks as tasks_assigned_to_me'   => fn ($q) => $q->where('user_id', $user->id),
            ])
            ->get();

        // Tasks assigned to this user, due soon — ordered by deadline
        $myTasks = \App\Models\Task::with('project')
            ->forDeveloper($user->id)
            ->where('statut', '!=', 'done')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // Stats
        if ($user->role === 'lead') {
            $stats = [
                'total_projects' => $projects->count(),
                'active_tasks'   => \App\Models\Task::whereIn('statut', ['to do', 'doing'])->count(),
                'completed'      => \App\Models\Task::where('statut', 'done')->count(),
                'team_members'   => \App\Models\User::count(), // Simple count for now
            ];
        } else {
            $stats = [
                'total_projects' => $projects->count(),
                'active_tasks'   => \App\Models\Task::forDeveloper($user->id)
                                        ->whereIn('statut', ['to do', 'doing'])
                                        ->count(),
                'completed_week' => \App\Models\Task::forDeveloper($user->id)
                                        ->where('statut', 'done')
                                        ->whereBetween('updated_at', [
                                            now()->startOfWeek(),
                                            now()->endOfWeek(),
                                        ])
                                        ->count(),
            ];
        }

        return view('dashboard', compact('projects', 'myTasks', 'stats'));
    }

    public function team()
    {
        $users = \App\Models\User::withCount(['tasks', 'projects'])->get();
        return view('team', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'developer', // Default role for recruited members
        ]);

        return back()->with('success', 'Team member added successfully.');
    }

    public function destroyUser(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot remove yourself.');
        }

        $user->delete();
        return back()->with('success', 'Team member removed successfully.');
    }
}