<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Sign In</h2>
        <p class="text-slate-500 mt-2 font-medium">Access your global freight dashboard.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1.5">
            <label for="email" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider ml-1">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   class="premium-input"
                   placeholder="admin@fms.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-6 space-y-1.5">
            <div class="flex justify-between items-center px-1">
                <label for="password" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-[12px] text-blue-600 hover:text-blue-700 font-bold" href="{{ route('password.request') }}">
                        Forgot?
                    </a>
                @endif
            </div>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="premium-input"
                   placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-6 px-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded-md border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4" name="remember">
                <span class="ms-2 text-[13px] font-medium text-slate-600">Keep me logged in</span>
            </label>
        </div>

        <div class="mt-8">
            <button type="submit" class="premium-btn group">
                Sign In 
                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>


    </form>
</x-guest-layout>
