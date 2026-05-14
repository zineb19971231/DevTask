@extends('layouts.devtrack')

@section('title', 'Projects - DevTrack')

@section('header')
    <h2 class="font-h2 text-h2 font-bold text-on-surface">All Projects</h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-on-surface-variant">View and manage all active projects.</p>
        </div>
        @if(auth()->user()->role === 'lead')
        <a href="{{ route('projects.create') }}" class="bg-primary text-on-primary font-body-strong px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-[18px]">add</span>
            New Project
        </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($projects as $project)
        <div class="bg-surface border border-outline-variant rounded-xl p-6 hover:border-primary/50 transition-all group flex flex-col h-full">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-h3 text-h3 font-bold text-on-surface group-hover:text-primary transition-colors">{{ $project->title }}</h3>
                <div class="flex items-center gap-1">
                    <a href="{{ route('projects.edit', $project) }}" class="text-on-surface-variant hover:text-primary transition-colors p-1">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </a>
                </div>
            </div>
            
            <p class="text-on-surface-variant text-sm line-clamp-3 mb-6 flex-1">{{ $project->description }}</p>
            
            <div class="mt-auto space-y-4">
                <div class="flex items-center justify-between text-xs font-mono text-on-surface-variant">
                    <span>Progress</span>
                    <span>{{ $project->progress ?? 0 }}%</span>
                </div>
                <div class="w-full h-1.5 bg-surface-variant rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width: {{ $project->progress ?? 0 }}%"></div>
                </div>
                
                <div class="flex justify-between items-center pt-2">
                    <div class="flex -space-x-2">
                        @foreach($project->members->take(3) as $member)
                        <div class="w-6 h-6 rounded-full bg-primary/20 border border-surface flex items-center justify-center text-[8px] font-bold text-primary" title="{{ $member->name }}">
                            {{ substr($member->name, 0, 1) }}
                        </div>
                        @endforeach
                    </div>
                    <a href="{{ route('projects.show', $project) }}" class="text-primary text-sm font-body-strong hover:underline flex items-center gap-1">
                        View Details
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-20 text-center bg-surface border border-dashed border-outline-variant rounded-xl">
             <span class="material-symbols-outlined text-4xl opacity-20 mb-2">folder_open</span>
             <p class="text-on-surface-variant">No active projects found.</p>
             @if(auth()->user()->role === 'lead')
             <a href="{{ route('projects.create') }}" class="text-primary hover:underline mt-2 inline-block">Create your first project</a>
             @endif
        </div>
        @endforelse
    </div>
</div>
@endsection
