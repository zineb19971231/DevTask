@extends('layouts.devtrack')

@section('title', 'Backlog - DevTrack')

@section('header')
    <h2 class="font-h2 text-h2 font-bold text-on-surface">Backlog</h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-on-surface-variant">Tasks waiting to be started or in the backlog.</p>
        </div>
        <button onclick="document.getElementById('global-create-task-modal').classList.remove('hidden'); document.getElementById('global-create-task-modal-content').classList.remove('hidden')" class="bg-primary text-on-primary font-body-strong px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Add Task
        </button>
    </div>

    <div class="grid gap-4">
        @forelse($tasks as $task)
        <div class="bg-surface border border-outline-variant rounded-xl p-4 hover:border-primary/50 transition-all group">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xs font-mono text-primary bg-primary/10 px-2 py-0.5 rounded">{{ $task->project->titre ?? 'General' }}</span>
                        <span class="text-xs text-on-surface-variant">{{ $task->created_at->diffForHumans() }}</span>
                    </div>
                    <h3 class="font-body-strong text-on-surface group-hover:text-primary transition-colors">{{ $task->title }}</h3>
                    <p class="text-sm text-on-surface-variant mt-1 line-clamp-2">{{ $task->description }}</p>
                </div>
                <div class="flex flex-col items-end gap-3 text-right">
                    <div class="flex items-center gap-2">
                         @php
                            $priorityColors = [
                                'low' => 'bg-outline/10 text-on-surface-variant',
                                'medium' => 'bg-primary/10 text-primary',
                                'high' => 'bg-error/10 text-error',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $priorityColors[$task->priority] ?? '' }}">
                            {{ $task->priority }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 rounded-full bg-surface-variant flex items-center justify-center text-[10px] font-bold">
                            {{ substr($task->user->name ?? '?', 0, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-surface border border-dashed border-outline-variant rounded-xl py-12 text-center text-on-surface-variant">
            <span class="material-symbols-outlined text-4xl opacity-20 mb-2">inventory_2</span>
            <p>Your backlog is empty.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
