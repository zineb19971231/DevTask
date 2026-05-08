@extends('layouts.devtrack')

@section('title', 'DevTrack - Archived Projects')

@section('header')
    <h2 class="font-h3 text-h3 font-bold text-on-surface">Archived Projects</h2>
@endsection

@section('content')
    <!-- Amber Banner -->
    <div class="bg-tertiary-container/10 border border-tertiary-container/30 rounded-lg p-4 flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-tertiary" style="font-variation-settings: 'FILL' 1;">warning</span>
        <p class="font-body text-body text-tertiary">⚠️ Archived projects are hidden from your main dashboard.</p>
    </div>

    <!-- Archives List -->
    <div class="space-y-4">
        @forelse($archivedProjects as $project)
            <div class="bg-[#161B27] border border-[#1E2535] rounded-lg p-5 px-6 flex items-center justify-between group transition-colors duration-200 hover:border-outline-variant">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded bg-surface-variant flex items-center justify-center text-outline">
                        <span class="material-symbols-outlined">folder_off</span>
                    </div>
                    <div>
                        <h3 class="font-body-strong text-body-strong text-on-surface">{{ $project->title }}</h3>
                        <p class="font-caption text-caption text-on-surface-variant mt-1">Archived on {{ $project->archived_at?->format('M d, Y') ?? 'Unknown' }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    @can('restore', $project)
                        <button class="px-4 py-2 rounded-DEFAULT font-body-strong text-body-strong text-on-surface-variant border border-[#1E2535] hover:bg-[#1E2535] hover:text-[#4ade80] transition-colors duration-200" onclick="restoreProject({{ $project->id }})">
                            Restore
                        </button>
                    @endcan
                    @can('forceDelete', $project)
                        <button class="px-4 py-2 rounded-DEFAULT font-body-strong text-body-strong text-error border border-[#1E2535] hover:bg-error-container/20 transition-colors duration-200" onclick="confirmForceDelete({{ $project->id }})">
                            Delete Forever
                        </button>
                    @endcan
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <p class="font-body text-body text-on-surface-variant">No archived projects.</p>
            </div>
        @endforelse
    </div>

    <!-- Confirmation Modal for Force Delete -->
    <div id="force-delete-modal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-error-container/10 border border-error/30 rounded-lg p-5 px-6 w-full max-w-md">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-10 h-10 rounded bg-error-container/20 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <h3 class="font-body-strong text-body-strong text-on-surface" id="force-delete-project-name"></h3>
                    <p class="font-caption text-caption text-error mt-1">Are you sure? This cannot be undone.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 justify-end">
                <button class="px-4 py-2 rounded-DEFAULT font-body-strong text-body-strong text-on-surface-variant hover:text-on-surface transition-colors duration-200" onclick="document.getElementById('force-delete-modal').classList.add('hidden')">
                    Cancel
                </button>
                <button class="px-4 py-2 rounded-DEFAULT font-body-strong text-body-strong bg-error text-on-error hover:bg-[#ff897d] transition-colors duration-200" id="confirm-force-delete-btn">
                    Yes, delete
                </button>
            </div>
        </div>
    </div>

    <script>
        function restoreProject(projectId) {
            if (confirm('Restore this project to dashboard?')) {
                fetch(`/projects/${projectId}/restore`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                }).then(() => window.location.reload());
            }
        }

        function confirmForceDelete(projectId) {
            const modal = document.getElementById('force-delete-modal');
            modal.classList.remove('hidden');
            document.getElementById('confirm-force-delete-btn').onclick = function() {
                fetch(`/projects/${projectId}/force-delete`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                }).then(() => window.location.reload());
            };
        }
    </script>
@endsection
