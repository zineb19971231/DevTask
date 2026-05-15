@extends('layouts.devtrack')

@section('title', 'DevTrack - Dashboard')

@section('header')
    <h1 class="font-h3 text-h3 font-bold text-on-surface">
        {{ auth()->user()->role === 'lead' ? 'My Projects' : 'My Tasks' }}
    </h1>
@endsection

@section('content')
    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        @if(auth()->user()->role === 'lead')
            <div class="bg-surface border border-outline-variant rounded-lg p-[20px]">
                <p class="font-label-mono text-label-mono text-on-surface-variant mb-2">Total Projects</p>
                <p class="font-h2 text-h2 text-on-surface">{{ $stats['total_projects'] ?? 0 }}</p>
            </div>
            <div class="bg-surface border border-outline-variant rounded-lg p-[20px]">
                <p class="font-label-mono text-label-mono text-on-surface-variant mb-2">Active Tasks</p>
                <p class="font-h2 text-h2 text-primary">{{ $stats['active_tasks'] ?? 0 }}</p>
            </div>
            <div class="bg-surface border border-outline-variant rounded-lg p-[20px]">
                <p class="font-label-mono text-label-mono text-on-surface-variant mb-2">Completed</p>
                <p class="font-h2 text-h2 text-[#22C55E]">{{ $stats['completed'] ?? 0 }}</p>
            </div>
            <div class="bg-surface border border-outline-variant rounded-lg p-[20px]">
                <p class="font-label-mono text-label-mono text-on-surface-variant mb-2">Team Members</p>
                <p class="font-h2 text-h2 text-tertiary-container">{{ $stats['team_members'] ?? 0 }}</p>
            </div>
        @else
            <div class="surface-card border rounded-lg p-4 flex flex-col justify-between h-24 relative overflow-hidden">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">My Projects</p>
                <p class="font-h1 text-h1 text-on-surface">{{ $stats['total_projects'] ?? 0 }}</p>
                <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-primary/5 rounded-full blur-xl"></div>
            </div>
            <div class="surface-card border rounded-lg p-4 flex flex-col justify-between h-24 relative overflow-hidden">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">My Open Tasks</p>
                <p class="font-h1 text-h1 text-on-surface">{{ $stats['active_tasks'] ?? 0 }}</p>
                <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-error/5 rounded-full blur-xl"></div>
            </div>
            <div class="surface-card border rounded-lg p-4 flex flex-col justify-between h-24 relative overflow-hidden">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">Completed This Week</p>
                <p class="font-h1 text-h1 text-on-surface">{{ $stats['completed_week'] ?? 0 }}</p>
                <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-primary/10 rounded-full blur-xl"></div>
            </div>
        @endif
    </div>

    @if(auth()->user()->role === 'lead')
        <!-- Projects Grid (Team Lead) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($projects as $project)
                <div class="bg-surface border border-outline-variant rounded-lg p-[24px] hover:border-primary transition-colors group cursor-pointer">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-body-strong text-body-strong text-on-surface text-[16px]">{{ $project->title }}</h3>
                        <button class="text-on-surface-variant group-hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined">more_horiz</span>
                        </button>
                    </div>
                    <p class="font-body text-body text-on-surface-variant mb-6 truncate">{{ $project->description }}</p>
                    <div class="mb-6">
                        <div class="w-full h-[6px] bg-outline-variant rounded-full overflow-hidden mb-2">
                            <div class="h-full bg-primary w-[{{ $project->progress ?? 0 }}%]"></div>
                        </div>
                        <p class="font-label-mono text-label-mono text-on-surface-variant">{{ $project->completed_tasks ?? 0 }} / {{ $project->total_tasks ?? 0 }} tasks</p>
                    </div>
                    <div class="flex justify-between items-center mt-auto">
                        <div class="flex items-center gap-2 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                            <span class="font-caption text-caption">{{ $project->deadline?->format('M d, Y') ?? 'No deadline' }}</span>
                        </div>
                        <div class="flex -space-x-2">
                            @foreach($project->members->take(3) as $member)
                                <div class="w-6 h-6 rounded-full bg-surface-container-highest border border-surface flex items-center justify-center font-caption text-[8px] text-on-surface">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="font-body text-body text-on-surface-variant">No projects yet. Create your first project!</p>
                </div>
            @endforelse
        </div>

        <!-- Add Project Button -->
        <div class="fixed bottom-8 right-8">
            <a href="{{ route('projects.create') }}" class="bg-primary text-on-primary font-body-strong text-body-strong px-4 py-3 rounded-full flex items-center gap-2 hover:bg-primary-fixed transition-colors shadow-lg">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Add Project
            </a>
        </div>
    @else
        <!-- Developer Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Projects I'm On -->
            <div class="lg:col-span-1 space-y-4">
                <h3 class="font-h3 text-h3 text-on-surface font-bold">Projects I'm On</h3>
                <div class="surface-card border rounded-lg overflow-hidden">
                    @forelse($projects as $project)
                        <div class="p-4 border-b border-outline-variant hover:bg-surface-variant transition-colors">
                            <p class="font-body-strong text-body-strong text-on-surface mb-2">{{ $project->title }}</p>
                            <div class="w-full bg-surface-container-highest rounded-full h-1.5 mb-2">
                                <div class="bg-primary h-1.5 rounded-full" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                            <p class="font-caption text-caption text-on-surface-variant">{{ $project->tasks_assigned_to_me ?? 0 }} tasks assigned to me</p>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            <p class="font-body text-body text-on-surface-variant">No projects assigned</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- My Tasks — Due Soon -->
            <div class="lg:col-span-2 space-y-4">
                <h3 class="font-h3 text-h3 text-on-surface font-bold">My Tasks — Due Soon</h3>
                <div class="surface-card border rounded-lg flex flex-col">
                    @forelse($myTasks as $task)
                        <div class="group flex items-center justify-between p-4 border-b border-outline-variant {{ $task->is_urgent ? 'bg-error/5 border-l-[3px] border-l-error' : 'border-l-[3px] border-l-transparent' }} hover:bg-surface-variant transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="bg-{{ $task->status === 'done' ? 'surface-container-highest' : ($task->status === 'in_progress' ? 'primary/20 text-primary' : 'surface-container-highest text-on-surface-variant') }} font-label-mono text-label-mono px-2 py-1 rounded-full whitespace-nowrap w-24 text-center">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </span>
                                <div>
                                    <p class="font-body-strong text-body-strong text-on-surface">{{ $task->title }}</p>
                                    <p class="font-caption text-caption text-on-surface-variant">{{ $task->project->title ?? '' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-right">
                                <span class="font-label-mono text-label-mono {{ $task->is_urgent ? 'text-error' : 'text-on-surface-variant' }}">
                                    {{ $task->deadline ? $task->deadline->diffForHumans() : 'No deadline' }}
                                </span>
                                <span class="bg-{{ $task->priority === 'high' ? 'error text-on-error' : ($task->priority === 'medium' ? 'tertiary-container text-on-tertiary-container' : 'inverse-primary text-on-primary') }} font-label-mono text-label-mono px-2 py-1 rounded w-16 text-center">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center">
                            <p class="font-body text-body text-on-surface-variant">No tasks assigned to you</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
@endsection
