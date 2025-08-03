<div>
    <!-- Debug View State -->
    <div class="mb-2 text-sm text-gray-500">Current View: {{ $view }}</div>

    <!-- Yearly/Monthly Toggle and Navigation -->
    <div class="flex justify-between items-center mb-4">
        <div class="space-x-4">
            <button wire:click="switchToMonthlyView"
                class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                Monthly View
            </button>
            <button wire:click="switchToYearlyView"
                class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                Yearly View
            </button>
        </div>

        <div class="flex items-center space-x-4">
            @if ($view === 'month')
                <button wire:click="goToPreviousMonth"
                    class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                    ←
                </button>
                <h2 class="text-xl font-semibold">
                    {{ $startsAt->format('F Y') }}
                </h2>
                <button wire:click="goToNextMonth"
                    class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                    →
                </button>
            @else
                <button wire:click="goToPreviousYear"
                    class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                    ←
                </button>
                <h2 class="text-xl font-semibold">
                    {{ $startsAt->format('Y') }}
                </h2>
                <button wire:click="goToNextYear"
                    class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
                     →
                </button>
            @endif
        </div>
    </div>

    <!-- Monthly View -->
    @if ($view === 'month')
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 bg-gray-100 border-b">
                @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                    <div class="py-2 text-center font-medium text-gray-600">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Days -->
            <div class="grid grid-cols-7">
                @foreach ($monthGrid as $week)
                    @foreach ($week as $day)
                        <div class="min-h-24 p-2 border border-gray-100 {{ $day->month !== $startsAt->month ? 'bg-gray-50' : '' }}"
                            wire:click="onDayClick('{{ $day->year }}', '{{ $day->month }}', '{{ $day->day }}')">
                            <div class="text-right mb-1">
                                <span class="text-sm {{ $day->isToday() ? 'font-bold text-blue-600' : '' }}">
                                    {{ $day->day }}
                                </span>
                            </div>

                            <div class="space-y-1">
                                @foreach ($getEventsForDay($day) as $event)
                                    <div class="text-xs p-1 bg-blue-100 text-blue-800 rounded truncate cursor-pointer"
                                        wire:click.stop="onEventClick('{{ $event['id'] }}')"
                                        title="{{ $event['title'] }} - {{ $event['description'] }}">
                                        {{ $event['title'] }}
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    @endif

    <!-- Yearly View -->
    @if ($view === 'year')
        <div class="grid grid-cols-4 gap-4">
            @foreach ($yearlyCalendar as $month)
                <div class="bg-white rounded-lg shadow p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $month['name'] }}</h3>
                    <div class="grid grid-cols-7 gap-1 text-center text-sm">
                        @foreach (['S', 'M', 'T', 'W', 'T', 'F', 'S'] as $day)
                            <div class="font-medium text-gray-600">{{ $day }}</div>
                        @endforeach
                        @foreach ($month['days'] as $day)
                            <div class="py-1 {{ $day['isCurrentMonth'] ? 'bg-blue-50' : '' }} {{ $day['isToday'] ? 'font-bold text-blue-600' : '' }}"
                                wire:click="switchToMonthlyView('{{ $month['year'] }}', '{{ $month['month'] }}')">
                                {{ $day['day'] }}
                                @if (count($day['events']) > 0)
                                    <div class="text-xs text-blue-800">
                                        {{ count($day['events']) }} event{{ count($day['events']) > 1 ? 's' : '' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
