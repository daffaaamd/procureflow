<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ProcureFlow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Nunito+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Nunito Sans', 'sans-serif'],
                        display: ['Montserrat', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            400: '#f87171',
                            500: '#ef4444', // Red theme matching the image
                            600: '#dc2626',
                            700: '#b91c1c',
                        }
                    },
                    boxShadow: {
                        'soft-xl': '0 20px 40px -15px rgba(0,0,0,0.1)',
                        'glow': '0 10px 25px -5px rgba(239, 68, 68, 0.4)',
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        {!! file_get_contents(resource_path('css/app.css')) !!}
        body {
            background-color: #f3f4f6;
            overflow-x: hidden;
        }
        /* Background decorative shapes */
        .bg-shape-1 {
            position: absolute;
            top: -10%;
            left: -5%;
            width: 450px;
            height: 450px;
            background: #ef4444;
            border-radius: 50%;
            z-index: 0;
        }
        .bg-shape-2 {
            position: absolute;
            bottom: -10%;
            right: -5%;
            width: 350px;
            height: 350px;
            background: #ef4444;
            border-radius: 50%;
            z-index: 0;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-600 min-h-screen flex items-center justify-center relative">
    
    <!-- Decorative Background Shapes -->
    <div class="bg-shape-1 hidden md:block"></div>
    <div class="bg-shape-2 hidden md:block"></div>

    <!-- Main Card Container -->
    <div class="relative z-10 flex flex-col md:flex-row w-full max-w-[900px] bg-white rounded-[2rem] shadow-soft-xl overflow-hidden mx-4 my-8 min-h-[600px]">
        
        <!-- Left Side: Form -->
        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center items-center">
            
            <div class="w-full max-w-sm flex flex-col items-start text-left">
                
                <!-- Center aligned header group -->
                <div class="w-full flex flex-col items-center mb-8">
                    <!-- Company Icon Above Welcome -->
                    <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center shadow-lg mb-6">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>

                    <p class="text-sm md:text-base font-display font-bold text-slate-500 tracking-wider uppercase mb-2">Welcome To</p>
                    
                    <!-- App Logo -->
                    <div class="flex items-center gap-2">
                        <svg class="w-8 h-8 text-brand-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span class="font-display text-2xl font-bold tracking-tight text-slate-800 uppercase">
                            Procure<span class="text-brand-500">Flow</span>
                        </span>
                    </div>
                </div>

                <p class="text-sm md:text-base text-slate-600 mb-8 leading-relaxed font-medium">
                    Secure access to the procurement portal of <strong class="text-slate-900 font-bold">PT XYZ Enterprise</strong>. Log in to manage your enterprise workflows.
                </p>

                <!-- Form -->
                <form action="{{ route('login.post') }}" method="POST" class="w-full space-y-4">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="bg-red-50 text-red-500 text-xs text-center p-2 rounded-lg mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300">
                            <!-- User Icon -->
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required placeholder="Username or Email" value="admin@procureflow.test" 
                            class="block w-full pl-11 pr-4 py-3 border border-slate-200 rounded-full text-sm text-slate-600 placeholder-slate-300 focus:outline-none focus:border-brand-400 focus:ring-1 focus:ring-brand-400 transition-colors">
                    </div>

                    <!-- Password Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-300">
                            <!-- Lock Icon -->
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Password" value="password" 
                            class="block w-full pl-11 pr-10 py-3 border border-slate-200 rounded-full text-sm text-slate-600 placeholder-slate-300 focus:outline-none focus:border-brand-400 focus:ring-1 focus:ring-brand-400 transition-colors">
                        
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-slate-300 hover:text-brand-500">
                            <!-- Question Mark Icon -->
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>

                    <input type="hidden" name="remember" value="1">

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 rounded-full shadow-glow text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 focus:outline-none transform hover:-translate-y-0.5 transition-all duration-200 uppercase tracking-wider">
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="mt-12 text-center w-full pt-6 border-t border-slate-100">
                    <p class="text-[11px] text-slate-400 font-medium tracking-wide">
                        &copy; {{ date('Y') }} PT XYZ Enterprise. All rights reserved.<br>
                        Designed & Made by <span class="text-brand-500 font-semibold uppercase tracking-wider">Daffa Ahmad Baihaqi</span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side: Image and Overlay -->
        <div class="hidden md:flex md:w-1/2 relative flex-col justify-center items-center p-12 text-center overflow-hidden">
            <!-- Corporate/PT Image -->
            <img src="https://images.unsplash.com/photo-1578574577315-3fbeb0cecdc2?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" 
                 alt="Corporate Building" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <!-- Red Gradient Overlay exactly like the image -->
            <div class="absolute inset-0 bg-brand-500/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-red-600/90 to-orange-500/70"></div>
            
            <!-- Content over image -->
            <div class="relative z-10 flex flex-col items-center mt-8">
                <div class="mb-2">
                    <svg class="w-20 h-20 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                </div>
                
                <h2 class="font-display text-4xl font-semibold text-white uppercase tracking-[0.2em] mb-6">
                    ProcureFlow
                </h2>
                
                <p class="text-white/90 text-xs leading-relaxed max-w-sm mx-auto font-light tracking-wide px-4">
                    A centralized procurement management platform that simplifies purchasing processes, from purchase requests and approvals to orders, deliveries, invoices, and payments.
                </p>
            </div>
        </div>

    </div>
</body>
</html>
