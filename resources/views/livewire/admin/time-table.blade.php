<!-- resources/views/livewire/admin/time-table.blade.php -->

<div class="p-4">
    <!-- Tab section -->
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'create' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('create')">Create Timetable</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'view' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('view')">View Timetable</button>
        </div>
    </div>
    <!-- Tab section end -->

    <div class="p-4">
        @if ($activeTab === 'create')
            <!-- Create Timetable Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Teacher Selection -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Teacher</label>
                        <select wire:model="teacherId" class="w-full border rounded-lg px-4 py-2">
                            <option value="">Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                            @endforeach
                        </select>
                        @error('teacherId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Standard Selection -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Standard</label>
                        <select wire:model="standardId" class="w-full border rounded-lg px-4 py-2">
                            <option value="">Select Standard</option>
                            @foreach($standards as $standard)
                                <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                            @endforeach
                        </select>
                        @error('standardId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Section Selection -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Section</label>
                        <select wire:model="sectionId" class="w-full border rounded-lg px-4 py-2" {{ !$standardId ? 'disabled' : '' }}>
                            <option value="">Select Section</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                        @error('sectionId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Subject Selection -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Select Subject</label>
                        <select wire:model="subjectId" class="w-full border rounded-lg px-4 py-2">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                        @error('subjectId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <!-- Day of Week -->
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Day of Week</label>
                        <select wire:model="dayOfWeek" class="w-full border rounded-lg px-4 py-2">
                            <option value="1">Monday</option>
                            <option value="2">Tuesday</option>
                            <option value="3">Wednesday</option>
                            <option value="4">Thursday</option>
                            <option value="5">Friday</option>
                            <option value="6">Saturday</option>
                            <option value="7">Sunday</option>
                        </select>
                    </div>

                    <!-- Time Selection -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Start Time</label>
                            <input type="time" wire:model="startTime" class="w-full border rounded-lg px-4 py-2">
                            @error('startTime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">End Time</label>
                            <input type="time" wire:model="endTime" class="w-full border rounded-lg px-4 py-2">
                            @error('endTime') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end mt-6">
                    <button wire:click="saveTimetable" class="px-4 py-2 bg-gradient-3 rounded text-white">
                        Save Timetable
                    </button>
                </div>
            </div>
        @endif

        @if ($activeTab === 'view')
            <!-- View Timetable -->
            <div class="bg-white rounded-lg shadow-md p-6">
                @foreach($daysOfWeek as $dayNumber => $dayName)
                    <h3 class="text-lg font-semibold mb-4 mt-6">{{ $dayName }}</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 border">Teacher</th>
                                    <th class="py-2 px-4 border">Standard</th>
                                    <th class="py-2 px-4 border">Section</th>
                                    <th class="py-2 px-4 border">Subject</th>
                                    <th class="py-2 px-4 border">Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($timetableEntries->get($dayNumber, []) as $entry)
                                    <tr>
                                        <td class="py-2 px-4 border">{{ $entry->teacher->user->name }}</td>
                                        <td class="py-2 px-4 border">{{ $entry->standard->name }}</td>
                                        <td class="py-2 px-4 border">{{ $entry->section->name }}</td>
                                        <td class="py-2 px-4 border">{{ $entry->subject->name }}</td>
                                        <td class="py-2 px-4 border">
                                            {{ \Carbon\Carbon::parse($entry->start_time)->format('h:i A') }} - 
                                            {{ \Carbon\Carbon::parse($entry->end_time)->format('h:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 px-4 border text-center text-gray-500">No classes scheduled</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>