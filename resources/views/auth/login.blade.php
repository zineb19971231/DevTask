@extends('layouts.auth')

@section('title', 'DevTrack - Login')

@section('content')
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-inverse-primary" style="font-size: 24px;">tag</span>
            <h1 class="font-h2 text-h2 text-on-surface">DevTrack</h1>
        </div>
        <p class="font-body text-body text-outline">Sign in to your workspace</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label class="block font-label-mono text-label-mono text-on-surface-variant mb-2" for="email">EMAIL ADDRESS</label>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-outline pointer-events-none">mail</span>
                <input id="email" class="w-full h-[48px] bg-surface-container-lowest border border-surface-container-high rounded-lg text-on-surface placeholder:text-outline focus:border-inverse-primary focus:ring-1 focus:ring-inverse-primary transition-all pl-12 pr-4 outline-none font-body text-body @error('email') border-error @enderror"
                       type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="developer@devtrack.io">
            </div>
            @error('email')
                <p class="mt-2 font-caption text-caption text-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-2">
                <label class="block font-label-mono text-label-mono text-on-surface-variant" for="password">PASSWORD</label>
                @if (Route::has('password.request'))
                    <a class="font-label-mono text-label-mono text-inverse-primary hover:underline" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <div class="relative flex items-center">
                <span class="material-symbols-outlined absolute left-4 text-outline pointer-events-none">lock</span>
                <input id="password" class="w-full h-[48px] bg-surface-container-lowest border border-surface-container-high rounded-lg text-on-surface placeholder:text-outline focus:border-inverse-primary focus:ring-1 focus:ring-inverse-primary transition-all pl-12 pr-12 outline-none font-body text-body"
                       type="password" name="password" required placeholder="••••••••">
                <button type="button" class="absolute right-4 text-outline hover:text-on-surface transition-colors flex items-center justify-center toggle-password">
                    <span class="material-symbols-outlined">visibility</span>
                </button>
            </div>
            @error('password')
                <p class="mt-2 font-caption text-caption text-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-surface-container-lowest border-outline-variant text-primary focus:ring-primary" name="remember">
                <span class="ml-2 text-sm text-on-surface-variant">Remember me</span>
            </label>
        </div>

        <button class="w-full h-[48px] bg-inverse-primary text-surface-container-lowest font-body-strong text-body-strong rounded-lg hover:opacity-90 transition-opacity flex items-center justify-center mt-2">
            Sign in
        </button>
    </form>

    <div class="mt-8 text-center">
        <p class="font-body text-body text-on-surface-variant">
            Don't have an account?
            <a class="text-inverse-primary hover:underline ml-1" href="{{ route('register') }}">Register</a>
        </p>
    </div>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('span');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility_off';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility';
                }
            });
        });
    </script>
@endsection
