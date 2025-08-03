<?php

namespace App\Livewire\Admin;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Omnia\LivewireCalendar\LivewireCalendar;

class TimeTableCalendar extends LivewireCalendar
{
    public string $view = 'month'; // Default view is monthly

    public function mount(
        $initialYear = null,
        $initialMonth = null,
        $weekStartsAt = null,
        $calendarView = null,
        $dayView = null,
        $eventView = null,
        $dayOfWeekView = null,
        $dragAndDropClasses = null,
        $beforeCalendarView = null,
        $afterCalendarView = null,
        $pollMillis = null,
        $pollAction = null,
        $dragAndDropEnabled = true,
        $dayClickEnabled = true,
        $eventClickEnabled = true,
        $extras = []
    ) {
        // Ensure monthly view is set by default
        $this->view = 'month';

        // Initialize with the specified or current year/month
        $initialYear = $initialYear ?? Carbon::today()->year;
        $initialMonth = $initialMonth ?? Carbon::today()->month;

        // Call parent mount with all required parameters
        parent::mount(
            $initialYear,
            $initialMonth,
            $weekStartsAt ?? Carbon::SUNDAY,
            $calendarView,
            $dayView,
            $eventView,
            $dayOfWeekView,
            $dragAndDropClasses,
            $beforeCalendarView,
            $afterCalendarView,
            $pollMillis,
            $pollAction,
            $dragAndDropEnabled,
            $dayClickEnabled,
            $eventClickEnabled,
            $extras
        );
    }

    public function events(): Collection
    {
        // Static timetable events for a year
        return collect([
            [
                'id' => 1,
                'title' => 'Math Class',
                'description' => 'Room 101 - 10th Grade',
                'date' => Carbon::create(2025, 1, 10),
            ],
            [
                'id' => 2,
                'title' => 'Science Lab',
                'description' => 'Lab 3 - Group A',
                'date' => Carbon::create(2025, 2, 15),
            ],
            [
                'id' => 3,
                'title' => 'English Literature',
                'description' => 'Room 205',
                'date' => Carbon::create(2025, 3, 20),
            ],
            [
                'id' => 4,
                'title' => 'PT Meeting',
                'description' => 'Staff Room',
                'date' => Carbon::create(2025, 4, 25),
            ],
            [
                'id' => 5,
                'title' => 'History Seminar',
                'description' => 'Room 301',
                'date' => Carbon::create(2025, 5, 12),
            ],
            [
                'id' => 6,
                'title' => 'Sports Day',
                'description' => 'School Ground',
                'date' => Carbon::create(2025, 6, 5),
            ],
            [
                'id' => 7,
                'title' => 'Art Workshop',
                'description' => 'Art Studio',
                'date' => Carbon::create(2025, 7, 18),
            ],
            [
                'id' => 8,
                'title' => 'Parent-Teacher Conference',
                'description' => 'Auditorium',
                'date' => Carbon::create(2025, 8, 22),
            ],
        ]);
    }

    public function getYearlyCalendarProperty(): array
    {
        $year = $this->startsAt->year;
        $calendar = [];

        for ($month = 1; $month <= 12; $month++) {
            $date = Carbon::create($year, $month, 1);
            $daysInMonth = $date->daysInMonth;
            $days = [];

            // Get the first day of the month
            $firstDayOfMonth = $date->dayOfWeek;

            // Add padding for days before the first day
            for ($i = 0; $i < $firstDayOfMonth; $i++) {
                $days[] = [
                    'day' => '',
                    'isCurrentMonth' => false,
                    'isToday' => false, // Add isToday for padding days
                    'events' => [],
                ];
            }

            // Add days of the month
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentDate = Carbon::create($year, $month, $day);
                $days[] = [
                    'day' => $day,
                    'isCurrentMonth' => true,
                    'isToday' => $currentDate->isToday(),
                    'events' => $this->events()->filter(function ($event) use ($currentDate) {
                        return Carbon::parse($event['date'])->isSameDay($currentDate);
                    })->values()->toArray(),
                ];
            }

            $calendar[] = [
                'name' => $date->format('F'),
                'month' => $month,
                'year' => $year,
                'days' => $days,
            ];
        }

        return $calendar;
    }

    public function switchToMonthlyView($year = null, $month = null)
    {
        $this->view = 'month';
        if ($year && $month) {
            $this->startsAt = Carbon::create($year, $month, 1);
            $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
            $this->calculateGridStartsEnds();
        }
    }

    public function switchToYearlyView()
    {
        $this->view = 'year';
    }

    public function goToPreviousYear()
    {
        $this->startsAt = $this->startsAt->subYear();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
        $this->calculateGridStartsEnds();
    }

    public function goToNextYear()
    {
        $this->startsAt = $this->startsAt->addYear();
        $this->endsAt = $this->startsAt->clone()->endOfMonth()->startOfDay();
        $this->calculateGridStartsEnds();
    }

    public function onEventClick($eventId)
    {
        $this->dispatch('showEventDetails', eventId: $eventId);
    }

    public function onDayClick($year, $month, $day)
    {
        $this->dispatch('showDaySchedule', date: "$year-$month-$day");
    }

    public function render()
    {
        return view('livewire.admin.time-table-calendar', [
            'monthGrid' => $this->monthGrid(),
            'events' => $this->events(),
            'getEventsForDay' => function ($day) {
                return $this->getEventsForDay($day, $this->events());
            },
            'yearlyCalendar' => $this->yearlyCalendar,
            'view' => $this->view,
            'startsAt' => $this->startsAt,
        ]);
    }
}