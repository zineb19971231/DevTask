<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevTrack - ' . config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            background-color: #0D0F14;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.05'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-background text-on-background font-body min-h-screen flex overflow-hidden">
    <!-- Sidebar -->
    <nav class="fixed left-0 top-0 h-full w-sidebar_width bg-surface border-r border-outline-variant flex flex-col py-4 z-50">
        <div class="px-gutter mb-8 flex items-center gap-3">
            <div class="w-8 h-8 rounded bg-primary-container flex items-center justify-center text-on-primary-container">
                <span class="material-symbols-outlined text-[20px]">terminal</span>
            </div>
            <div>
                <h1 class="font-h3 text-h3 font-bold text-on-surface">DevTrack</h1>
                <p class="font-caption text-caption text-on-surface-variant">V-Core Engine</p>
            </div>
        </div>

        <div class="flex-1 flex flex-col gap-1 px-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('dashboard') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
            <a href="{{ route('projects.index') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('projects.index') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">folder</span>
                Projects
            </a>
            <a href="{{ route('tasks.global') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('tasks.global') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">list_alt</span>
                Tasks
            </a>
            <a href="{{ route('backlog') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('backlog') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">inventory_2</span>
                Backlog
            </a>
            <a href="{{ route('board') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('board') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">view_kanban</span>
                Board
            </a>
            <a href="{{ route('team') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('team') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">group</span>
                Team
            </a>
            <div class="mt-4 mb-2 px-6">
                <p class="font-caption text-caption text-outline uppercase tracking-wider">System</p>
            </div>
            <a href="{{ route('projects.archives') }}" class="flex items-center gap-3 px-4 py-2 {{ request()->routeIs('projects.archives') ? 'text-primary border-l-2 border-primary bg-primary/5' : 'text-on-surface-variant border-l-2 border-transparent hover:bg-surface-variant hover:text-on-surface' }} transition-colors duration-150 ease-linear rounded-r-lg">
                <span class="material-symbols-outlined">inventory_2</span>
                Archives
            </a>
        </div>

        <div class="px-4 mt-auto">
            @auth
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-full bg-surface-variant overflow-hidden border border-outline-variant">
                        <img src="{{ auth()->user()->avatar ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCsL5Cc3pQmi5rSSro3jCgmQKBn9R5lbGTy7zZ7m-wo-VJjji5jWG_BAIBZFqjA-1jLwbBVIQpPxrf7e-DPd2uDFthFHqZsdntH1RVHBp7U9VP_mf0gjbf02vY1Dh5xD_AdWr_bLVWa7A5FFAhc3Zlh5blEMG-dGA6IbbTE5s0d_PDmfq1o4oz2P_awswN-CyGLVsdS7op2FLqEVGQ29E1mZ5jVBEuMXkWMCCxwcdPXWw1_uSmHCPEL8m4FRwoZoib9mr4AAzP286KU' }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-body-strong text-body-strong text-on-surface">{{ auth()->user()->name }}</p>
                        <span class="inline-block bg-surface-variant text-on-surface-variant font-label-mono text-label-mono px-2 py-0.5 rounded-full mt-0.5 text-xs">
                            {{ auth()->user()->role === 'lead' ? 'Team Lead' : 'Developer' }}
                        </span>
                    </div>
                </div>
            @endauth
            @if(auth()->user()->role === 'lead')
            <a href="{{ route('projects.create') }}" class="w-full bg-surface-variant text-on-surface font-body-strong text-body-strong py-2 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-outline-variant transition-colors mb-2">
                <span class="material-symbols-outlined text-[18px]">create_new_folder</span>
                New Project
            </a>
            @endif
            <a href="{{ route('projects.index') }}" class="w-full bg-primary text-on-primary font-body-strong text-body-strong py-2 px-4 rounded-lg flex items-center justify-center gap-2 hover:bg-primary-fixed transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New Task
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="ml-sidebar_width flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top Header -->
        <header class="h-navbar_height bg-surface border-b border-outline-variant flex items-center justify-between px-container_padding z-40">
            <div class="flex items-center gap-4">
                @yield('header')
            </div>
            <div class="flex items-center gap-4">
                <button class="text-on-surface-variant hover:text-primary transition-colors p-2 rounded-full hover:bg-surface-variant">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button class="text-on-surface-variant hover:text-primary transition-colors p-2 rounded-full hover:bg-surface-variant">
                    <span class="material-symbols-outlined">settings</span>
                </button>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:text-error transition-colors p-2 rounded-full hover:bg-error-container/20">
                        <span class="material-symbols-outlined">logout</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-container_padding">
            @yield('content')
        </div>
    </main>

    @stack('modals')
</body>
</html>
