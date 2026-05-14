@extends('layouts.devtrack')

@section('title', 'All Tasks - DevTrack')

@section('header')
    <h2 class="font-h2 text-h2 font-bold text-on-surface">Global Tasks</h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-on-surface-variant">Manage and track all your tasks across projects.</p>
        </div>
    </div>

    <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-variant/30 border-b border-outline-variant">
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Task</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Project</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Assignee</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Status</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant">Priority</th>
                    <th class="px-6 py-4 font-body-strong text-on-surface-variant text-right">Deadline</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($tasks as $task)
                <tr class="hover:bg-surface-variant/10 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-on-surface font-body-strong">{{ $task->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-on-surface-variant text-sm">{{ $task->project->titre ?? 'No Project' }}</span>
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
                        <span class="text-sm {{ $task->is_urgent ? 'text-error font-bold' : 'text-on-surface-variant' }}">
                            {{ $task->deadline ? $task->deadline->format('M d, Y') : 'No deadline' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                        <div class="flex flex-col items-center gap-2">
                            <span class="material-symbols-outlined text-4xl opacity-20">task</span>
                            <p>No tasks found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
