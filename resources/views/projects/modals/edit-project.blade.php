<!-- Edit Project Modal -->
<div id="edit-project-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
    <div class="surface-card border rounded-xl w-full max-w-[520px] p-[32px] flex flex-col gap-6 shadow-2xl">
        <div class="flex justify-between items-center">
            <h2 class="font-h3 text-h3 text-on-surface">Edit Project</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors" onclick="document.getElementById('edit-project-modal').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <form method="POST" action="{{ route('projects.update', $project) }}" class="flex flex-col gap-5">
            @csrf
            @method('PUT')

            <!-- Title -->
            <div class="flex flex-col gap-2">
                <label class="font-label-mono text-label-mono text-on-surface-variant uppercase" for="titre">Title</label>
                <input id="titre" class="bg-surface-container border border-outline-variant rounded-md px-3 py-2 text-body font-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-full placeholder-on-surface-variant font-label-mono"
                       type="text" name="titre" value="{{ old('titre', $project->titre) }}" required>
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-2">
                <label class="font-label-mono text-label-mono text-on-surface-variant uppercase" for="description">Description</label>
                <textarea id="description" class="bg-surface-container border border-outline-variant rounded-md px-3 py-2 text-body font-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-full placeholder-on-surface-variant font-label-mono resize-none"
                          rows="3" name="description">{{ old('description', $project->description) }}</textarea>
            </div>

            <!-- Deadline -->
            <div class="flex flex-col gap-2">
                <label class="font-label-mono text-label-mono text-on-surface-variant uppercase" for="deadline">Deadline</label>
                <div class="relative">
                    <input id="deadline" class="bg-surface-container border border-outline-variant rounded-md pl-10 pr-3 py-2 text-body font-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-full font-label-mono"
                           type="date" name="deadline" value="{{ old('deadline', $project->deadline?->format('Y-m-d')) }}">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">calendar_today</span>
                </div>
            </div>

            <hr class="border-outline-variant w-full"/>

            <!-- Members Section -->
            <div class="flex flex-col gap-4">
                <h3 class="font-body-strong text-body-strong text-on-surface">Current Members</h3>
                <div class="flex flex-col gap-3">
                    @foreach($project->members as $member)
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-surface-container-high border border-outline-variant flex items-center justify-center font-label-mono text-label-mono text-on-surface">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $member->name)[1] ?? '', 0, 1)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-body-strong text-body-strong text-on-surface">{{ $member->name }}</span>
                                    <span class="font-caption text-caption text-on-surface-variant">{{ $member->email }}</span>
                                </div>
                            </div>
                            @can('manageMembers', $project)
                                <button type="button" class="font-body text-body text-error hover:text-error/80 transition-colors opacity-0 group-hover:opacity-100 cursor-pointer"
                                        onclick="removeMember({{ $member->id }})">
                                    Remove
                                </button>
                            @endcan
                        </div>
                    @endforeach
                </div>

                <!-- Add Member -->
                <div class="flex gap-2 mt-2">
                    <input class="bg-surface-container border border-outline-variant rounded-md px-3 py-2 text-body font-body text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary flex-1 placeholder-on-surface-variant font-label-mono"
                           placeholder="Add member by email..." type="email" id="new-member-email">
                    <button type="button" class="bg-primary-container text-on-primary-container hover:bg-primary-container/90 transition-colors font-body-strong text-body-strong px-4 py-2 rounded-md cursor-pointer"
                            onclick="inviteMember()">
                        Invite
                    </button>
                </div>
            </div>

            <hr class="border-outline-variant w-full"/>

            <!-- Footer Actions -->
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <button type="button" class="border border-outline-variant text-on-surface hover:bg-surface-variant transition-colors font-body-strong text-body-strong px-4 py-2 rounded-md cursor-pointer"
                            onclick="document.getElementById('edit-project-modal').classList.add('hidden')">
                        Cancel
                    </button>
                    <button type="submit" class="bg-inverse-primary text-white hover:bg-inverse-primary/90 transition-colors font-body-strong text-body-strong px-4 py-2 rounded-md cursor-pointer">
                        Save Changes
                    </button>
                </div>
                <div class="flex justify-center mt-2">
                    <button type="button" class="flex items-center gap-2 text-error hover:text-error/80 transition-colors font-caption text-caption cursor-pointer"
                            onclick="if(confirm('Archive this project?')) { document.getElementById('archive-form').submit(); }">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                        Archive this project
                    </button>
                </div>
            </div>
        </form>

        <!-- Hidden archive form -->
        <form id="archive-form" method="POST" action="{{ route('projects.destroy', $project) }}">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>

<script>
    function inviteMember() {
        const email = document.getElementById('new-member-email').value;
        if (!email) return;
        fetch(`/projects/{{ $project->id }}/invite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ email: email })
        }).then(() => window.location.reload());
    }

    function removeMember(userId) {
        if (confirm('Remove this member from the project?')) {
            fetch(`/projects/{{ $project->id }}/members/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            }).then(() => window.location.reload());
        }
    }
</script>
