<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Register</h2>
        <p class="text-slate-500 mt-2 font-medium">Join the next generation of freight management.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="space-y-1.5">
            <label for="name" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider ml-1">Full Name</label>
            <input id="name" 
                   type="text" 
                   name="name" 
                   value="{{ old('name') }}" 
                   required 
                   autofocus 
                   class="premium-input"
                   placeholder="John Doe"
            >
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div class="mt-5 space-y-1.5">
            <label for="email" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider ml-1">Email Address</label>
            <input id="email" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   class="premium-input"
                   placeholder="john@fms.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div class="mt-5 space-y-1.5">
            <label for="password" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider ml-1">Password</label>
            <input id="password" 
                   type="password" 
                   name="password" 
                   required 
                   class="premium-input"
                   placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-5 space-y-1.5">
            <label for="password_confirmation" class="text-[13px] font-bold text-slate-700 uppercase tracking-wider ml-1">Confirm Password</label>
            <input id="password_confirmation" 
                   type="password" 
                   name="password_confirmation" 
                   required 
                   class="premium-input"
                   placeholder="••••••••"
            >
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="mt-8">
            <button type="submit" class="premium-btn group">
                Create Account
                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>

        <div class="mt-10 pt-8 border-t border-slate-100 text-center">
            <p class="text-[13px] text-slate-500 font-medium">
                Already registered? 
                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-bold ml-1">Sign in instead</a>
            </p>
        </div>
    </form>
</x-guest-layout>
