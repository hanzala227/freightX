<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FreightX</title>
    
    <!-- Professional Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        html, body { 
            margin: 0; 
            padding: 0; 
            width: 100%; 
            max-width: 100%; 
            overflow-x: auto; 
            height: 100%; 
            position: relative;
        }
        body { font-family: 'Inter', sans-serif; background: #eef1f5; }
        .font-oswald { font-family: 'Oswald', sans-serif; }
        
        /* CONTENT CONTAINER REINFORCEMENT */
        .app-wrapper { 
            display: flex; 
            width: 100%; 
            max-width: 100%; 
            height: 100vh; 
            overflow: hidden; 
        }
        .main-content-wrapper { 
            display: flex; 
            flex-direction: column; 
            flex: 1; 
            min-width: 0; 
            max-width: 100%; 
            overflow: hidden; 
        }
        main { 
            flex: 1; 
            overflow-y: auto; 
            overflow-x: visible; 
            padding: 0; 
            width: 100%; 
            max-width: 100%; 
        }

        /* Sidebar Visibility Fix */
        .app-wrapper { display: flex; width: 100%; height: 100vh; overflow: hidden; }
        aside { width: 200px !important; flex-shrink: 0 !important; display: flex !important; transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important; }
        .main-content-wrapper { flex: 1; min-width: 0; display: flex; flex-direction: column; overflow: hidden; }
        
        /* Smooth Transitions */
        aside, aside > div {
            transition: width 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
        }

        /* Sidebar Collapsed State (Desktop) */
        body.sidebar-collapsed aside {
            width: 60px !important;
            overflow: visible !important; /* Allow popover menus to float outside! */
        }
        body.sidebar-collapsed aside .flex.flex-col.w-\[200px\] {
            width: 60px !important;
            overflow: visible !important;
        }
        body.sidebar-collapsed aside .custom-sidebar-scrollbar {
            overflow: visible !important;
        }
        /* Hide text elements, separators, arrows, and search bar in collapsed state */
        body.sidebar-collapsed aside nav span,
        body.sidebar-collapsed aside nav div.pt-6, /* Hide separators */
        body.sidebar-collapsed aside nav i.fa-angle-right,
        body.sidebar-collapsed aside nav i.fa-angle-down,
        body.sidebar-collapsed aside .px-3.py-4 { /* Hide search bar */
            display: none !important;
        }
        /* Center navigation icons & handles in collapsed state */
        body.sidebar-collapsed aside nav a,
        body.sidebar-collapsed aside nav button {
            padding: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            margin: 0 !important;
            justify-content: center !important;
            align-items: center !important;
            height: 42px;
            width: 100% !important;
            position: relative !important;
            overflow: hidden !important;
        }
        /* Fix collapsed icon centering for <a> tags (Dashboard, Action Center, Intelligence items) */
        body.sidebar-collapsed aside nav a.sidebar-nav-item {
            padding-left: 0 !important;
            padding-right: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            border-left: none !important;
        }
        body.sidebar-collapsed aside nav a.sidebar-nav-item i {
            margin: 0 !important;
        }
        /* Target internal flex containers inside buttons to center properly */
        body.sidebar-collapsed aside nav button .flex.items-center {
            justify-content: center !important;
            align-items: center !important;
            width: auto !important;
            position: relative !important;
            overflow: hidden !important;
        }
        body.sidebar-collapsed aside nav a i,
        body.sidebar-collapsed aside nav button i {
            margin: 0 !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            padding: 0 !important;
            font-size: 14px !important;
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
            max-width: 20px !important;
            max-height: 20px !important;
            line-height: 1 !important;
            text-align: center !important;
            display: inline-flex !important;
            justify-content: center !important;
            align-items: center !important;
            flex-shrink: 0 !important;
            flex-grow: 0 !important;
            position: relative !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
        }
        /* Center and format the logo area in collapsed state */
        body.sidebar-collapsed aside .flex.items-center.h-\[50px\].px-4 {
            justify-content: center !important;
            padding: 0 !important;
        }

        /* PREMIUM COLLAPSED FLOATING SUBMENUS */
        body.sidebar-collapsed aside nav > div {
            position: relative !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu {
            position: absolute !important;
            left: 60px !important;
            top: 0 !important;
            width: 200px !important;
            z-index: 99999 !important;
            background-color: #3b4b7a !important;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2) !important;
            border-radius: 0 4px 4px 0 !important;
            border-left: 3px solid #0ab39c !important;
            padding: 5px 0 !important;
            height: auto !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu a,
        body.sidebar-collapsed .collapsed-floating-submenu button {
            display: block !important;
            padding-left: 20px !important;
            padding-right: 15px !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
            text-align: left !important;
            color: #a0a8c1 !important;
            text-decoration: none !important;
            font-size: 9px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            width: 100% !important;
            height: auto !important;
            justify-content: flex-start !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03) !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu a:hover,
        body.sidebar-collapsed .collapsed-floating-submenu button:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu button .flex.items-center {
            justify-content: flex-start !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu button span {
            display: inline-block !important; /* Re-enable headers */
        }

        body.sidebar-collapsed .collapsed-floating-submenu button i.fa-angle-right {
            display: inline-block !important; /* Re-enable nested sub-arrows */
            margin-left: auto !important;
            width: auto !important;
        }

        body.sidebar-collapsed .collapsed-floating-submenu div[x-show="subOpen"] a {
            padding-left: 32px !important;
        }

        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        @media (max-width: 768px) {
            body:not(.sidebar-mobile-open) aside { display: none !important; }
            .sidebar-mobile-open aside { display: flex !important; position: fixed; z-index: 2000; width: 200px; }
            /* Prevent desktop collapsed styles from clashing on mobile */
            body.sidebar-collapsed aside { width: 200px !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-[#333] bg-[#eef1f5]">
    <script>window.__turbo_tracking_listeners = true;</script>
    @include('components.global-preloader')
    <div class="app-wrapper">
        <!-- Sidebar -->
        <x-sidebar />

        <div class="main-content-wrapper">
            <!-- Mobile Overlay -->
            <div onclick="document.body.classList.remove('sidebar-mobile-open')" class="fixed inset-0 bg-black/50 z-[1999] md:hidden transition-opacity cursor-pointer hidden [.sidebar-mobile-open_&]:block"></div>
            
            <!-- Top Navbar -->
            <x-navbar />

            <!-- Content Area -->
            <main class="custom-scrollbar focus:outline-none">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    <x-add-new-modal />
    @stack('scripts')
</body>
</html>

