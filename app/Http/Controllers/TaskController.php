<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

class TaskController extends Controller
{
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