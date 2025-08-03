<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ url('website-image/Group 11525.png') }}" />
    @wireUiScripts
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>{{ $title ?? 'Edyone LMS' }}</title>

    {{-- Rich Text --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
</head>

</head>

<body class="bg-gray-50 h-screen">
    <div class="h-full flex flex-col">
        <div class="flex-1 h-full flex flex-row">
            <x-notifications position="top-end" />
            <x-dialog z-index="z-50" blur="md" align="center" />

            <div x-data="{ offcanvas: false }" class="flex flex-1 h-full">
                <!-- Sidebar: Fixed on the left -->
                @if (Auth::user())
                    @if (Auth::user()->role === 'super-admin')
                        <div class="fixed inset-y-0 left-0 w-64  shadow-md z-50 overflow-y-auto">
                            @include('admin-components.super-admin-sidebar')
                        </div>
                    @elseif (Auth::user()->role === 'admin')
                        <div class="fixed inset-y-0 left-0 w-64 shadow-md z-50 overflow-y-auto">
                            @include('admin-components.admin-sidebar')
                        </div>
                    @endif
                @endif

                <div class="flex flex-col flex-1 h-full md:pl-64">
                    <!-- Navbar: Fixed at the top -->
                    <div class="fixed top-0 left-0 right-0 z-50 md:pl-64">
                        @livewire('components.nav-bar')
                    </div>

                    <!-- Main content: Scrolls independently -->
                    <main class="flex-1 h-full overflow-y-auto pt-16">
                        <div class="relative h-full w-full">
                            <!-- Background blobs -->
                            <div
                                class="absolute w-[400px] h-[400px] bg-pink-200 rounded-full opacity-30 blur-3xl top-[-50px] left-[-100px]">
                            </div>
                            <div
                                class="absolute w-[500px] h-[500px] bg-purple-200 rounded-full opacity-30 blur-3xl right-[50px]">
                            </div>
                            <div
                                class="absolute w-[300px] h-[300px] bg-orange-200 rounded-full opacity-30 blur-3xl top-[100px] right-[150px]">
                            </div>

                            <div class="relative z-10">
                                {{ $slot }}
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>
    @livewireScripts
    @livewireCalendarScripts
</body>

</html>
