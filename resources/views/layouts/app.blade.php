<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Warehouse Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <!-- jQuery and Select2 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary": "#1c3700",
                        "on-primary": "#532200",
                        "primary": "#ffb68c",
                        "outline": "#a28c81",
                        "on-background": "#e3e2e2",
                        "surface-container": "#1f2020",
                        "tertiary-fixed": "#ffe16d",
                        "on-tertiary-fixed-variant": "#544600",
                        "tertiary": "#e9c400",
                        "surface-tint": "#ffb68c",
                        "surface-container-high": "#292a2a",
                        "on-secondary-fixed-variant": "#2c5003",
                        "secondary": "#a8d47a",
                        "on-primary-fixed-variant": "#753401",
                        "on-secondary-fixed": "#0e2000",
                        "on-tertiary": "#3a3000",
                        "on-primary-fixed": "#321200",
                        "primary-fixed-dim": "#ffb68c",
                        "secondary-fixed": "#c3f094",
                        "outline-variant": "#54433a",
                        "error": "#ffb4ab",
                        "surface-container-lowest": "#0d0e0f",
                        "on-tertiary-fixed": "#221b00",
                        "inverse-on-surface": "#303031",
                        "tertiary-container": "#c9a900",
                        "surface-variant": "#343535",
                        "on-secondary-container": "#97c26b",
                        "tertiary-fixed-dim": "#e9c400",
                        "on-error-container": "#ffdad6",
                        "on-surface-variant": "#dac2b6",
                        "error-container": "#93000a",
                        "on-tertiary-container": "#4c3e00",
                        "secondary-container": "#2c5003",
                        "primary-container": "#8b4513",
                        "surface-container-highest": "#343535",
                        "on-error": "#690005",
                        "primary-fixed": "#ffdbc9",
                        "surface": "#121414",
                        "surface-container-low": "#1b1c1c",
                        "on-primary-container": "#ffc29f",
                        "surface-dim": "#121414",
                        "surface-bright": "#383939",
                        "secondary-fixed-dim": "#a8d47a",
                        "background": "#121414",
                        "inverse-surface": "#e3e2e2",
                        "on-surface": "#e3e2e2",
                        "inverse-primary": "#934b19"
                    },
                    borderRadius: {
                        "DEFAULT": "0px",
                        "lg": "0px",
                        "xl": "0px",
                        "full": "0px"
                    },
                    spacing: {
                        "stack-md": "24px",
                        "pixel-unit": "4px",
                        "container-margin": "32px",
                        "gutter": "16px",
                        "stack-sm": "8px",
                        "64": "64px",
                        "128": "128px"
                    },
                    fontFamily: {
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-sm": ["Space Grotesk"],
                        "body-lg": ["Inter"]
                    },
                    fontSize: {
                        "headline-md": ["24px", { lineHeight: "1.2", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-lg": ["40px", { lineHeight: "1.1", letterSpacing: "-0.05em", fontWeight: "900" }],
                        "label-sm": ["12px", { lineHeight: "1", fontWeight: "700" }],
                        "body-lg": ["16px", { lineHeight: "1.5", fontWeight: "600" }]
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        
        /* Pixel Beveling Base */
        .pixel-box {
            border: 4px solid transparent;
            position: relative;
        }
        .pixel-box::before {
            content: '';
            position: absolute;
            top: -4px; left: -4px; right: -4px; bottom: -4px;
            border-top: 4px solid rgba(255, 255, 255, 0.1);
            border-left: 4px solid rgba(255, 255, 255, 0.1);
            border-bottom: 4px solid rgba(0, 0, 0, 0.4);
            border-right: 4px solid rgba(0, 0, 0, 0.4);
            pointer-events: none;
            z-index: 10;
        }
        .pixel-box-hover:hover::before, .pixel-box-active:active::before {
            border-top: 4px solid rgba(0, 0, 0, 0.4);
            border-left: 4px solid rgba(0, 0, 0, 0.4);
            border-bottom: 4px solid rgba(255, 255, 255, 0.1);
            border-right: 4px solid rgba(255, 255, 255, 0.1);
        }
        
        .pixel-input {
            border-top: 4px solid rgba(0, 0, 0, 0.6);
            border-left: 4px solid rgba(0, 0, 0, 0.6);
            border-bottom: 4px solid rgba(255, 255, 255, 0.1);
            border-right: 4px solid rgba(255, 255, 255, 0.1);
        }

        .pixel-btn-primary {
            background-color: #e9c400; /* Gold Yellow equivalent */
            color: #3a3000;
            border: 4px solid transparent;
            position: relative;
        }
        .pixel-btn-primary::before {
            content: '';
            position: absolute;
            top: -4px; left: -4px; right: -4px; bottom: -4px;
            border-top: 4px solid rgba(255, 255, 255, 0.5);
            border-left: 4px solid rgba(255, 255, 255, 0.5);
            border-bottom: 4px solid rgba(0, 0, 0, 0.3);
            border-right: 4px solid rgba(0, 0, 0, 0.3);
            pointer-events: none;
        }
        .pixel-btn-primary:active {
            transform: translate(2px, 2px);
        }
        .pixel-btn-primary:active::before {
            border-top: 4px solid rgba(0, 0, 0, 0.3);
            border-left: 4px solid rgba(0, 0, 0, 0.3);
            border-bottom: 4px solid rgba(255, 255, 255, 0.5);
            border-right: 4px solid rgba(255, 255, 255, 0.5);
        }
        
        .pixel-glow {
            border: 4px solid #e9c400 !important;
            box-shadow: 0 0 10px rgba(233, 196, 0, 0.5);
        }
        .pixel-glow::before {
            display: none;
        }

        .pixel-border {
            border: 4px solid #000;
            box-shadow: inset 2px 2px 0px rgba(255, 255, 255, 0.1), inset -2px -2px 0px rgba(0, 0, 0, 0.4);
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
    </style>
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #121414 !important;
            border: 2px solid #54433a !important;
            border-radius: 0px !important;
            height: 48px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e3e2e2 !important;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 14px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
        }
        .select2-dropdown {
            background-color: #1f2020 !important;
            border: 2px solid #ffb68c !important;
            border-radius: 0px !important;
        }
        .select2-search__field {
            background-color: #121414 !important;
            border: 2px solid #54433a !important;
            color: #e3e2e2 !important;
            border-radius: 0px !important;
        }
        .select2-results__option {
            color: #dac2b6 !important;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 700;
        }
        .select2-results__option--highlighted[aria-selected] {
            background-color: #ffb68c !important;
            color: #121414 !important;
        }
    </style>
</head>

<body class="bg-background text-on-background h-screen flex font-body-lg text-body-lg overflow-hidden">
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- Top Navigation -->
        @include('layouts.navigation')

        <!-- Main Content Canvas -->
        <main class="flex-1 overflow-y-auto p-gutter md:p-container-margin bg-surface-container-low">
            <div class="max-w-[1200px] mx-auto space-y-stack-md">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="bg-green-900/20 border-l-4 border-green-500 p-4 mb-6 pixel-box text-green-400">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">check_circle</span>
                            <p class="font-bold uppercase text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-900/20 border-l-4 border-red-500 p-4 mb-6 pixel-box text-red-400">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined">error</span>
                            <p class="font-bold uppercase text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                {{ $slot }}

                <!-- Footer -->
                <footer class="mt-container-margin py-6 border-t-4 border-outline-variant text-center">
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest">
                        &copy; {{ date('Y') }} Warehouse Management System. All rights reserved.
                    </p>
                </footer>
            </div>
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.confirmDelete = function(formId, itemName) {
            const phrase = "Saya yakin ingin menghapus " + itemName;
            Swal.fire({
                title: 'KONFIRMASI PENGHAPUSAN',
                html: `<div class="text-left">
                        <p class="mb-4 text-xs font-black uppercase tracking-widest text-stone-500">Ketik kalimat berikut untuk mengonfirmasi:</p>
                        <p class="mb-4 p-4 bg-stone-950 border-2 border-error/20 text-error font-mono text-sm select-none break-words">"${phrase}"</p>
                       </div>`,
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                inputPlaceholder: 'KETIK DI SINI...',
                icon: 'warning',
                iconColor: '#ffb4ab',
                showCancelButton: true,
                confirmButtonColor: '#ffb4ab',
                cancelButtonColor: '#383939',
                confirmButtonText: 'HAPUS PERMANEN',
                cancelButtonText: 'BATAL',
                background: '#121414',
                color: '#e3e2e2',
                customClass: {
                    popup: 'pixel-border',
                    confirmButton: 'pixel-btn text-stone-950 font-black',
                    cancelButton: 'pixel-btn font-black',
                    input: 'bg-stone-950 border-2 border-outline-variant text-on-surface focus:border-amber-500 focus:ring-0 pixel-border font-bold'
                },
                preConfirm: (value) => {
                    if (value !== phrase) {
                        Swal.showValidationMessage('Kalimat konfirmasi tidak sesuai!');
                        return false;
                    }
                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>
