<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In | DataCamp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .bg-shape { background: linear-gradient(135deg, #FF6B35, #F7C59F, #FFD700); }
        input:focus { outline: none; border-color: #03EF62; }
        .btn-green { background: #03EF62; color: #05192D; }
        .btn-green:hover { background: #00d455; }
    </style>
</head>
<body class="min-h-screen bg-white relative overflow-hidden flex items-center justify-center">

    {{-- Background shapes seperti DataCamp --}}
    <div class="absolute top-0 left-0 w-64 h-64 rounded-full opacity-80" style="background:#FF6B35; transform: translate(-40%, -40%)"></div>
    <div class="absolute top-0 left-0 w-48 h-48 rounded-full opacity-60" style="background:#FFD700; transform: translate(10%, -20%)"></div>
    <div class="absolute bottom-0 left-0 w-56 h-56 rounded-full opacity-70" style="background:#FF6B35; transform: translate(-20%, 30%)"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 rounded-full opacity-50" style="background:#FFD700; transform: translate(30%, 20%)"></div>
    <div class="absolute top-0 right-0 w-60 h-60 opacity-70" style="background:#FF6B35; transform: translate(40%, -30%) rotate(45deg)"></div>
    <div class="absolute top-0 right-0 w-44 h-44 opacity-50" style="background:#FFD700; transform: translate(10%, 10%) rotate(45deg)"></div>

    {{-- Form Card --}}
    <div class="relative z-10 w-full max-w-md px-6">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="4" fill="#05192D"/><path d="M8 16L14 10L20 16L14 22L8 16Z" fill="#03EF62"/><path d="M16 16L22 10L28 16L22 22L16 16Z" fill="#03EF62" opacity="0.5"/></svg>
                <span class="text-xl font-bold text-gray-900">datacamp</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Welcome Back!</h1>

            {{-- Social Login --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                    <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Google
                </button>
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                    <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#00A4EF" d="M0 0h11.5v11.5H0z"/><path fill="#FFB900" d="M12.5 0H24v11.5H12.5z"/><path fill="#00B04F" d="M0 12.5h11.5V24H0z"/><path fill="#F25022" d="M12.5 12.5H24V24H12.5z"/></svg>
                    Microsoft
                </button>
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#0A66C2"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    LinkedIn
                </button>
                <button class="flex items-center justify-center gap-2 border border-gray-200 rounded-lg py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="black"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                    Apple
                </button>
            </div>

            <div class="flex items-center gap-3 mb-5">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400">Or sign in using:</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1.5">E-mail address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 focus:border-green-400"
                        placeholder="E-mail address">
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-1.5">
                        <label class="text-sm text-gray-600">Password</label>
                        @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-gray-500 hover:text-gray-700">Need Help?</a>
                        @endif
                    </div>
                    <input type="password" name="password" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm text-gray-800 focus:border-green-400"
                        placeholder="Password">
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 mb-5">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300">
                    <label for="remember" class="text-sm text-gray-600">Remember me</label>
                </div>

                <button type="submit" class="btn-green w-full py-3 rounded-lg text-sm font-semibold">
                    Next
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-5">
                Or <a href="{{ route('register') }}" class="text-green-600 hover:text-green-500 font-medium">click here</a> to create your free account.
            </p>

            <p class="text-xs text-gray-400 text-center mt-4 leading-relaxed">
                By signing in, you accept our <a href="#" class="text-green-600">Terms of Use</a>, our <a href="#" class="text-green-600">Privacy Policy</a> and that your data is stored in the USA.
            </p>
        </div>
    </div>

</body>
</html>