<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in to your account - {{ config('app.name', 'WMS') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&amp;family=Space+Grotesk:wght@700&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary": "#ffb68c",
                        "secondary": "#a8d47a",
                        "tertiary": "#e9c400",
                        "background": "#121414",
                        "on-background": "#e3e2e2",
                        "surface-container": "#1f2020",
                        "surface-variant": "#343535",
                        "outline-variant": "#54433a",
                        "on-surface": "#e3e2e2",
                        "on-surface-variant": "#dac2b6",
                        "error": "#ffb4ab",
                    },
                    "fontFamily": {
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-sm": ["Space Grotesk"],
                        "headline-md": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .pixel-border {
            border: 4px solid #1c1917;
            box-shadow: inset 2px 2px 0px #57534e, inset -2px -2px 0px #1c1917;
        }

        .pixel-box {
            border: 4px solid #000;
            position: relative;
            background: #1f2020;
        }

        .pixel-box::before {
            content: '';
            position: absolute;
            inset: 0;
            border: 2px solid rgba(255, 255, 255, 0.1);
            pointer-events: none;
        }

        .pixel-btn {
            border: 4px solid #000;
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.3), inset -2px -2px 0px rgba(0, 0, 0, 0.4);
            transition: all 0.1s;
        }

        .pixel-btn:active {
            box-shadow: inset -2px -2px 0px rgba(255, 255, 255, 0.3), inset 2px 2px 0px rgba(0, 0, 0, 0.4);
            transform: translate(2px, 2px);
        }

        .pixel-input {
            border: 4px solid #000;
            background: #0d0e0f;
            box-shadow: inset -2px -2px 0px rgba(255, 255, 255, 0.05), inset 2px 2px 0px rgba(0, 0, 0, 0.5);
        }

        /* Autofill override */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #0d0e0f inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>

<body class="bg-background text-on-background min-h-screen flex flex-col justify-center items-center p-6 font-body-lg">
    <div class="w-full max-w-md">
        <!-- Brand -->
        <div class="flex flex-col items-center mb-8">
            <div class="w-16 h-16 bg-amber-500 pixel-border flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-stone-900 text-4xl">warehouse</span>
            </div>
            <h1 class="font-headline-lg text-4xl text-amber-500 uppercase italic tracking-tighter">VOXEL SYSTEM</h1>
            <p class="font-label-sm text-xs text-on-surface-variant uppercase tracking-widest mt-1">Version 1.0 Stable
            </p>
        </div>

        <div class="pixel-box p-8 bg-surface-container relative">
            <div class="mb-8">
                <h2 class="text-2xl font-black text-on-surface uppercase tracking-tight">Login Access</h2>
                <p class="text-sm text-on-surface-variant mt-1">Enter credentials to enter the grid</p>
            </div>

            @if ($errors->any())
                <div
                    class="bg-red-600 text-stone-950 p-4 mb-6 text-sm font-black uppercase pixel-btn flex items-center gap-3">
                    <span class="material-symbols-outlined">report</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest mb-2" for="login">Username / Email</label>
                    <input class="w-full pixel-input bg-stone-950 px-4 py-3 text-white focus:outline-none focus:border-amber-500 transition-all font-bold placeholder:text-stone-700" 
                           id="login" name="login" value="{{ old('login') }}" placeholder="ENTER IDENTITY" type="text" required />
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-[10px] font-black text-on-surface-variant uppercase tracking-widest" for="password">Security Key</label>
                        <a class="text-[9px] font-black text-amber-500 hover:text-amber-400 uppercase" href="#">Reset Key?</a>
                    </div>
                    <input class="w-full pixel-input bg-stone-950 px-4 py-3 text-white focus:outline-none focus:border-amber-500 transition-all font-bold placeholder:text-stone-700" 
                           id="password" name="password" placeholder="••••••••" type="password" required />
                </div>

                <div class="flex items-center">
                    <input class="w-5 h-5 pixel-input rounded-none bg-stone-950 checked:bg-amber-500 cursor-pointer"
                        id="remember" name="remember" type="checkbox" />
                    <label
                        class="ml-3 block text-xs font-black text-on-surface-variant uppercase cursor-pointer select-none"
                        for="remember">
                        Keep Session Active
                    </label>
                </div>

                <button
                    class="w-full pixel-btn bg-amber-500 hover:bg-amber-400 text-stone-900 py-4 mt-4 font-black text-sm uppercase flex justify-center items-center gap-2"
                    type="submit">
                    <span class="material-symbols-outlined text-xl">login</span>
                    Initialize Login
                </button>
            </form>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] text-on-surface-variant font-black uppercase tracking-[0.2em]">
                &copy; 2026 Warehouse Management System Jaringan Minimarket
            </p>
        </div>
    </div>
</body>

</html>