<div class="surface-card rounded-lg p-4 group hover:border-outline transition-colors cursor-grab active:cursor-grabbing relative {{ $task->status === 'done' ? 'opacity-70 grayscale-[50%]' : '' }} {{ $task->is_urgent ? 'border-t-2 border-t-amber-500 shadow-[0_4px_24px_-8px_rgba(245,158,11,0.1)]' : '' }}">
    <!-- Action buttons (visible on hover) -->
    <div class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity flex gap-1 z-10">
        @can('update', $task)
            <button class="text-on-surface-variant hover:text-on-surface p-1 rounded hover:bg-surface-variant" onclick="openEditTaskModal({{ $task->id }})">
                <span class="material-symbols-outlined text-[16px]">edit</span>
            </button>
        @endcan
        @can('delete', $task)
            <button class="text-on-surface-variant hover:text-error p-1 rounded hover:bg-error-container/20" onclick="confirmDeleteTask({{ $task->id }})">
                <span class="material-symbols-outlined text-[16px]">delete</span>
            </button>
        @endcan
    </div>

    <!-- Drag indicator -->
    <div class="absolute -left-3 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity text-outline-variant cursor-grab">
        <span class="material-symbols-outlined text-[20px]">drag_indicator</span>
    </div>

    <!-- Task header -->
    <div class="flex justify-between items-start mb-1">
        <div class="font-label-mono text-[10px] text-on-surface-variant uppercase tracking-wider">{{ $project->code ?? 'PRJ' }}-{{ $task->id }}</div>
        @if($task->is_urgent)
            <div class="flex items-center gap-1 text-amber-500 font-label-mono text-[10px] bg-amber-500/10 px-1.5 py-0.5 rounded">
                <span class="material-symbols-outlined text-[12px]">warning</span> Urgent
            </div>
        @endif
    </div>

    <!-- Task title & description -->
    <h4 class="font-body-strong text-body-strong text-on-surface mb-1 pr-6 leading-snug">{{ $task->title }}</h4>
    <p class="font-caption text-caption text-on-surface-variant mb-4 line-clamp-1">{{ $task->description }}</p>

    <!-- Task footer -->
    <div class="flex items-center justify-between border-t border-outline-variant/50 pt-3">
        <div class="flex items-center gap-2">
            @if($task->assignedUser)
                <div class="w-5 h-5 rounded-full {{ $task->assignedUser->role === 'lead' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-primary-container text-on-primary-container' }} flex items-center justify-center font-caption text-[8px] font-bold">
                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $task->assignedUser->name)[1] ?? '', 0, 1)) }}
                </div>
                <span class="font-caption text-caption text-on-surface-variant">{{ explode(' ', $task->assignedUser->name)[0] }}</span>
            @endif
        </div>
        <div class="bg-{{ $task->priority === 'high' ? 'red-500/10 text-red-400 border border-red-500/20' : ($task->priority === 'medium' ? 'amber-500/10 text-amber-400 border border-amber-500/20' : 'blue-500/10 text-blue-400 border border-blue-500/20') }} font-label-mono text-[10px] px-2 py-0.5 rounded">
            {{ ucfirst($task->priority) }}
        </div>
    </div>

    <!-- Developer-only status update controls (visible only if task is assigned to current user) -->
    @if(auth()->user()->id === $task->assigned_user_id && $task->status !== 'done')
        <div class="hidden group-hover:flex items-center gap-2 mt-2">
            @if($task->status !== 'todo')
                <button class="px-2 py-1 hover:bg-surface-bright text-on-surface-variant hover:text-on-surface transition-colors flex items-center border border-outline-variant rounded" onclick="updateTaskStatus({{ $task->id }}, 'todo')">
                    <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                </button>
            @endif
            @if($task->status !== 'in_progress')
                <button class="px-2 py-1 hover:bg-surface-bright text-on-surface-variant hover:text-on-surface transition-colors flex items-center border border-outline-variant rounded" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')">
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </button>
            @endif
            @if($task->status !== 'done')
                <button class="bg-primary-container text-on-primary-container hover:bg-primary hover:text-on-primary px-3 py-1 rounded-full font-label-mono text-[10px] transition-colors" onclick="updateTaskStatus({{ $task->id }}, 'done')">
                    Complete
                </button>
            @endif
        </div>
    @endif
</div>
