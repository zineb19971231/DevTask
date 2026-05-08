@extends('layouts.devtrack')

@section('title', 'DevTrack - ' . $project->title)

@section('header')
    <div class="flex flex-col gap-4">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 font-label-mono text-label-mono text-on-surface-variant">
            <span class="hover:text-primary cursor-pointer transition-colors" onclick="window.location='{{ route('dashboard') }}'">Dashboard</span>
            <span class="material-symbols-outlined text-[14px]">chevron_right</span>
            <span class="text-on-surface">{{ $project->title }}</span>
        </div>
        <!-- Title Row -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="font-h1 text-h1 font-bold text-on-surface tracking-tight">{{ $project->title }}</h1>
                <p class="font-body text-body text-on-surface-variant mt-1 max-w-2xl">{{ $project->description }}</p>
            </div>
            <!-- Metadata Row -->
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2 text-tertiary-container bg-tertiary-container/10 px-3 py-1.5 rounded-full">
                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                    <span class="font-body-strong text-caption">Due {{ $project->deadline?->format('M d') ?? 'No deadline' }}</span>
                </div>
                <div class="flex items-center">
                    <div class="flex -space-x-2">
                        @foreach($project->members->take(4) as $index => $member)
                            <div class="w-8 h-8 rounded-full {{ $member->role === 'lead' ? 'bg-surface-bright border-2 border-surface' : 'bg-secondary-container border-2 border-surface' }} flex items-center justify-center font-label-mono text-[10px] {{ $member->role === 'lead' ? 'text-on-surface' : 'text-secondary' }} z-{{ 30 - $index }}">
                                {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                            </div>
                        @endforeach
                        @if($project->members->count() > 4)
                            <div class="w-8 h-8 rounded-full bg-surface-variant border-2 border-surface flex items-center justify-center font-label-mono text-[10px] text-on-surface-variant z-0">+{{ $project->members->count() - 4 }}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <!-- Kanban Board Area -->
    <div class="flex-1 p-container_padding flex gap-6 overflow-x-auto items-start h-full pb-8">
        <!-- TO DO Column -->
        <div class="flex flex-col gap-3 min-w-[320px] w-[320px] shrink-0 h-full">
            <div class="flex items-center justify-between pb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-on-surface-variant"></span>
                    <h3 class="font-body-strong text-body-strong text-on-surface">To Do</h3>
                    <span class="px-2 py-0.5 rounded-full bg-surface-variant text-on-surface-variant font-label-mono text-[10px]">{{ $tasks->where('status', 'todo')->count() }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-3 overflow-y-auto pr-1 pb-4">
                @foreach($tasks->where('status', 'todo') as $task)
                    @include('tasks.partials.card-developer', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>

        <!-- IN PROGRESS Column -->
        <div class="flex flex-col gap-3 min-w-[320px] w-[320px] shrink-0 h-full">
            <div class="flex items-center justify-between pb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary"></span>
                    <h3 class="font-body-strong text-body-strong text-on-surface">In Progress</h3>
                    <span class="px-2 py-0.5 rounded-full bg-primary/20 text-primary font-label-mono text-[10px]">{{ $tasks->where('status', 'in_progress')->count() }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-3 overflow-y-auto pr-1 pb-4">
                @foreach($tasks->where('status', 'in_progress') as $task)
                    @include('tasks.partials.card-developer', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>

        <!-- DONE Column -->
        <div class="flex flex-col gap-3 min-w-[320px] w-[320px] shrink-0 h-full">
            <div class="flex items-center justify-between pb-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-tertiary"></span>
                    <h3 class="font-body-strong text-body-strong text-on-surface text-on-surface-variant">Done</h3>
                    <span class="px-2 py-0.5 rounded-full bg-surface-variant text-on-surface-variant font-label-mono text-[10px]">{{ $tasks->where('status', 'done')->count() }}</span>
                </div>
            </div>
            <div class="flex flex-col gap-3 overflow-y-auto pr-1 pb-4">
                @foreach($tasks->where('status', 'done') as $task)
                    @include('tasks.partials.card-developer', ['task' => $task, 'project' => $project])
                @endforeach
            </div>
        </div>
    </div>
@endsection
