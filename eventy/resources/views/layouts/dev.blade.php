<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Eventy') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Temporary styles for development - when Vite is not built -->
        <style>
            /* Basic TailwindCSS-like styles */
            .font-sans { font-family: 'figtree', sans-serif; }
            .antialiased { -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale; }
            .min-h-screen { min-height: 100vh; }
            .bg-gray-100 { background-color: #f7fafc; }
            .dark .dark\:bg-gray-900 { background-color: #1a202c; }
            .bg-white { background-color: #ffffff; }
            .dark .dark\:bg-gray-800 { background-color: #2d3748; }
            .shadow { box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); }
            .max-w-7xl { max-width: 80rem; }
            .mx-auto { margin-left: auto; margin-right: auto; }
            .py-6 { padding-top: 1.5rem; padding-bottom: 1.5rem; }
            .px-4 { padding-left: 1rem; padding-right: 1rem; }
            .sm\:px-6 { padding-left: 1.5rem; padding-right: 1.5rem; }
            .lg\:px-8 { padding-left: 2rem; padding-right: 2rem; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation placeholder -->
            <div style="background-color: #1a202c; color: white; padding: 1rem;">
                Navigation Placeholder
            </div>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html> 