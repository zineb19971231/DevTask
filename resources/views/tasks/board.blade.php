@extends('layouts.devtrack')

@section('title', 'Board - DevTrack')

@section('header')
    <h2 class="font-h2 text-h2 font-bold text-on-surface">Kanban Board</h2>
@endsection

@section('content')
<div class="h-full flex flex-col">
    <div class="mb-6">
        <p class="text-on-surface-variant">Visualize your workflow and progress.</p>
    </div>

    <div class="flex-1 flex gap-6 overflow-x-auto pb-4">
        <!-- Todo -->
        <div class="w-80 flex-shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-outline"></span>
                    <h3 class="font-body-strong text-on-surface">To Do</h3>
                    <span class="bg-surface-variant text-on-surface-variant text-xs px-2 py-0.5 rounded-full">{{ $board['todo']->count() }}</span>
                </div>
            </div>
            
            <div class="flex-1 flex flex-col gap-3 bg-surface/50 p-2 rounded-xl border border-outline-variant/50">
                @foreach($board['todo'] as $task)
                    @include('tasks.partials.board-card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- In Progress -->
        <div class="w-80 flex-shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                    <h3 class="font-body-strong text-on-surface">In Progress</h3>
                    <span class="bg-primary/10 text-primary text-xs px-2 py-0.5 rounded-full">{{ $board['in_progress']->count() }}</span>
                </div>
            </div>
            
            <div class="flex-1 flex flex-col gap-3 bg-surface/50 p-2 rounded-xl border border-outline-variant/50">
                @foreach($board['in_progress'] as $task)
                    @include('tasks.partials.board-card', ['task' => $task])
                @endforeach
            </div>
        </div>

        <!-- Done -->
        <div class="w-80 flex-shrink-0 flex flex-col gap-4">
            <div class="flex items-center justify-between px-2">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success"></span>
                    <h3 class="font-body-strong text-on-surface">Completed</h3>
                    <span class="bg-success/10 text-success text-xs px-2 py-0.5 rounded-full">{{ $board['done']->count() }}</span>
                </div>
            </div>
            
            <div class="flex-1 flex flex-col gap-3 bg-surface/50 p-2 rounded-xl border border-outline-variant/50">
                @foreach($board['done'] as $task)
                    @include('tasks.partials.board-card', ['task' => $task])
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
