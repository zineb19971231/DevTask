<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Auth::user()->projects()
            ->with(['members'])
            ->withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn ($q) => $q->where('statut', 'done'),
            ])
            ->get();

        // Calculate progress for each project
        $projects->each(function ($project) {
            $project->progress = $project->total_tasks > 0 
                ? round(($project->completed_tasks / $project->total_tasks) * 100) 
                : 0;
        });

        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $project = Project::create($validated);
        
        // Assign current user as lead
        $project->members()->attach(Auth::id(), ['role' => 'lead']);

        return redirect()->route('dashboard')->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        $user = Auth::user();
        $project->load(['members', 'tasks.user']);
        $tasks = $project->tasks;

        if ($user->role === 'lead') {
            return view('projects.show-lead', compact('project', 'tasks'));
        } else {
            return view('projects.show-developer', compact('project', 'tasks'));
        }
    }

    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project)
    {
        $project->delete(); // Soft delete (archive)
        return redirect()->route('dashboard');
    }

    public function archives()
    {
        $archivedProjects = Project::onlyTrashed()->get()->map(function ($project) {
            $project->archived_at = $project->deleted_at;
            return $project;
        });
        
        return view('projects.archives', compact('archivedProjects'));
    }

    public function restore($projectId)
    {
        $project = Project::withTrashed()->findOrFail($projectId);
        $project->restore();
        return redirect()->route('dashboard');
    }

    public function forceDelete($projectId)
    {
        $project = Project::withTrashed()->findOrFail($projectId);
        $project->forceDelete();
        return redirect()->route('projects.archives');
    }

    public function invite(Request $request, Project $project)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if (!$project->members()->where('user_id', $user->id)->exists()) {
            $project->members()->attach($user->id, ['role' => 'developer']);
        }

        return response()->json(['success' => true]);
    }

    public function removeMember(Project $project, \App\Models\User $user)
    {
        $project->members()->detach($user->id);
        return response()->json(['success' => true]);
    }
}
