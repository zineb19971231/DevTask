@extends('layouts.devtrack')

@section('title', 'Edit Project - DevTrack')

@section('header')
    <div class="flex items-center gap-2">
        <a href="{{ route('projects.show', $project) }}" class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <h2 class="font-h2 text-h2 font-bold text-on-surface">Edit Project: {{ $project->title }}</h2>
    </div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-surface border border-outline-variant rounded-xl p-8 shadow-sm">
        <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-2">
                <label for="titre" class="font-body-strong text-on-surface">Project Title</label>
                <input type="text" name="titre" id="titre" value="{{ old('titre', $project->titre) }}" required
                    class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"
                    placeholder="e.g. Next-Gen Cloud Platform">
            </div>

            <div class="space-y-2">
                <label for="description" class="font-body-strong text-on-surface">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all resize-none"
                    placeholder="Briefly describe the project goals and scope...">{{ old('description', $project->description) }}</textarea>
            </div>

            <div class="space-y-2">
                <label for="deadline" class="font-body-strong text-on-surface">Deadline</label>
                <input type="date" name="deadline" id="deadline" value="{{ $project->deadline ? $project->deadline->format('Y-m-d') : '' }}"
                    class="w-full bg-background border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
            </div>

            <div class="pt-4 flex items-center justify-between gap-4">
                <button type="button" onclick="if(confirm('Archive this project?')) document.getElementById('delete-project-form').submit();" class="text-error hover:underline font-body-strong">
                    Archive Project
                </button>
                <div class="flex items-center gap-4">
                    <a href="{{ route('projects.show', $project) }}" class="px-6 py-2 rounded-lg font-body-strong text-on-surface-variant hover:bg-surface-variant transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="bg-primary text-on-primary px-8 py-2 rounded-lg font-body-strong hover:bg-primary-fixed transition-colors shadow-lg shadow-primary/20">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-project-form" action="{{ route('projects.destroy', $project) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
