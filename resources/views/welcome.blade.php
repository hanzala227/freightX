<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FreitX</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; background: #ffffff; margin: 0; }
        .minimal-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .logo {
            width: 250px;
            height: auto;
            margin-bottom: 40px;
        }
        .btn-simple {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #e2e8f0;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-simple:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
        .btn-primary {
            background-color: #00b4d8;
            color: white;
            border: 1px solid #00b4d8;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px rgba(0, 180, 216, 0.2);
        }
        .btn-primary:hover {
            background-color: #0096c7;
            box-shadow: 0 6px 12px rgba(0, 180, 216, 0.3);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="antialiased">
    @include('components.global-preloader')

    <div class="minimal-container">
        <!-- Logo -->
        <img src="{{ asset('assets/images/logo.png') }}" alt="FreitX" class="logo" onerror="this.src='https://ui-avatars.com/api/?name=FreitX&background=fff&color=00b4d8&size=200'">
        
        <div class="flex gap-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-primary">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-simple">Register</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>

</body>
</html>
