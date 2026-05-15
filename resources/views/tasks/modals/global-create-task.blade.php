<!-- Global Create Task Modal -->
<div id="global-create-task-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50"></div>
<div id="global-create-task-modal-content" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="relative z-50 w-full max-w-[520px] bg-[#161B27] border border-[#1E2535] rounded-[12px] shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#1E2535]">
            <h2 class="font-h3 text-h3 text-on-surface m-0">Quick Add Task</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-1 rounded-md hover:bg-surface-variant" onclick="document.getElementById('global-create-task-modal').classList.add('hidden'); document.getElementById('global-create-task-modal-content').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Body -->
        <form id="global-task-form" method="POST" action="" class="p-6 flex flex-col gap-6">
            @csrf

            <!-- Project Selector -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="global-task-project">Project</label>
                <select id="global-task-project" name="projet_id" class="w-full h-12 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" required onchange="updateGlobalTaskAction(this.value)">
                    <option value="">Select a project...</option>
                    @foreach(Auth::user()->projects()->wherePivot('role', 'lead')->get() as $p)
                        <option value="{{ $p->id }}">{{ $p->title }}</option>
                    @endforeach
                </select>
                <p class="text-xs text-on-surface-variant italic">Note: You can only add tasks to projects you lead.</p>
            </div>

            <!-- Task Title -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="global-task-titre">Task Title</label>
                <input id="global-task-titre" class="w-full h-12 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface placeholder:text-on-surface-variant placeholder:font-label-mono placeholder:text-label-mono focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors"
                       type="text" name="titre" placeholder="e.g. Build authentication endpoint" required>
                <input type="hidden" name="priorite" value="medium">
            </div>

            <!-- Description (Optional) -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="global-task-desc">Description</label>
                <textarea id="global-task-desc" class="w-full bg-surface border border-outline-variant rounded-lg p-4 font-body text-body text-on-surface placeholder:text-on-surface-variant placeholder:font-label-mono placeholder:text-label-mono focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors resize-none"
                          rows="2" name="description" placeholder="Technical details..."></textarea>
            </div>

            <!-- Assign To -->
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="global-task-user">Assign To</label>
                <select id="global-task-user" name="user_id" class="w-full h-12 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-colors" required>
                    <option value="{{ Auth::id() }}">Assign to me</option>
                </select>
                <p class="text-[10px] text-on-surface-variant italic">For more assignees, add the task from the project page.</p>
            </div>

            <!-- Footer -->
            <div class="px-6 py-5 border-t border-[#1E2535] flex items-center justify-between bg-surface-container-lowest">
                <button type="button" class="px-4 py-2 rounded-[6px] font-body-strong text-body-strong text-on-surface-variant hover:text-on-surface hover:bg-surface-variant transition-colors"
                        onclick="document.getElementById('global-create-task-modal').classList.add('hidden'); document.getElementById('global-create-task-modal-content').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 rounded-[6px] bg-primary text-on-primary font-h3 text-[14px] leading-[20px] hover:bg-primary-container transition-colors shadow-lg">
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateGlobalTaskAction(projectId) {
        if (!projectId) return;
        const form = document.getElementById('global-task-form');
        form.action = `/projects/${projectId}/tasks`;
    }
</script>
