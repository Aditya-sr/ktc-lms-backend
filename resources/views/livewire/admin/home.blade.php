<div class="max-w-7xl mx-auto p-6">
    <!-- Combined Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Home</h1>
            <p class="text-gray-500 mt-1">Quick access to school management tools</p>
        </div>
        <div class="w-full md:w-96">
            <div class="relative">
                <input type="text" wire:model.live="searchQuery"
                    placeholder="Search features (e.g. 'attendance', 'timetable')"
                    class="w-full p-3 pl-10 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent shadow-sm">
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results (Overlay Style) -->
    @if ($searchQuery && count($searchResults) > 0)
        <div class="relative z-10">
            <div
                class="absolute top-0 left-0 right-0 bg-white rounded-lg shadow-xl border border-gray-200 mt-1 max-w-2xl mx-auto">
                @foreach ($searchResults as $route => $label)
                    <div wire:click="selectResult('{{ $route }}')"
                        class="p-4 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors">
                        <div class="font-medium text-gray-800">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif($searchQuery && empty($searchResults))
        <div
            class="mt-2 p-4 bg-white rounded-lg shadow border border-gray-200 text-center text-gray-500 max-w-2xl mx-auto">
            No results found for "{{ $searchQuery }}"
        </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">
        <!-- Left Column - Recent Searches -->
        <div class="lg:col-span-2 space-y-6">
            @if (count($recentSearches) > 0)
                <div class="bg-white rounded-lg shadow border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Recent Searches</h3>
                        <button wire:click="clearRecentSearches" class="text-sm text-red-500 hover:text-red-700">
                            Clear All
                        </button>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach ($recentSearches as $search)
                            <div wire:click="$set('searchQuery', '{{ $search['term'] }}')"
                                class="p-4 hover:bg-gray-50 cursor-pointer flex justify-between items-center">
                                <div>
                                    <div class="font-medium text-gray-800">{{ $search['term'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $search['time']->diffForHumans() }}</div>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Quick Actions Card -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-lg">Quick Actions</h3>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 p-4">
                    <a href="{{ route('admin.attendance', ['organization' => auth()->user()->organization]) }}"
                        class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">Mark Attendance</span>
                    </a>
                    <a href="{{ route('admin.announcement', ['organization' => auth()->user()->organization]) }}"
                        class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">New Announcement</span>
                    </a>
                    <a href="{{ route('admin.fee', ['organization' => auth()->user()->organization]) }}"
                        class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-center">Fee Collection</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Upcoming Events -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-lg">Upcoming Events</h3>
                </div>
                <div class="p-4">
                    <div class="relative">
                        <!-- Timeline -->
                        <div class="border-l-2 border-gray-200 absolute h-full left-4 top-0"></div>

                        <!-- Event 1 -->
                        <div class="mb-6 ml-8 relative">
                            <div class="absolute w-3 h-3 bg-blue-500 rounded-full -left-5 top-1 border-2 border-white">
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">Quarterly Exams</p>
                                    <p class="text-sm text-gray-500">All classes</p>
                                </div>
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">25-29 Oct</span>
                            </div>
                        </div>

                        <!-- Event 2 -->
                        <div class="mb-6 ml-8 relative">
                            <div class="absolute w-3 h-3 bg-green-500 rounded-full -left-5 top-1 border-2 border-white">
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">PTM Meeting</p>
                                    <p class="text-sm text-gray-500">Class 10-A & 10-B</p>
                                </div>
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">30 Oct</span>
                            </div>
                        </div>

                        <!-- Event 3 -->
                        <div class="mb-6 ml-8 relative">
                            <div
                                class="absolute w-3 h-3 bg-purple-500 rounded-full -left-5 top-1 border-2 border-white">
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-medium">Annual Day</p>
                                    <p class="text-sm text-gray-500">School Auditorium</p>
                                </div>
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">15
                                    Nov</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.calender', ['organization' => auth()->user()->organization]) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Full Calendar</a>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow border border-gray-200">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-semibold text-lg">School Stats</h3>
                </div>
                <div class="p-4 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Students</span>
                        <span class="font-medium">1,245</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Teachers</span>
                        <span class="font-medium">48</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Today's Attendance</span>
                        <span class="font-medium text-green-600">92%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
