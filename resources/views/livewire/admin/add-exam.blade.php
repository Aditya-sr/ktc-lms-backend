<div>
    <div class="flex justify-end items-center p-4">
        <button wire:click="onAddExam" class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Add Exam
        </button>
    </div>

    <x-modal-form show="{{ $open }}" title="{{ $editId ? 'Edit Exam' : 'Add Exam' }}" submitAction="onSave"
        submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="resetForm">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <x-input wire:model.defer="examName" label="Exam Name" required />

            <x-native-select wire:model.defer="examType" label="Exam Type" required>
                <option value="">Select Exam Type</option>
                @foreach ($examTypes as $key => $type)
                    <option value="{{ $key }}">{{ $type }}</option>
                @endforeach
            </x-native-select>

            <x-native-select wire:model="academicYear" label="Academic Year" required >
                @foreach ($academicYearOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </x-native-select>
            <x-datetime-picker without-time label="Start Date" wire:model.defer="startDate" required />

            <x-datetime-picker without-time label="End Date" wire:model.defer="endDate" required />

            <x-input type="number" wire:model.defer="totalMarks" label="Total Marks" required />

            <x-input type="number" wire:model.defer="passingMarks" label="Passing Marks" required />

            <div class="sm:col-span-2">
                <x-toggle wire:model.defer="isPublished" label="Publish Exam" />
            </div>
        </div>
    </x-modal-form>

    <!-- View Modal -->
    <x-view-modal :show="$showViewModal" :title="$viewModalTitle" closeAction="closeViewModal">
        <div class="space-y-4">
            @foreach ($viewData['details'] ?? [] as $label => $value)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">{{ $label }}</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    </x-view-modal>

    <!-- Exam Table -->
    <div class="flex-1 overflow-x-auto px-4">
        @livewire('admin.exam-table')
    </div>
</div>
