<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FreightX') }}</title>

    <!-- Professional Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .login-container {
            display: flex;
            height: 100vh;
            width: 100%;
        }
        .login-visual {
            flex: 1.2;
            position: relative;
            background-image: url('/assets/images/login-bg.png');
            background-size: cover;
            background-position: center;
            display: none;
        }
        @media (min-width: 1024px) {
            .login-visual { display: block; }
        }
        .visual-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15, 23, 42, 0.8), rgba(0, 0, 0, 0.2));
        }
        .login-form-side {
            flex: 1;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }
        .form-card {
            width: 100%;
            max-width: 400px;
        }
        .font-oswald { font-family: 'Oswald', sans-serif; }
        
        .premium-input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            font-size: 0.95rem;
            color: #1e293b;
        }
        .premium-input:focus {
            outline: none;
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .premium-btn {
            width: 100%;
            padding: 0.875rem;
            background: #1e293b;
            color: white;
            border-radius: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
        }
        .premium-btn:hover {
            background: #0f172a;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .brand-text {
            color: #ffffff;
            position: absolute;
            bottom: 4rem;
            left: 4rem;
            z-index: 2;
        }
    </style>
</head>
<body class="antialiased">
    <script>window.__turbo_tracking_listeners = true;</script>
    @include('components.global-preloader')
    <div class="login-container">
        <!-- Visual Side -->
        <div class="login-visual shadow-2xl">
            <div class="visual-overlay"></div>
            <div class="brand-text">
                <h1 class="font-oswald text-5xl font-bold tracking-tight mb-2">FreightX</h1>
                <p class="text-gray-300 text-lg font-medium opacity-80 uppercase tracking-widest">Global Freight Management Solutions</p>
                <div class="mt-8 flex space-x-6 text-sm">
                    <div class="flex items-center text-gray-400">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Real-time Tracking
                    </div>
                    <div class="flex items-center text-gray-400">
                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2"></span> Secure Ledger
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Side -->
        <div class="login-form-side">
            <div class="form-card">
                <div class="mb-10 lg:hidden text-center">
                     <h1 class="font-oswald text-3xl font-bold text-slate-800 tracking-tight">FreightX</h1>
                </div>
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
