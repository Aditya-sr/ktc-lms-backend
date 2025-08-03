<div class="container mx-auto p-4">
    <livewire:admin.time-table-calendar :initial-year="2025" :initial-month="6" :key="'timetable-' . now()->format('Y-m')" />
</div>
