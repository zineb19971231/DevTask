<!-- Create Team Member Modal -->
<div id="create-user-modal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-50"></div>
<div id="create-user-modal-content" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="relative z-50 w-full max-w-[480px] bg-[#161B27] border border-[#1E2535] rounded-[12px] shadow-2xl flex flex-col overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-[#1E2535]">
            <h2 class="font-h3 text-h3 text-on-surface m-0">Add Team Member</h2>
            <button class="text-on-surface-variant hover:text-on-surface transition-colors p-1 rounded-md hover:bg-surface-variant" onclick="document.getElementById('create-user-modal').classList.add('hidden'); document.getElementById('create-user-modal-content').classList.add('hidden')">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <!-- Body -->
        <form action="{{ route('team.store') }}" method="POST" class="p-6 flex flex-col gap-5">
            @csrf
            
            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="user-name">Full Name</label>
                <input id="user-name" name="name" type="text" placeholder="John Doe" required
                       class="w-full h-11 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary transition-colors">
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="user-email">Email Address</label>
                <input id="user-email" name="email" type="email" placeholder="john@startup.com" required
                       class="w-full h-11 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary transition-colors">
            </div>

            <div class="flex flex-col gap-2">
                <label class="font-body-strong text-body-strong text-on-surface" for="user-password">Initial Password</label>
                <input id="user-password" name="password" type="text" value="{{ Str::random(10) }}" required
                       class="w-full h-11 bg-surface border border-outline-variant rounded-lg px-4 font-body text-body text-on-surface focus:outline-none focus:border-primary transition-colors">
                <p class="text-[10px] text-on-surface-variant italic">Share this password with the member so they can log in.</p>
            </div>

            <!-- Footer -->
            <div class="mt-4 pt-5 border-t border-[#1E2535] flex items-center justify-end gap-3">
                <button type="button" class="px-4 py-2 rounded-lg font-body-strong text-on-surface-variant hover:text-on-surface transition-colors"
                        onclick="document.getElementById('create-user-modal').classList.add('hidden'); document.getElementById('create-user-modal-content').classList.add('hidden')">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary text-on-primary font-body-strong rounded-lg hover:bg-primary-fixed transition-colors">
                    Add Member
                </button>
            </div>
        </form>
    </div>
</div>
