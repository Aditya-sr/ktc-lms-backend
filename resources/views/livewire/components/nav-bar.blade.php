<div>
    <nav class="bg-pink-200 border-b border-gray-200 px-4 sm:px-6 lg:px-8  ">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button x-on:click="offcanvas = true" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Back button and breadcrumb -->
                <div class="flex items-center ml-4 md:ml-0">
                    <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-900 mr-4">
                        <svg class="h-5 w-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        <span class="text-sm font-medium">Back</span>
                    </button>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <x-button rounded class="h-10 w-10" icon="bell-alert" outline wire:click="" />
                <x-button rounded class="h-10 w-10" icon="user" outline wire:click="profilePage" />
                <x-button rounded class="h-10 w-10" icon="arrow-right-on-rectangle" outline wire:click="confirmLogout" />
            </div>
        </div>
    </nav>

    @if ($showSuperAdminLogoutModal)
        <div
            class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-[9999] p-4 overflow-y-auto">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full relative"
                style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500">
                            <path d="M9 11l-6 6m0 0l6 6m-6-6h12m6-6l-6 6m0 0l6 6m-6-6H3"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Super Admin Logout</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to logout from Super Admin?</p>

                    <div class="flex justify-center space-x-4">
                        <button wire:click="superAdminLogout"
                            class="px-4 py-2 bg-white text-purple-600 rounded border border-purple-600 hover:bg-purple-50 transition">
                            Yes
                        </button>
                        <button wire:click="$set('showSuperAdminLogoutModal', false)"
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Admin Logout Modal -->
    @if ($showAdminLogoutModal)
        <div
            class="fixed inset-0 flex items-center justify-center bg-white/10 backdrop-blur-sm z-[9999] p-4 overflow-y-auto">
            <div class="bg-white rounded-lg p-6 max-w-sm w-full relative"
                style="box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                <div class="text-center">
                    <div class="flex justify-center mb-4">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-teal-500">
                            <path d="M9 11l-6 6m0 0l6 6m-6-6h12m6-6l-6 6m0 0l6 6m-6-6H3"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Admin Logout</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to logout from Admin Panel?</p>

                    <div class="flex justify-center space-x-4">
                        <button wire:click="adminLogout"
                            class="px-4 py-2 bg-white text-purple-600 rounded border border-purple-600 hover:bg-purple-50 transition">
                            Yes
                        </button>
                        <button wire:click="$set('showAdminLogoutModal', false)"
                            class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition">
                            No
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
