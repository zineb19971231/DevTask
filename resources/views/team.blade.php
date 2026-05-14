@extends('layouts.devtrack')

@section('title', 'Team - DevTrack')

@section('header')
    <h2 class="font-h2 text-h2 font-bold text-on-surface">Team Members</h2>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <p class="text-on-surface-variant">Manage your team and track their contributions.</p>
        </div>
        @if(auth()->user()->role === 'lead')
        <button class="bg-primary text-on-primary font-body-strong px-4 py-2 rounded-lg flex items-center gap-2 hover:bg-primary-fixed transition-colors">
            <span class="material-symbols-outlined text-[18px]">person_add</span>
            Invite Member
        </button>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($users as $user)
        <div class="bg-surface border border-outline-variant rounded-xl p-6 flex flex-col items-center text-center group hover:border-primary/50 transition-all">
            <div class="relative mb-4">
                <div class="w-20 h-20 rounded-full bg-surface-variant p-1 border-2 border-outline-variant group-hover:border-primary/30 transition-colors">
                    <img src="{{ $user->avatar ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCsL5Cc3pQmi5rSSro3jCgmQKBn9R5lbGTy7zZ7m-wo-VJjji5jWG_BAIBZFqjA-1jLwbBVIQpPxrf7e-DPd2uDFthFHqZsdntH1RVHBp7U9VP_mf0gjbf02vY1Dh5xD_AdWr_bLVWa7A5FFAhc3Zlh5blEMG-dGA6IbbTE5s0d_PDmfq1o4oz2P_awswN-CyGLVsdS7op2FLqEVGQ29E1mZ5jVBEuMXkWMCCxwcdPXWw1_uSmHCPEL8m4FRwoZoib9mr4AAzP286KU' }}" alt="{{ $user->name }}" class="w-full h-full rounded-full object-cover">
                </div>
                <span class="absolute bottom-1 right-1 w-4 h-4 rounded-full bg-success border-2 border-surface"></span>
            </div>
            
            <h3 class="font-h3 text-h3 font-bold text-on-surface">{{ $user->name }}</h3>
            <p class="text-on-surface-variant text-sm mb-4">{{ $user->email }}</p>
            
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest {{ $user->role === 'lead' ? 'bg-primary/20 text-primary' : 'bg-surface-variant text-on-surface-variant' }} mb-6">
                {{ $user->role === 'lead' ? 'Team Lead' : 'Developer' }}
            </span>
            
            <div class="w-full grid grid-cols-2 border-t border-outline-variant pt-4 gap-4">
                <div class="flex flex-col items-center">
                    <span class="font-h3 text-h3 font-bold text-on-surface">{{ $user->tasks_count }}</span>
                    <span class="text-[10px] text-on-surface-variant uppercase font-bold tracking-tighter">Total Tasks</span>
                </div>
                <div class="flex flex-col items-center border-l border-outline-variant">
                    <span class="font-h3 text-h3 font-bold text-on-surface">{{ $user->projects()->count() }}</span>
                    <span class="text-[10px] text-on-surface-variant uppercase font-bold tracking-tighter">Projects</span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
