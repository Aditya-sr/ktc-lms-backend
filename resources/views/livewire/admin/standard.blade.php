<div>
    <div class="flex justify-end gap-4 p-4 items-center">
        <button class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg"
            wire:click="onStandard()">Add Class</button>
        <button class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg"
            wire:click="onSection()">Add Section</button>
        <button class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg"
            wire:click="onSubject()">Add Subject</button>
    </div>

    <!-- List section -->
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'standard' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('standard')">Class List</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'section' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('section')">Section List</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'subject' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('subject')">Subject List</button>
        </div>
    </div>
    <!-- List section end -->

    <!-- Standard Modal -->
    <x-modal-form show="{{ $openStandard }}" title="{{ $editId ? 'Edit Class' : 'Add Class' }}"
        submitAction="{{ 'saveStandard' }}" submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <x-input wire:model.defer="standardName" label="Class Name" />
            <x-input wire:model.defer="standardCode" label="Code" />
            <x-native-select label="Board" wire:model.defer="standardBoard">
                <option value="">Select Board</option>
                @foreach (App\Helpers\Constants::BOARD as $board)
                    <option value="{{ $board }}">{{ $board }}</option>
                @endforeach
            </x-native-select>
            {{-- <x-input wire:model.defer="standardOrder" label="Order" type="number" /> --}}
        </div>
        <div class="justify-items-end py-4">
            <x-toggle label="Active" wire:model.defer="standardActive" />
        </div>
    </x-modal-form>

    <!-- Section Modal -->
    <x-modal-form show="{{ $openSection }}" title="{{ $editId ? 'Edit Section' : 'Add Section' }}"
        submitAction="{{ 'saveSection' }}" submitButton="{{ $editId ? 'Update' : 'Create' }}"
        closeAction="closeModal">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <x-input wire:model.defer="sectionName" label="Section Name" />
            <x-native-select label="Class" wire:model.defer="selectedStandard">
                <option value="">Select Class</option>
                @foreach ($standards as $standard)
                    <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                @endforeach
            </x-native-select>
            {{-- <x-textarea wire:model.defer="sectionDescription" label="Description" class="sm:col-span-2" /> --}}
        </div>
        <div class="justify-items-end py-4">
            <x-toggle label="Active" wire:model.defer="sectionActive" />
        </div>
    </x-modal-form>

    <!-- Subject Modal -->
    <x-modal-form show="{{ $openSubject }}" title="{{ $editId ? 'Edit Subject' : 'Add Subject' }}"
        submitAction="{{ 'saveSubject' }}" submitButton="{{ $editId ? 'Update' : 'Create' }}"
        closeAction="closeModal">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <x-input wire:model.defer="subjectName" label="Subject Name" />
            <x-input wire:model.defer="subjectCode" label="Code" />

            <x-native-select label="Standard" wire:model.live="selectedStandardForSubject">
                <option value="">Select Standard</option>
                @foreach ($standards as $standard)
                    <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                @endforeach
            </x-native-select>
            <x-select label="Sections" wire:model="selectedSectionsForSubject" :options="$sections
                ->map(function ($section) {
                    return [
                        'value' => $section->id,
                        'label' => $section->name,
                    ];
                })
                ->toArray()" option-value="value"
                option-label="label" multiselect
                placeholder="{{ $selectedStandardForSubject ? 'Select sections' : 'Please select a standard first' }}"
                :disabled="!$selectedStandardForSubject" />
            {{-- <div class="sm:col-span-2">
                <x-textarea wire:model.defer="subjectDescription" label="Description" />
            </div> --}}
            <x-toggle label="Mandatory" wire:model.defer="isMandatory" />
        </div>
        <div class="justify-items-end py-4">
            <x-toggle label="Active" wire:model.defer="subjectActive" />
        </div>
    </x-modal-form>

    {{-- sub standard secttion --}}

    <x-view-modal 
    :show="$showViewModal" 
    :title="$viewModalTitle" 
    closeAction="closeViewModal">

    @if ($activeTab === 'standard')
        <p><strong>Name:</strong> {{ $viewData['name'] ?? '—' }}</p>
        <p><strong>Code:</strong> {{ $viewData['code'] ?? '—' }}</p>
        <p><strong>Board:</strong> {{ $viewData['board'] ?? '—' }}</p>
        <p><strong>Order:</strong> {{ $viewData['order'] ?? '—' }}</p>
        <p><strong>Status:</strong> {{ ($viewData['is_active'] ?? false) ? 'Active' : 'Inactive' }}</p>
    @elseif ($activeTab === 'section')
        <p><strong>Standard:</strong> {{ $viewData['standard']['name'] ?? '—' }}</p>
        <p><strong>Name:</strong> {{ $viewData['name'] ?? '—' }}</p>
        <p><strong>Description:</strong> {{ $viewData['description'] ?? '—' }}</p>
        <p><strong>Status:</strong> {{ ($viewData['is_active'] ?? false) ? 'Active' : 'Inactive' }}</p>
    @elseif ($activeTab === 'subject')
        <p><strong>Name:</strong> {{ $viewData['name'] ?? '—' }}</p>
        <p><strong>Code:</strong> {{ $viewData['code'] ?? '—' }}</p>
        <p><strong>Description:</strong> {{ $viewData['description'] ?? '—' }}</p>
        <p><strong>Mandatory:</strong> {{ ($viewData['is_mandatory'] ?? false) ? 'Yes' : 'No' }}</p>
        <p><strong>Status:</strong> {{ ($viewData['is_active'] ?? false) ? 'Active' : 'Inactive' }}</p>
        <p><strong>Standards:</strong> {{ implode(', ', array_column($viewData['standards'] ?? [], 'name')) }}</p>
    @endif

    </x-view-modal>


    {{--  Tables --}}
    <div class="p-4">
        @if ($activeTab === 'standard')
            @livewire('admin.standard-table')
        @elseif($activeTab === 'section')
            @livewire('admin.section-table')
        @elseif($activeTab === 'subject')
            @livewire('admin.subject-table')
        @endif
    </div>
</div>
