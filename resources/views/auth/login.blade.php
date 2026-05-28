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
<body class="min-h-screen bg-black relative overflow-hidden flex items-center justify-center">

    {{-- Background shapes --}}
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
            <a href="/" class="inline-flex items-center gap-2 justify-center">
                <div class="flex items-center gap-1.5">
                    <div class="w-7 h-7 rounded flex items-center justify-center" style="background:#03EF62">
                        <span class="font-black text-sm" style="font-style:normal;color:#05192D">D</span>
                    </div>
                    <svg width="10" height="14" viewBox="0 0 10 14" fill="none">
                        <path d="M1 1L9 7L1 13" stroke="#03EF62" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="font-bold text-white text-base" style="font-style:italic;letter-spacing:-0.5px">datacamp</span>
                </div>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <h1 class="text-2xl font-bold text-center text-gray-900 mb-6">Welcome Back!</h1>

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