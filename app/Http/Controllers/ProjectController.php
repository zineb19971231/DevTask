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
        $projects = Auth::user()->projects()->with('members')->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        // Will implement later
        return redirect()->route('dashboard');
    }

    public function show(Project $project)
    {
        $user = Auth::user();
        
        // Dummy tasks data
        $tasks = collect([
            (object)[
                'id' => 1,
                'title' => 'Design DB Schema for V2',
                'description' => 'Map out the relational changes needed for the new rate-limiting tables.',
                'status' => 'todo',
                'priority' => 'low',
                'is_urgent' => false,
                'assignedUser' => (object)['name' => 'Alex Mercer', 'role' => 'lead'],
                'project' => $project,
            ],
            (object)[
                'id' => 2,
                'title' => 'Set up staging CI/CD pipeline',
                'description' => 'Ensure automated tests run on PR to staging branch.',
                'status' => 'todo',
                'priority' => 'medium',
                'is_urgent' => false,
                'assignedUser' => (object)['name' => 'Sam K.', 'role' => 'developer'],
                'project' => $project,
            ],
            (object)[
                'id' => 3,
                'title' => 'Implement JWT Auth Middleware',
                'description' => 'Replace deprecated token validation logic before rollout.',
                'status' => 'in_progress',
                'priority' => 'high',
                'is_urgent' => true,
                'assignedUser' => (object)['name' => 'Jamie D.', 'role' => 'developer'],
                'project' => $project,
            ],
            (object)[
                'id' => 4,
                'title' => 'Refactor User Endpoints',
                'description' => 'Consolidate GET/POST/PUT handlers into generic controllers.',
                'status' => 'in_progress',
                'priority' => 'medium',
                'is_urgent' => false,
                'assignedUser' => (object)['name' => 'Alex Mercer', 'role' => 'lead'],
                'project' => $project,
            ],
            (object)[
                'id' => 5,
                'title' => 'Audit existing logging framework',
                'description' => 'Review CloudWatch retention policies.',
                'status' => 'done',
                'priority' => 'low',
                'is_urgent' => false,
                'assignedUser' => (object)['name' => 'Sam K.', 'role' => 'developer'],
                'project' => $project,
            ],
        ]);

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
        // Will implement later
        return redirect()->route('projects.show', $project);
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
}
