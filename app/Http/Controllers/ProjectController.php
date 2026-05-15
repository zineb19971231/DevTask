<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

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
        $this->authorize('create', Project::class);
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());
        
        // Assign current user as lead
        $project->members()->attach(Auth::id(), ['role' => 'lead']);

        return redirect()->route('dashboard')->with('success', 'Project created.');
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
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
        $this->authorize('update', $project);
        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $project->update($request->validated());

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
        $this->authorize('restore', $project);
        $project->restore();
        return redirect()->route('dashboard')->with('success', 'Project restored.');
    }

    public function forceDelete($projectId)
    {
        $project = Project::withTrashed()->findOrFail($projectId);
        $this->authorize('forceDelete', $project);
        $project->forceDelete();
        return redirect()->route('projects.archives')->with('success', 'Project permanently deleted.');
    }

    public function invite(Request $request, Project $project)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        
        $user = \App\Models\User::where('email', $request->email)->first();
        
        if ($project->members()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'This user is already a member of this project.');
        }

        $project->members()->attach($user->id, ['role' => 'developer']);

        return back()->with('success', $user->name . ' has been added to the project.');
    }

    public function removeMember(Project $project, \App\Models\User $user)
    {
        // Don't allow removing the lead/self
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot remove yourself from the project.');
        }

        $project->members()->detach($user->id);
        return back()->with('success', 'Member removed successfully.');
    }
}
