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
            ->withCount([
                'tasks',
                'tasks as done_tasks_count' => fn ($q) => $q->where('status', 'done'),
                'tasks as my_tasks_count'   => fn ($q) => $q->where('assigned_to', $user->id),
            ])
            ->get();

        // Tasks assigned to this user, due soon — ordered by deadline
        $myTasks = \App\Models\Task::with('project')
            ->forDeveloper($user->id)
            ->where('status', '!=', 'done')
            ->orderBy('deadline')
            ->take(5)
            ->get();

        // Stats
        $stats = [
            'projects'      => $projects->count(),
            'open_tasks'    => \App\Models\Task::forDeveloper($user->id)
                                    ->whereIn('status', ['todo', 'in_progress'])
                                    ->count(),
            'done_this_week' => \App\Models\Task::forDeveloper($user->id)
                                    ->where('status', 'done')
                                    ->whereBetween('updated_at', [
                                        now()->startOfWeek(),
                                        now()->endOfWeek(),
                                    ])
                                    ->count(),
        ];

        return view('dashboard', compact('projects', 'myTasks', 'stats'));
    }
}