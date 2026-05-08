@extends('layouts.devtrack')

@section('title', 'DevTrack - ' . $project->title)

@section('header')
    <div class="flex flex-col justify-center">
        <div class="flex items-center gap-1 font-caption text-caption text-on-surface-variant mb-1">
            <a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Dashboard</a>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-on-surface">{{ $project->title }}</span>
        </div>
        <div class="flex items-end gap-3">
            <h2 class="font-h2 text-h2 font-bold text-on-surface leading-none">{{ $project->title }}</h2>
            <div class="flex items-center gap-1 font-caption text-caption text-on-surface-variant bg-surface-container-low px-2 py-0.5 rounded border border-outline-variant">
                <span class="material-symbols-outlined text-[14px]">event</span>
                Due {{ $project->deadline?->format('M d') ?? 'No deadline' }}
            </div>
        </div>
        <p class="font-caption text-caption text-on-surface-variant mt-1 hidden lg:block">{{ $project->description }}</p>
    </div>
@endsection

@section('content')
    <!-- Action Buttons -->
    <div class="flex items-center gap-3 mb-6">
        @can('update', $project)
            <button class="h-8 px-3 rounded surface-card font-body-strong text-body-strong text-on-surface hover:bg-surface-variant hover:-translate-y-px transition-all flex items-center gap-2" onclick="document.getElementById('edit-project-modal').classList.remove('hidden')">
                <span class="material-symbols-outlined text-[18px]">edit</span>
                Edit Project
            </button>
        @endcan
        @can('manageMembers', $project)
            <button class="h-8 px-3 rounded surface-card font-body-strong text-body-strong text-on-surface hover:bg-surface-variant hover:-translate-y-px transition-all flex items-center gap-2" onclick="document.getElementById('manage-members-modal').classList.remove('hidden')">
                <span class="material-symbols-outlined text-[18px]">group</span>
                Manage Members
            </button>
        @endcan
        <button class="h-8 px-4 rounded bg-primary-container text-on-primary-container font-body-strong text-body-strong hover:bg-primary-fixed transition-all active:scale-95 flex items-center gap-2" onclick="document.getElementById('create-task-modal').classList.remove('hidden')">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Add Task
        </button>
    </div>

    <!-- Members Row -->
    <section class="px-container_padding py-4 border-b border-outline-variant flex items-center gap-6 shrink-0 bg-background/50 backdrop-blur-sm mb-6">
        <div class="flex items-center gap-1 font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">
            <span class="material-symbols-outlined text-[16px]">group</span> Team
        </div>
        <div class="flex items-center gap-3">
            @foreach($project->members as $member)
                <div class="flex flex-col items-center gap-1 group cursor-pointer">
                    <div class="w-9 h-9 rounded-full {{ $member->role === 'lead' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-secondary-container text-on-secondary-container' }} flex items-center justify-center font-body-strong text-body-strong ring-2 ring-background group-hover:ring-{{ $member->role === 'lead' ? 'tertiary-container' : 'secondary-container' }} transition-all">
                        {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <span class="font-caption text-[10px] text-on-surface-variant group-hover:text-on-surface transition-colors">{{ explode(' ', $member->name)[0] }}</span>
                </div>
            @endforeach
            @can('manageMembers', $project)
                <div class="flex flex-col items-center gap-1 group cursor-pointer">
                    <div class="w-9 h-9 rounded-full border border-dashed border-outline-variant bg-transparent flex items-center justify-center text-on-surface-variant group-hover:border-primary group-hover:text-primary group-hover:bg-primary-container/10 transition-all">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </div>
                    <span class="font-caption text-[10px] text-on-surface-variant group-hover:text-primary transition-colors">Add</span>
                </div>
            @endcan
        </div>
    </section>

    <!-- Kanban Board -->
    <section class="flex-1 p-container_padding flex gap-gutter overflow-x-auto">
        <!-- To Do Column -->
        <div class="w-[320px] shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-outline-variant"></div>
                    <h3 class="font-body-strong text-body-strong text-on-surface">To Do</h3>
                    <span class="font-label-mono text-[10px] bg-surface-container-high px-1.5 py-0.5 rounded text-on-surface-variant">{{ $tasks->where('status', 'todo')->count() }}</span>
                </div>
                @can('create', App\Models\Task::class)
                    <button class="text-on-surface-variant hover:text-primary transition-colors" onclick="document.getElementById('create-task-modal').classList.remove('hidden')">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                @endcan
            </div>
            <div class="flex flex-col gap-3">
                @foreach($tasks->where('status', 'todo') as $task)
                    @include('tasks.partials.card', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="w-[320px] shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                    <h3 class="font-body-strong text-body-strong text-on-surface">In Progress</h3>
                    <span class="font-label-mono text-[10px] bg-primary-container/20 text-primary px-1.5 py-0.5 rounded">{{ $tasks->where('status', 'in_progress')->count() }}</span>
                </div>
                @can('create', App\Models\Task::class)
                    <button class="text-on-surface-variant hover:text-primary transition-colors" onclick="document.getElementById('create-task-modal').classList.remove('hidden')">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                @endcan
            </div>
            <div class="flex flex-col gap-3">
                @foreach($tasks->where('status', 'in_progress') as $task)
                    @include('tasks.partials.card', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>

        <!-- Done Column -->
        <div class="w-[320px] shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2 opacity-60">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <h3 class="font-body-strong text-body-strong text-on-surface">Done</h3>
                    <span class="font-label-mono text-[10px] bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded">{{ $tasks->where('status', 'done')->count() }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-3">
                @foreach($tasks->where('status', 'done') as $task)
                    @include('tasks.partials.card', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>

        <!-- Blocked Column (optional) -->
        <div class="w-[320px] shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2 opacity-50">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-error"></div>
                    <h3 class="font-body-strong text-body-strong text-on-surface">Blocked</h3>
                    <span class="font-label-mono text-[10px] bg-surface-container-high px-1.5 py-0.5 rounded text-on-surface-variant">0</span>
                </div>
            </div>
            <div class="h-[120px] rounded-lg border-2 border-dashed border-outline-variant/30 flex flex-col items-center justify-center text-on-surface-variant gap-2 bg-surface/30">
                <span class="material-symbols-outlined opacity-50">block</span>
                <span class="font-caption text-caption">No tasks here</span>
            </div>
        </div>
    </section>

    <!-- Modals -->
    @can('update', $project)
        @include('projects.modals.edit-project', ['project' => $project])
    @endcan
    @can('manageMembers', $project)
        @include('projects.modals.manage-members', ['project' => $project])
    @endcan
    @can('create', App\Models\Task::class)
        @include('tasks.modals.create-task', ['project' => $project])
    @endcan
    @can('update', App\Models\Task::class)
        @include('tasks.modals.edit-task', ['project' => $project])
    @endcan
@endsection
