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
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "background": "#13131b",
                        "surface": "#13131b",
                        "surface-container-lowest": "#0d0d15",
                        "on-surface": "#e4e1ed",
                        "on-surface-variant": "#c7c4d7",
                        "primary": "#c0c1ff",
                        "primary-container": "#8083ff",
                        "on-primary": "#1000a9",
                        "on-primary-container": "#0d0096",
                        "inverse-primary": "#494bd6",
                        "outline": "#908fa0",
                        "outline-variant": "#464554",
                        "error": "#ffb4ab",
                        "error-container": "#93000a",
                        "on-error": "#690005",
                        "secondary": "#c2c6d7",
                        "secondary-container": "#444956",
                        "tertiary": "#ffb783",
                        "tertiary-container": "#d97721",
                        "surface-container": "#1f1f27",
                        "surface-container-high": "#292932",
                        "surface-container-highest": "#34343d",
                    },
                    fontFamily: {
                        "h1": ["DM Sans"],
                        "h2": ["DM Sans"],
                        "h3": ["DM Sans"],
                        "body": ["Inter"],
                        "body-strong": ["Inter"],
                        "caption": ["Inter"],
                        "label-mono": ["JetBrains Mono"],
                    },
                    fontSize: {
                        "h1": ["28px", { lineHeight: "36px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "h2": ["24px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "h3": ["20px", { lineHeight: "28px", letterSpacing: "0", fontWeight: "700" }],
                        "body": ["14px", { lineHeight: "20px", letterSpacing: "0", fontWeight: "400" }],
                        "body-strong": ["14px", { lineHeight: "20px", letterSpacing: "0", fontWeight: "600" }],
                        "caption": ["12px", { lineHeight: "16px", letterSpacing: "0", fontWeight: "400" }],
                        "label-mono": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "500" }],
                    },
                    spacing: {
                        "navbar_height": "56px",
                        "sidebar_width": "240px",
                        "container_padding": "24px",
                        "gutter": "16px",
                    },
                },
            },
        }
    </script>
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
<body class="bg-surface-container-lowest text-on-surface font-body text-body min-h-screen flex flex-col items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none opacity-[0.03]" style="background-image: radial-gradient(circle, #e4e1ed 1px, transparent 1px); background-size: 24px 24px;"></div>
    <div class="w-full sm:w-[420px] bg-surface border border-surface-container-high rounded-xl p-[40px] relative z-10 shadow-2xl shadow-black/50">
        @yield('content')
    </div>
    <div class="mt-12 text-center relative z-10">
        <p class="font-caption text-caption text-outline">DevTrack — Built for dev teams. Not spreadsheets.</p>
    </div>
</body>
</html>
