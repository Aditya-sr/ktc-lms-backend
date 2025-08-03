<!-- resources/views/livewire/admin/arrangement.blade.php -->

<div class="p-4">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Teacher Arrangement System</h2>

        <!-- Date Selection -->
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Select Date</label>
            <input type="date" wire:model="date" class="border rounded-lg px-4 py-2 w-full md:w-64">
        </div>

        <!-- Absent Teachers List -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-3">Absent Teachers ({{ $date }})</h3>

            @if ($absentTeachers->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($absentTeachers as $teacher)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                            wire:click="selectedTeacherId = {{ $teacher->id }}">
                            <div class="font-medium">{{ $teacher->name }}</div>
                            <div class="text-sm text-gray-600">{{ $teacher->teacherDetail->employee_id ?? 'N/A' }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-gray-500 py-4">No teachers marked absent for this date</div>
            @endif
        </div>

        <!-- Arrangement Form -->
        <div class="border-t pt-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Create Substitute Arrangement</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teacher Selection -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Original Teacher</label>
                    <select wire:model="selectedTeacherId" class="w-full border rounded-lg px-4 py-2">
                        <option value="">Select Teacher</option>
                        @foreach ($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedTeacherId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Timetable Selection -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Class to Cover</label>
                    <select wire:model="selectedTimetableId" class="w-full border rounded-lg px-4 py-2"
                        {{ !$selectedTeacherId ? 'disabled' : '' }}>
                        <option value="">Select Class</option>
                        @foreach ($teacherTimetables as $timetable)
                            <option value="{{ $timetable->id }}">
                                {{ $timetable->standard->name }} - {{ $timetable->section->name }}
                                ({{ $daysOfWeek[$timetable->day_of_week] }}
                                {{ \Carbon\Carbon::parse($timetable->start_time)->format('h:i A') }})
                            </option>
                        @endforeach
                    </select>
                    @error('selectedTimetableId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Substitute Selection -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Substitute Teacher</label>
                    <select wire:model="substituteTeacherId" class="w-full border rounded-lg px-4 py-2"
                        {{ !$selectedTimetableId ? 'disabled' : '' }}>
                        <option value="">Select Substitute</option>
                        @foreach ($availableSubstitutes as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @error('substituteTeacherId')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Reason -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Reason for Absence</label>
                    <textarea wire:model="reason" class="w-full border rounded-lg px-4 py-2" rows="2" placeholder="Optional reason"></textarea>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end mt-6">
                <button wire:click="createArrangement"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Arrangement
                </button>
            </div>
        </div>

        <!-- Existing Arrangements -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-semibold mb-4">Existing Arrangements ({{ $date }})</h3>

            @if ($existingArrangements->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-3 px-4 border text-left">Original Teacher</th>
                                <th class="py-3 px-4 border text-left">Substitute</th>
                                <th class="py-3 px-4 border text-left">Class Details</th>
                                <th class="py-3 px-4 border text-left">Time</th>
                                <th class="py-3 px-4 border text-left">Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($existingArrangements as $arrangement)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 border">{{ $arrangement->originalTeacher->name }}</td>
                                    <td class="py-3 px-4 border">{{ $arrangement->substituteTeacher->name }}</td>
                                    <td class="py-3 px-4 border">
                                        {{ $arrangement->timetable->standard->name }} -
                                        {{ $arrangement->timetable->section->name }} -
                                        {{ $arrangement->timetable->subject->name }}
                                    </td>
                                    <td class="py-3 px-4 border">
                                        {{ \Carbon\Carbon::parse($arrangement->timetable->start_time)->format('h:i A') }}
                                        -
                                        {{ \Carbon\Carbon::parse($arrangement->timetable->end_time)->format('h:i A') }}
                                    </td>
                                    <td class="py-3 px-4 border">{{ $arrangement->reason ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-gray-500 py-4">No substitute arrangements for this date</div>
            @endif
        </div>
    </div>
</div>
