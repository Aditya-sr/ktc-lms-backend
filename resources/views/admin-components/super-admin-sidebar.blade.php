<!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
<div x-show="offcanvas" x-cloak class="fixed inset-0 flex z-40 md:hidden" role="dialog" aria-modal="true">
    <div x-show="offcanvas" x-on:click="offcanvas = false" class="fixed inset-0 bg-black bg-opacity-50"
        x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100" aria-hidden="true"></div>

    <div x-show="offcanvas" x-transition:enter="transition ease-in-out duration-300 transform"
        x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full" class="relative flex-1 flex flex-col max-w-xs w-full bg-white">

        <div x-show="offcanvas" x-transition:enter="ease-in-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="absolute top-0 right-0 -mr-12 pt-2">
            <button x-on:click="offcanvas = false" type="button"
                class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 h-0 overflow-y-auto bg-white border-r border-gray-200">
            <!-- Mobile Logo Section -->
            <div class="flex-shrink-0 flex flex-col items-center px-4 py-4 border-b border-gray-200">
                <img src="{{ asset('website-image/Group 11525.png') }}" alt="Logo"
                    class="w-20 h-20 object-contain mb-2">
                <h2 class="text-sm font-bold text-gray-900 text-center">EDYONE LMS</h2>
            </div>

            <!-- Mobile Navigation -->
            <nav class="px-4 py-4">
                <div class="mb-6">
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Dashboard</h3>
                    <div class="space-y-1">
                        @foreach (config('menu.' . App\Helpers\Constants::ROLEVALUE[auth()->user()->role]) as $menu_item)
                            @php
                                $is_active = Route::is($menu_item['prefix']);
                            @endphp
                            <a href="{{ route($menu_item['link']) }}"
                                class="{{ $is_active ? 'bg-purple-50 text-purple-700 border-r-2 border-purple-500' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} group flex items-center px-2 py-2 text-sm font-medium rounded-lg transition-colors duration-150">
                                <x-icon
                                    class="{{ $is_active ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }} mr-2 flex-shrink-0 h-4 w-4"
                                    name="{{ $menu_item['icon'] }}" />
                                {{ $menu_item['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <h3 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3">Account Settings</h3>
                    <div class="space-y-1">
                        <a href="#"
                            class="text-gray-600 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-lg transition-colors duration-150">
                            <svg class="text-gray-400 group-hover:text-gray-600 mr-2 flex-shrink-0 h-4 w-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Terms & Conditions
                        </a>
                        <a href="#"
                            class="text-gray-600 hover:text-gray-900 hover:bg-gray-50 group flex items-center px-2 py-2 text-sm font-medium rounded-lg transition-colors duration-150">
                            <svg class="text-gray-400 group-hover:text-gray-600 mr-2 flex-shrink-0 h-4 w-4"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                            Rating
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="flex-shrink-0 w-14">
        <!-- Force sidebar to shrink to fit close icon -->
    </div>
</div>

<!-- Static sidebar for desktop -->
<div class="hidden md:flex md:w-64 md:flex-col md:fixed md:inset-y-0">
    <!-- Sidebar component -->
    <div class="flex-1 flex flex-col min-h-0 bg-white border-r border-gray-200">
        <!-- Logo section -->
        <div class="flex-shrink-0 flex flex-col items-center px-4 py-4">
            <img src="{{ asset('website-image/Group 11525.png') }}" alt="Logo"
                class="w-20 h-20 object-contain mb-2">
            <h2 class="text-sm font-bold text-gray-900 text-center">EDYONE LMS</h2>
        </div>

        <!-- Scrollable navigation area -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <nav class="flex-1 px-4 py-4 space-y-6">
                <!-- Dashboard Section -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Dashboard</h3>
                    <div class="space-y-1">
                        @foreach (array_slice(config('menu.' . App\Helpers\Constants::ROLEVALUE[auth()->user()->role]), 0, -2) as $menu_item)
                            @php
                                $is_active = Route::is($menu_item['prefix']);
                            @endphp
                            <a href="{{ route($menu_item['link']) }}"
                                class="{{ $is_active ? 'bg-purple-50 text-purple-700 border-r-2 border-purple-500' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} group flex items-center px-2 py-2 text-sm font-medium rounded-lg transition-colors duration-150">
                                <x-icon
                                    class="{{ $is_active ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }} mr-2 flex-shrink-0 h-4 w-4"
                                    name="{{ $menu_item['icon'] }}" />
                                {{ $menu_item['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Account Settings Section -->
                <div>
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-3">Account Settings</h3>
                    <div class="space-y-1">
                        @foreach (array_slice(config('menu.' . App\Helpers\Constants::ROLEVALUE[auth()->user()->role]), -2) as $menu_item)
                            @php
                                $is_active = Route::is($menu_item['prefix']);
                            @endphp
                            <a href="{{ route($menu_item['link']) }}"
                                class="{{ $is_active ? 'bg-purple-50 text-purple-700 border-r-2 border-purple-500' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }} group flex items-center px-2 py-2 text-sm font-medium rounded-lg transition-colors duration-150">
                                <x-icon
                                    class="{{ $is_active ? 'text-purple-500' : 'text-gray-400 group-hover:text-gray-600' }} mr-2 flex-shrink-0 h-4 w-4"
                                    name="{{ $menu_item['icon'] }}" />
                                {{ $menu_item['title'] }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
