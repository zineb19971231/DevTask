@extends('layouts.devtrack')

@section('title', $project->titre . ' Tasks - DevTrack')

@section('header')
    <div class="flex items-center gap-2">
        <a href="{{ route('projects.show', $project) }}" class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-h2 text-h2 font-bold text-on-surface">{{ $project->titre }} - Tasks</h2>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-on-surface-variant">Detailed task list for this project.</p>
        </div>
        <button class="bg-primary text-on-primary font-body-strong px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            New Task
        </button>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-variant/30 border-b border-outline-variant">
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Task</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Assignee</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Priority</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($tasks as $task)
                <tr class="hover:bg-surface-variant/10 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-on-surface font-body-strong">{{ $task->title }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-primary/20 flex items-center justify-center text-[10px] text-primary font-bold uppercase">
                                {{ substr($task->user->name ?? '?', 0, 2) }}
                            </div>
                            <span class="text-on-surface-variant text-sm">{{ $task->user->name ?? 'Unassigned' }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                         @php
                            $statusColors = [
                                'todo' => 'bg-outline/20 text-outline',
                                'in_progress' => 'bg-primary/20 text-primary',
                                'done' => 'bg-success/20 text-success',
                            ];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$task->status] ?? 'bg-surface-variant text-on-surface-variant' }}">
                            {{ ucwords(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $priorityColors = [
                                'low' => 'text-on-surface-variant',
                                'medium' => 'text-primary',
                                'high' => 'text-error',
                            ];
                        @endphp
                        <span class="flex items-center gap-1 text-sm {{ $priorityColors[$task->priority] ?? '' }}">
                            <span class="material-symbols-outlined text-[16px]">priority_high</span>
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                             <button class="text-on-surface-variant hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </button>
                            <form action="{{ route('tasks.destroy', [$project, $task]) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-on-surface-variant hover:text-error transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-on-surface-variant">
                        No tasks in this project.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
