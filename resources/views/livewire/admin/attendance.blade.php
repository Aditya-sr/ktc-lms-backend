<div class="p-4">
    {{-- Main Tabs --}}
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            @foreach (array_keys($tabs) as $tabKey)
                <button
                    class="px-6 py-2 rounded-md transition-all {{ $activeTab === $tabKey ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                    wire:click="showTab('{{ $tabKey }}')">
                    {{ ucwords(str_replace('_', ' ', $tabKey)) }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Sub Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            @foreach ($tabs[$activeTab] as $subTabKey => $subTabLabel)
                <button wire:click="setSubTab('{{ $subTabKey }}')"
                    class="relative whitespace-nowrap py-4 px-1 font-medium text-sm {{ $subTab === $subTabKey ? 'text-gradient-3' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $subTabLabel }}
                    @if ($subTab === $subTabKey)
                        <div
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-[#ff00cc] via-[#cc00ff] to-[#3366ff]">
                        </div>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Content Area --}}

    <div>
        @if ($activeTab === 'teacher_attendance')
            @if ($subTab === 'teacher_attendance')
                {{-- @livewire('admin.fee.fee-type-table') --}}
            @elseif($subTab === 'teacher_attendance_list')
                {{-- @livewire('admin.fee.fee-cycle-table') --}}
            @elseif($subTab === 'techer_attendance_dashboard')
                {{-- @livewire('admin.fee.fee-concession-table') --}}
            @endif
        @elseif($activeTab === 'student_attendance')
            @if ($subTab === 'student_attendance_list')
                {{-- @livewire('admin.fee.fee-template-table') --}}
            @elseif($subTab === 'student_attendance_dashboard')
                {{-- @livewire('admin.fee.fee-template-item-table') --}}
            @endif
        @elseif($activeTab === 'assign_teacher_class')
            @if ($subTab === 'assign_teacher')
                <div class="flex justify-end gap-4 p-4 items-center">
                    <button class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg"
                        wire:click="openModalAssignTeacher()">Assign</button>
                </div>
                @livewire('admin.teacher.assign-teacher-table')
            @endif
        @endif
    </div>

    <!-- Modal Form Teacher Assign class -->
    <x-modal-form show="{{ $showModalAssignTeacher }}"
        title="{{ $editId ? 'Edit Teacher Assignment' : 'Assign Teacher to Class' }}" submitAction="saveAssignment"
        submitButton="{{ $editId ? 'Update' : 'Assign' }}" closeAction="closeModalAssignTeacher">
        <div class="grid grid-cols-1 gap-6">
            <!-- Teacher Select -->
            <x-native-select wire:model.defer="teacher_detail_id" label="Select Teacher" placeholder="Select a teacher"
                :options="$teachers
                    ->map(function ($teacher) {
                        return [
                            'value' => $teacher->id,
                            'label' => $teacher->user->name,
                        ];
                    })
                    ->toArray()" option-value="value" option-label="label" />

            <!-- Standard Select -->
            <x-native-select wire:model.live="standard_id" label="Select Standard/Class" placeholder="Select a standard"
                :options="$standards
                    ->map(function ($standard) {
                        return [
                            'value' => $standard->id,
                            'label' => $standard->name,
                        ];
                    })
                    ->toArray()" option-value="value" option-label="label" />

            <!-- Section Select -->
            <x-native-select wire:model.defer="section_id" label="Select Section"
                placeholder="{{ count($filteredSections) ? 'Select a section' : 'Please select standard first' }}"
                :options="$filteredSections" option-value="value" option-label="label" :disabled="!$standard_id" />
        </div>
    </x-modal-form>

</div>
