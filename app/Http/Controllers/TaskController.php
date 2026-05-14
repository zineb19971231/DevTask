<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()->with('user')->get();
        return view('tasks.index', compact('project', 'tasks'));
    }

    public function globalIndex()
    {
        $user = auth()->user();
        if ($user->role === 'lead') {
            $tasks = Task::with(['project', 'user'])->latest()->get();
        } else {
            $tasks = Task::where('user_id', $user->id)->with(['project', 'user'])->latest()->get();
        }
        return view('tasks.global', compact('tasks'));
    }

    public function backlog()
    {
        $user = auth()->user();
        $query = Task::whereIn('statut', ['to do', 'backlog'])->with(['project', 'user']);
        
        if ($user->role !== 'lead') {
            $query->where('user_id', $user->id);
        }

        $tasks = $query->latest()->get();
        return view('tasks.backlog', compact('tasks'));
    }

    public function board()
    {
        $user = auth()->user();
        $query = Task::with(['project', 'user']);

        if ($user->role !== 'lead') {
            $query->where('user_id', $user->id);
        }

        $tasks = $query->get();
        
        $board = [
            'todo'        => $tasks->filter(fn($t) => $t->status === 'todo'),
            'in_progress' => $tasks->filter(fn($t) => $t->status === 'in_progress'),
            'done'        => $tasks->filter(fn($t) => $t->status === 'done'),
        ];

        return view('tasks.board', compact('board'));
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $this->authorize('create', [Task::class, $project]);

        $project->tasks()->create($request->validated());

        return back()->with('success', 'Task created.');
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return back()->with('success', 'Task updated.');
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $this->authorize('updateStatus', $task);

        $transitions = [
            'todo'        => 'in_progress',
            'in_progress' => 'done',
        ];

        if (isset($transitions[$task->status])) {
            $task->status = $transitions[$task->status];
            $task->save();
        }

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return back()->with('success', 'Task deleted.');
    }
}