<div>
    <div class="flex justify-end items-center p-4">
        <button wire:click="onAddTeacher()"
            class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Add Teacher
        </button>
    </div>

    <x-modal-form show="{{ $open }}" title="{{ $editId ? 'Edit Teacher' : 'Add Teacher' }}" submitAction="onSave"
        submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
              <!-- Image Uploads -->
            <div class="sm:col-span-3">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teacher Profile Image</label>
                        @if ($editId && !$teacherImage)
                            @php $user = \App\Models\User::find($editId) @endphp
                            @if ($user->image)
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $user->image }}" class="h-16 w-16 rounded-full object-cover">
                                    <button wire:click="$set('teacherImage', null)" type="button"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        Remove
                                    </button>
                                </div>
                            @endif
                        @endif
                        <input type="file" wire:model="teacherImage"
                            class="block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-md file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-50 file:text-blue-700
                        hover:file:bg-blue-100">
                        @error('teacherImage')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <!-- Basic Information -->
            <x-input wire:model.defer="teacherName" label="Full Name" required />
            <x-input wire:model.defer="teacherEmail" label="Email" required />
            <x-input wire:model.defer="employeeId" label="Employee ID" required />
            <x-input wire:model.defer="teacherMobile" label="Mobile Number" required />
            <x-datetime-picker label="Date of Birth" without-time wire:model.defer="dob" required />
            <x-datetime-picker label="Date of Joining" without-time wire:model.defer="dateOfJoining" required />
            <x-input wire:model.defer="qualification" label="Qualification" required />
            <x-input wire:model.defer="emergencyContact" label="Emergency Contact" required />

            <x-native-select label="Gender" wire:model.defer="teacherGender" required>
                @foreach (App\Helpers\Constants::GENDER as $gender)
                    <option value="{{ $gender }}">{{ $gender }}</option>
                @endforeach
            </x-native-select>

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

            <x-input wire:model.defer="pincode" label="Pincode" required />
        </div>

        <div class="mb-4">
            <x-textarea wire:model.defer="address" label="Address" required />
        </div>

        <div class="justify-items-end py-4">
            <x-toggle label="Active" wire:model.defer="teacherActive" />
        </div>
    </x-modal-form>

    <x-view-modal 
    :show="$showViewModal" 
    :title="$viewModalTitle" 
    closeAction="closeViewModal">

    @if ($activeTab === 'teacher')
    <div class="flex justify-center mb-4">
        @if (!empty($teacherImageUrl))
            <img src="{{ $teacherImageUrl }}" class="h-24 w-24 rounded-full object-cover shadow" alt="Teacher Image">
            @else
                <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                    N/A
                </div>
            @endif
    </div>

    <p><strong>Name:</strong> {{ $viewData['user']->name ?? '—' }}</p>
    <p><strong>Email:</strong> {{ $viewData['user']->email ?? '—' }}</p>
    <p><strong>Mobile:</strong> {{ $viewData['user']->mobile_number ?? '—' }}</p>
    <p><strong>Gender:</strong> {{ $viewData['user']->gender ?? '—' }}</p>
    <p><strong>Employee ID:</strong> {{ $viewData['detail']->employee_id ?? '—' }}</p>
    <p><strong>Date of Birth:</strong> {{ $viewData['user']->dob ?? '—' }}</p>
    <p><strong>Date of Joining:</strong> {{ $viewData['detail']->date_of_joining ?? '—' }}</p>
    <p><strong>Qualification:</strong> {{ $viewData['detail']->qualification ?? '—' }}</p>
    <p><strong>Emergency Contact:</strong> {{ $viewData['detail']->emergency_contact ?? '—' }}</p>
    <p><strong>State:</strong> {{ $viewData['detail']->state ?? '—' }}</p>
    <p><strong>City:</strong> {{ $viewData['detail']->city ?? '—' }}</p>
    <p><strong>Pincode:</strong> {{ $viewData['detail']->pincode ?? '—' }}</p>
    <p><strong>Address:</strong> {{ $viewData['detail']->address ?? '—' }}</p>
@endif


   </x-view-modal>


    <div class="flex-1 overflow-x-auto px-4">
        @livewire('admin.teacher-table')
    </div>

</div>
