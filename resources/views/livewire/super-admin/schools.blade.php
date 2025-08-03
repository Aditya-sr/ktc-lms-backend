<div>
    <!-- Search Bar -->
    <div class="flex justify-end items-center p-4">
        <button wire:click="openModal" class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Add School
        </button>
    </div>

    <!-- Table with Horizontal Scroll -->
    <div class="p-4 overflow-x-auto">
        <div class="min-w-full inline-block align-middle">
            <livewire:super-admin.school-table />
        </div>
    </div>

    <!-- Modal for Add/Edit School -->
    <x-modal-form show="{{ $showModal }}" title="{{ $editId ? 'Edit School' : 'Add School' }}"
        submitAction="{{ 'saveSchool' }}" submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <x-input wire:model.defer="schoolName" label="School Name" placeholder="Enter school name" />
            <x-input wire:model.defer="email" label="Email" placeholder="Enter email" />
            <x-input wire:model.defer="mobileNumber" label="Mobile Number" placeholder="Enter mobile number" />
            <x-input wire:model.defer="state" label="State" placeholder="Enter state" />
            <x-input wire:model.defer="educationBoard" label="Education Board" placeholder="Enter education board" />
            <x-input wire:model.defer="schoolCode" label="School Code" placeholder="Enter school code" />
            <x-input wire:model.defer="serialNumber" label="Serial Number" placeholder="Enter serial number" />
        </div>
    </x-modal-form>
</div>
