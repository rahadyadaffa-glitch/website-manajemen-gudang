<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - {{ config('app.name', 'Warehouse Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full antialiased text-gray-900">
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <!-- Branding -->
            <div class="flex flex-col items-center">
                <div
                    class="flex h-36 w-36 items-center justify-center rounded-3xl bg-white shadow-sm border border-gray-100 p-2 mb-8">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-auto object-contain">
                </div>
                <h2 class="text-center text-3xl font-extrabold tracking-tight text-gray-900">
                    Sign in to your account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-500 font-medium">
                    Enter your credentials to manage inventory
                </p>
            </div>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white px-8 py-10 shadow-[0_1px_3px_rgba(0,0,0,0.05),0_20px_25px_-5px_rgba(0,0,0,0.05)] sm:rounded-2xl border border-gray-100">
                <form class="space-y-6" action="{{ route('login.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded-xl bg-red-50 p-4 border border-red-100 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-red-800">{{ $errors->first() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div>
                        <label for="login" class="block text-sm font-semibold text-gray-700">Email or Username</label>
                        <div class="mt-1.5">
                            <input id="login" name="login" type="text" value="{{ old('login') }}" required
                                class="block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 sm:text-sm transition-all"
                                placeholder="name@example.com">
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                            <div class="text-sm">
                                <a href="#" class="font-bold text-blue-600 hover:text-blue-500">Forgot password?</a>
                            </div>
                        </div>
                        <div class="mt-1.5">
                            <input id="password" name="password" type="password" required
                                class="block w-full appearance-none rounded-xl border border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-blue-600 focus:outline-none focus:ring-1 focus:ring-blue-600 sm:text-sm transition-all"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600 cursor-pointer">
                            <label for="remember"
                                class="ml-2 block text-sm text-gray-600 cursor-pointer font-medium">Keep me
                                active</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="flex w-full justify-center rounded-xl bg-gray-900 px-4 py-3.5 text-sm font-bold text-white shadow-sm hover:bg-gray-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-gray-900 transition-all active:scale-[0.98]">
                            Sign in to Dashboard
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-10 text-center">
                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">
                    Warehouse Management System v1.0
                </p>
            </div>
        </div>
    </div>
</body>

</html>