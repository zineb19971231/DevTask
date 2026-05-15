<!-- Create Task Modal -->
<div id="create-task-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50"></div>
<div id="create-task-modal-content" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="relative z-50 w-full max-w-[520px] bg-[#161B27] border border-[#1E2535] rounded-[12px] shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#1E2535]">
            <h2 class="font-h3 text-h3 text-on-surface m-0">Add Task to {{ $project->title }}</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-1 rounded-md hover:bg-surface-variant" onclick="document.getElementById('create-task-modal').classList.add('hidden'); document.getElementById('create-task-modal-content').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Body -->
        <form method="POST" action="{{ route('projects.tasks.store', $project) }}" class="p-6 flex flex-col gap-6">
            @csrf

            <!-- Task Title -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="task-titre">Task Title</label>
                <input id="task-titre" class="w-full h-12 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface placeholder:text-on-surface-variant placeholder:font-label-mono placeholder:text-label-mono focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       type="text" name="titre" placeholder="e.g. Build authentication endpoint" required>
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="task-desc">Description</label>
                <textarea id="task-desc" class="w-full bg-surface border border-outline-variant rounded-lg p-4 font-body text-body text-on-surface placeholder:text-on-surface-variant placeholder:font-label-mono placeholder:text-label-mono focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none"
                          rows="3" name="description" placeholder="Technical details, acceptance criteria..."></textarea>
            </div>

            <!-- Deadline & Priority Row -->
            <div class="flex flex-col sm:flex-row gap-6">
                <!-- Deadline -->
                <div class="flex flex-col gap-2 flex-1">
                    <label class="font-body-strong text-body-strong text-on-surface" for="task-deadline">Deadline</label>
                    <div class="relative">
                        <input id="task-deadline" class="w-full h-[40px] bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                               type="date" name="deadline" required>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant pointer-events-none">calendar_month</span>
                    </div>
                </div>

                <!-- Priority -->
                <div class="flex flex-col gap-2 flex-1">
                    <label class="font-body-strong text-body-strong text-on-surface">Priority</label>
                    <div class="flex items-center gap-2 h-[40px]">
                        <button type="button" class="priority-btn flex-1 h-full rounded-[4px] border border-outline-variant bg-surface text-on-surface font-body-strong text-body-strong hover:bg-surface-variant transition-colors" data-priority="low">Low</button>
                        <button type="button" class="priority-btn flex-1 h-full rounded-[4px] border-none bg-amber-500/20 text-amber-500 font-body-strong text-body-strong ring-1 ring-amber-500/50" data-priority="medium">Medium</button>
                        <button type="button" class="priority-btn flex-1 h-full rounded-[4px] border border-outline-variant bg-surface text-on-surface font-body-strong text-body-strong hover:bg-surface-variant transition-colors" data-priority="high">High</button>
                        <input type="hidden" name="priorite" id="priority-input" value="medium">
                    </div>
                </div>
            </div>

            <!-- Assign To -->
            <div class="flex flex-col gap-2 relative">
                <label class="font-body-strong text-body-strong text-on-surface">Assign To</label>
                <select name="user_id" class="w-full h-[48px] bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" required>
                    <option value="">Select developer...</option>
                    @foreach($project->members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->role }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Footer -->
            <div class="px-6 py-5 border-t border-[#1E2535] flex items-center justify-between bg-surface-container-lowest">
                <button type="button" class="px-4 py-2 rounded-[6px] font-body-strong text-body-strong text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-colors border border-transparent hover:border-outline-variant"
                        onclick="document.getElementById('create-task-modal').classList.add('hidden'); document.getElementById('create-task-modal-content').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 rounded-[6px] bg-primary text-on-primary font-h3 text-[14px] leading-[20px] hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-lg shadow-primary/10">
                    Create Task
                </button>
            </div>
        </form>

        <!-- Tip -->
        <div class="px-6 pb-5 pt-2 bg-surface-container-lowest text-center">
            <p class="font-caption text-caption text-on-surface-variant italic">Tip: Set a realistic deadline. Tasks due in &lt;48h will be flagged as urgent.</p>
        </div>
    </div>
</div>

<script>
    // Priority selector
    document.querySelectorAll('.priority-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.priority-btn').forEach(b => {
                b.classList.remove('bg-amber-500/20', 'text-amber-500', 'ring-1', 'ring-amber-500/50');
                b.classList.add('bg-surface', 'border', 'border-outline-variant', 'text-on-surface');
            });
            this.classList.remove('bg-surface', 'border', 'border-outline-variant', 'text-on-surface');
            this.classList.add('bg-amber-500/20', 'text-amber-500', 'ring-1', 'ring-amber-500/50');
            document.getElementById('priority-input').value = this.dataset.priority;
        });
    });
</script>
