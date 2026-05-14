<div class="bg-surface border border-outline-variant rounded-lg p-3 shadow-sm hover:border-primary/40 transition-all cursor-pointer group">
    <div class="flex flex-col gap-2">
        <div class="flex items-center justify-between">
            <span class="text-[10px] font-mono text-on-surface-variant uppercase">{{ $task->project->titre ?? 'General' }}</span>
            <div class="flex items-center gap-1">
                @php
                    $priorityColors = [
                        'low' => 'text-on-surface-variant',
                        'medium' => 'text-primary',
                        'high' => 'text-error',
                    ];
                @endphp
                <span class="material-symbols-outlined text-[14px] {{ $priorityColors[$task->priority] ?? '' }}">priority_high</span>
            </div>
        </div>
        
        <h4 class="text-sm font-body-strong text-on-surface group-hover:text-primary transition-colors line-clamp-2">{{ $task->title }}</h4>
        
        <div class="flex items-center justify-between mt-2">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 rounded-full bg-primary/20 flex items-center justify-center text-[8px] text-primary font-bold uppercase">
                    {{ substr($task->user->name ?? '?', 0, 2) }}
                </div>
                <span class="text-[10px] text-on-surface-variant">{{ $task->deadline ? $task->deadline->format('M d') : '' }}</span>
            </div>
            
            <div class="flex items-center gap-1">
                 <form action="{{ route('tasks.update-status', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-on-surface-variant hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
