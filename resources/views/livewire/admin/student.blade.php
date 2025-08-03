<div>
    <div class="flex justify-end items-center p-4">
        <button wire:click="onAddStudent()"
            class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Add Student
        </button>
    </div>

    <x-modal-form show="{{ $open }}" title="{{ $editId ? 'Edit Student' : 'Add Student' }}"
        submitAction="{{ 'onSave' }}" submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-4">
            <!-- Image Uploads -->
            <div class="sm:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Student Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student Profile Image</label>
                        @if ($editId && !$studentImage)
                            @php $user = \App\Models\User::find($editId) @endphp
                            @if ($user->image)
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $user->image }}" class="h-16 w-16 rounded-full object-cover">
                                    <button wire:click="$set('studentImage', null)" type="button"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        Remove
                                    </button>
                                </div>
                            @endif
                        @endif
                        <input type="file" wire:model="studentImage"
                            class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                        @error('studentImage')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <x-input wire:model.defer="studentsName" label="Full Name" required />
            <x-input wire:model.defer="studentsEmail" label="Email" required />
            <x-input wire:model.defer="fatherName" label="Father's Name" required />
            <x-input wire:model.defer="motherName" label="Mother's Name" required />
            <x-datetime-picker label="Date Of Birth" without-time required wire:model.defer="dob" />
            <x-datetime-picker label="Date Of Admission" without-time required wire:model.defer="dateOfAdmission" />
            <x-input wire:model.defer="studentsMobile" label="Mobile Number" required />
            <x-input wire:model.defer="aadharNo" label="Aadhar Number" />

            <!-- Academic Information -->
            <x-native-select label="Gender" wire:model.defer="studentsGender" required>
                <option value="">Select Gender</option>
                @foreach (App\Helpers\Constants::GENDER as $gender)
                    <option value="{{ $gender }}">{{ $gender }}</option>
                @endforeach
            </x-native-select>
            <x-native-select label="Board" wire:model.defer="studentsBoard" required>
                <option value="">Select Board</option>
                @foreach (App\Helpers\Constants::BOARD as $board)
                    <option value="{{ $board }}">{{ $board }}</option>
                @endforeach
            </x-native-select>

            <x-native-select label="Class" wire:model.live="studentsClass">
                <option value="">Select Class</option>
                @foreach ($standards as $standard)
                    <option value="{{ $standard->id }}">{{ $standard->name }}</option>
                @endforeach
            </x-native-select>

            <x-native-select label="Section" wire:model.defer="studentsSection">
                <option value="">Select Section</option>
                @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </x-native-select>

            <!-- Address Information -->
            <x-native-select label="State" wire:model.live="selectedState">
                <option value="">Select State</option>
                @foreach ($states as $state)
                    <option value="{{ $state }}">{{ $state }}</option>
                @endforeach
            </x-native-select>
            <x-native-select label="City" wire:model.live="selectedCity">
                <option value="">Select City</option>
                @foreach ($cities as $city)
                    <option value="{{ $city['name'] }}">{{ $city['name'] }}</option>
                @endforeach
            </x-native-select>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <x-input wire:model.defer="pincode" label="Pincode" />
            <x-input wire:model.defer="religion" label="Religion" />
            <x-textarea wire:model.defer="localAddress" label="Local Address" />
            <x-textarea wire:model.defer="permanentAddress" label="Permanent Address" />
        </div>
        <div class="justify-items-end py-4">
            <x-toggle label="Active" wire:model.defer="studentsActive" />
        </div>
    </x-modal-form>

    <!-- View Modal -->
    <x-view-modal :show="$showViewModal" :title="$viewModalTitle" closeAction="closeViewModal" :image="$studentImageUrl ?? null">
        <div class="space-y-6 text-left">
            <!-- Student Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Full Name</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['user']->name ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Contact Number</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['user']->mobile_number ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Email Address</h3>
                    <p class="text-base font-semibold text-gray-800 break-all">{{ $viewData['user']->email ?? '—' }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Admission No</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['detail']->admission_no ?? '—' }}</p>
                </div>
            </div>

            <!-- Academic Info -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Class</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['detail']->standard->name ?? '—' }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Section</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['detail']->section->name ?? '—' }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Roll No</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['detail']->roll_no ?? '—' }}</p>
                </div>
            </div>

            <!-- Address Info -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">Address Details</h3>
                <div class="space-y-2">
                    <p class="text-base text-gray-800">
                        {{ $viewData['detail']->address ?? '—' }}
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <h4 class="text-xs font-medium text-gray-500">City</h4>
                            <p class="text-sm text-gray-700">{{ $viewData['detail']->city ?? '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-medium text-gray-500">State</h4>
                            <p class="text-sm text-gray-700">{{ $viewData['detail']->state ?? '—' }}</p>
                        </div>
                        <div>
                            <h4 class="text-xs font-medium text-gray-500">Pincode</h4>
                            <p class="text-sm text-gray-700">{{ $viewData['detail']->pincode ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info (if needed) -->
            <!-- <div class="bg-gray-50 p-4 rounded-lg">
            <h3 class="text-sm font-medium text-gray-500 mb-2">Additional Information</h3>
            <p class="text-base text-gray-800">Any other relevant details can go here</p>
        </div> -->
        </div>
    </x-view-modal>


    {{-- Scrollable table container --}}
    <div class="flex-1 overflow-x-auto px-4">
        @livewire('admin.student-table')
    </div>
</div>
