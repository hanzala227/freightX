<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Support</h2>
        <p class="text-slate-500 mt-2 font-medium">Forgot your password? No problem. Just let us know your email address and we'll email you a password reset link.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
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
                   placeholder="john@fms.com"
            >
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <div class="mt-8">
            <button type="submit" class="premium-btn">
                Email Password Reset Link
            </button>
        </div>

        <div class="mt-10 pt-8 border-t border-slate-100 text-center">
            <a href="{{ route('login') }}" class="text-[13px] text-blue-600 hover:text-blue-700 font-bold flex items-center justify-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
