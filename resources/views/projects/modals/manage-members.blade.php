<!-- Manage Members Modal -->
<div id="manage-members-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50"></div>
<div id="manage-members-modal-content" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="relative z-50 w-full max-w-[500px] bg-[#161B27] border border-[#1E2535] rounded-[12px] shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#1E2535]">
            <h2 class="font-h3 text-h3 text-on-surface m-0">Project Members</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-1 rounded-md hover:bg-surface-variant" onclick="document.getElementById('manage-members-modal').classList.add('hidden'); document.getElementById('manage-members-modal-content').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 flex flex-col gap-6 max-h-[60vh] overflow-y-auto">
            <!-- Current Members List -->
            <div class="flex flex-col gap-3">
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">Current Team</p>
                <div class="flex flex-col gap-2">
                    @foreach($project->members as $member)
                        <div class="flex items-center justify-between p-3 bg-surface rounded-lg border border-outline-variant group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full {{ $member->pivot->role === 'lead' ? 'bg-tertiary-container text-on-tertiary-container' : 'bg-primary-container text-on-primary-container' }} flex items-center justify-center font-bold text-xs uppercase">
                                    {{ substr($member->name, 0, 2) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-on-surface font-body-strong">{{ $member->name }}</span>
                                    <span class="text-[10px] text-on-surface-variant italic">{{ ucfirst($member->pivot->role) }}</span>
                                </div>
                            </div>
                            @if($member->id !== Auth::id())
                                <form action="{{ route('projects.remove-member', [$project, $member]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-1 rounded hover:bg-error/10" title="Remove Member">
                                        <span class="material-symbols-outlined text-[18px]">person_remove</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="border-[#1E2535]">

            <!-- Invite New Member Form -->
            <form action="{{ route('projects.invite', $project) }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <p class="font-label-mono text-label-mono text-on-surface-variant uppercase tracking-wider">Invite New Member</p>
                <div class="flex flex-col gap-2">
                    <label class="font-body-strong text-body-strong text-on-surface" for="invite-email">User Email</label>
                    <div class="flex gap-2">
                        <input id="invite-email" name="email" type="email" placeholder="colleague@example.com" required
                               class="flex-1 h-11 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary transition-colors">
                        <button type="submit" class="h-11 px-4 bg-primary text-on-primary font-body-strong rounded-lg hover:bg-primary-fixed transition-colors">
                            Invite
                        </button>
                    </div>
                    <p class="text-[10px] text-on-surface-variant">The user must already be registered in DevTrack.</p>
                </div>
            </form>
        </div>
    </div>
</div>
