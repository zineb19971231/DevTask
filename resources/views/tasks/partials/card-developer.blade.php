<div class="surface-card rounded-lg p-4 flex flex-col gap-3 {{ $task->status === 'done' ? 'opacity-60 grayscale-[50%]' : '' }} {{ $task->is_urgent ? 'border-l-[3px] border-l-amber-500' : 'border-l-[3px] border-l-transparent' }}">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div class="flex gap-2">
            @if($task->is_urgent)
                <span class="px-2 py-0.5 rounded flex items-center font-label-mono text-[10px] uppercase bg-tertiary-container/20 text-tertiary-container font-bold border border-tertiary-container/30">Urgent</span>
            @endif
            <span class="px-2 py-0.5 rounded flex items-center font-label-mono text-[10px] uppercase {{ $task->status === 'done' ? 'bg-surface-variant text-on-surface-variant' : ($task->status === 'in_progress' ? 'bg-primary-container text-on-primary-container' : 'bg-surface-variant text-on-surface-variant') }}">
                {{ ucfirst($task->status) }}
            </span>
        </div>
        @if(auth()->user()->id === $task->assigned_user_id)
            <span class="px-2 py-0.5 rounded flex items-center font-label-mono text-[10px] uppercase bg-primary-container text-on-primary-container">Mine</span>
        @endif
    </div>

    <!-- Title & Description -->
    <div>
        <h4 class="font-body-strong text-body-strong text-on-surface font-bold">{{ $task->title }}</h4>
        <p class="font-body text-body text-on-surface-variant line-clamp-2 mt-1 text-[13px] leading-relaxed">{{ $task->description }}</p>
    </div>

    <!-- Footer -->
    <div class="flex items-center justify-between mt-2 pt-3 border-t border-outline-variant/50">
        <div class="flex items-center gap-2">
            @if($task->assignedUser)
                <div class="w-6 h-6 rounded-full {{ $task->assignedUser->role === 'lead' ? 'bg-surface-bright' : 'bg-secondary-container' }} flex items-center justify-center font-label-mono text-[10px] {{ $task->assignedUser->role === 'lead' ? 'text-on-surface' : 'text-secondary' }}">
                    {{ strtoupper(substr($task->assignedUser->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $task->assignedUser->name)[1] ?? '', 0, 1)) }}
                </div>
                <span class="font-body text-[11px] text-on-surface-variant">{{ explode(' ', $task->assignedUser->name)[0] }}</span>
            @endif
        </div>

        <!-- Developer Interactive Controls (only if assigned to current user) -->
        @if(auth()->user()->id === $task->assigned_user_id && $task->status !== 'done')
            <div class="hidden group-hover:flex items-center gap-2">
                <div class="flex bg-surface-variant rounded-md overflow-hidden border border-outline-variant">
                    @if($task->status !== 'todo')
                        <button class="px-2 py-1 hover:bg-surface-bright text-on-surface-variant hover:text-on-surface transition-colors flex items-center border-r border-outline-variant" title="Move to To Do" onclick="updateTaskStatus({{ $task->id }}, 'todo')">
                            <span class="material-symbols-outlined text-[14px]">arrow_back</span>
                        </button>
                    @endif
                    @if($task->status !== 'in_progress')
                        <button class="px-2 py-1 hover:bg-surface-bright text-on-surface-variant hover:text-on-surface transition-colors flex items-center border-r border-outline-variant" title="Move to In Progress" onclick="updateTaskStatus({{ $task->id }}, 'in_progress')">
                            <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                        </button>
                    @endif
                    @if($task->status !== 'done')
                        <button class="px-2 py-1 hover:bg-surface-bright text-on-surface-variant hover:text-on-surface transition-colors flex items-center" title="Mark as Done" onclick="updateTaskStatus({{ $task->id }}, 'done')">
                            <span class="material-symbols-outlined text-[14px]">check</span>
                        </button>
                    @endif
                </div>
                <button class="bg-primary/10 text-primary hover:bg-primary hover:text-on-primary px-3 py-1 rounded-full font-label-mono text-[10px] transition-colors" onclick="updateTaskStatus({{ $task->id }}, 'done')">
                    Complete
                </button>
            </div>
        @endif
    </div>
</div>

<script>
    function updateTaskStatus(taskId, status) {
        fetch(`/projects/{{ $project->id }}/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status: status })
        }).then(() => window.location.reload());
    }
</script>
