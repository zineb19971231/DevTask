@extends('layouts.auth')

@section('title', 'DevTrack - Register')

@section('content')
    <div class="text-center mb-8 flex flex-col items-center">
        <div class="w-12 h-12 bg-surface rounded-xl border border-outline-variant flex items-center justify-center mb-4 shadow-lg shadow-black/50">
            <span class="material-symbols-outlined text-primary text-3xl font-bold" style="font-variation-settings: 'FILL' 1;">tag</span>
        </div>
        <h1 class="font-h2 text-h2 text-on-surface mb-2 tracking-tight">DevTrack</h1>
        <p class="font-body text-body text-on-surface-variant">Create your account to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf



        <!-- Name -->
        <div>
            <label class="sr-only" for="name">Full Name</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-[20px]">person</span>
                </div>
                <input id="name" class="block w-full pl-10 pr-3 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-on-surface placeholder-outline font-label-mono text-label-mono focus:ring-1 focus:ring-primary focus:border-primary transition-colors h-[48px] @error('name') border-error @enderror"
                       type="text" name="name" value="{{ old('name') }}" required placeholder="Full Name">
            </div>
            @error('name')
                <p class="mt-2 font-caption text-caption text-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label class="sr-only" for="email">Email address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-[20px]">mail</span>
                </div>
                <input id="email" class="block w-full pl-10 pr-3 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-on-surface placeholder-outline font-label-mono text-label-mono focus:ring-1 focus:ring-primary focus:border-primary transition-colors h-[48px] @error('email') border-error @enderror"
                       type="email" name="email" value="{{ old('email') }}" required placeholder="developer@company.com">
            </div>
            @error('email')
                <p class="mt-2 font-caption text-caption text-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="sr-only" for="password">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-[20px]">lock</span>
                </div>
                <input id="password" class="block w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-on-surface placeholder-outline font-label-mono text-label-mono focus:ring-1 focus:ring-primary focus:border-primary transition-colors h-[48px] @error('password') border-error @enderror"
                       type="password" name="password" required placeholder="••••••••">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                </button>
            </div>
            @error('password')
                <p class="mt-2 font-caption text-caption text-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label class="sr-only" for="password_confirmation">Confirm Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-[20px]">lock</span>
                </div>
                <input id="password_confirmation" class="block w-full pl-10 pr-10 py-3 bg-surface-container-lowest border border-outline-variant rounded-lg text-on-surface placeholder-outline font-label-mono text-label-mono focus:ring-1 focus:ring-primary focus:border-primary transition-colors h-[48px]"
                       type="password" name="password_confirmation" required placeholder="••••••••">
                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface transition-colors">
                    <span class="material-symbols-outlined text-[20px]">visibility_off</span>
                </button>
            </div>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm font-body-strong text-body-strong text-on-primary bg-primary hover:bg-primary-fixed focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary focus:ring-offset-surface transition-colors h-[48px] items-center">
                Create Account
                <span class="material-symbols-outlined ml-2 text-[20px]">arrow_forward</span>
            </button>
        </div>
    </form>

    <div class="mt-6 text-center">
        <p class="font-body text-body text-on-surface-variant">
            Already have an account?
            <a class="font-body-strong text-primary hover:text-primary-fixed transition-colors" href="{{ route('login') }}">Sign in</a>
        </p>
    </div>

    <script>


        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const icon = this.querySelector('span');
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.textContent = 'visibility';
                } else {
                    input.type = 'password';
                    icon.textContent = 'visibility_off';
                }
            });
        });
    </script>
@endsection
