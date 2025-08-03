<div>
    <!-- Header Tabs -->
    <div class="flex justify-end rounded-full items-center p-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'profile' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('profile')">School Profile</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'info' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('info')">School Info</button>
        </div>
    </div>

    @if ($activeTab === 'profile')
        <!-- School Profile Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-md">
            <!-- Profile Photo Section -->
            <div class="md:col-span-1">
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="text-center">
                        @if ($organization->logo)
                            <img src="{{ $organization->logo }}"
                                class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-md mb-4">
                        @else
                            <div
                                class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center text-gray-500 mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif

                        <div x-data="{ isUploading: false }" x-on:livewire-upload-start="isUploading = true"
                            x-on:livewire-upload-finish="isUploading = false"
                            x-on:livewire-upload-error="isUploading = false">

                            @if ($tempPhotoUrl)
                                <img src="{{ $tempPhotoUrl }}"
                                    class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-white shadow-md mb-4">
                            @endif

                            <label class="cursor-pointer">
                                <span
                                    class="block px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition duration-300 text-sm font-medium">
                                    @if ($photo)
                                        Change Photo
                                    @else
                                        Upload Photo
                                    @endif
                                </span>
                                <input type="file" class="hidden" wire:model="photo">
                            </label>

                            <div class="mt-2 text-xs text-gray-500">
                                JPG, PNG or GIF (Max 2MB)
                            </div>

                            @error('photo')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror

                            @if ($photo && !$tempPhotoUrl)
                                <div wire:loading wire:target="photo" class="mt-2 text-sm text-gray-500">
                                    Uploading...
                                </div>
                            @endif

                            @if ($photo)
                                <button wire:click="savePhoto"
                                    class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300 text-sm font-medium w-full">
                                    Save Photo
                                </button>
                            @endif

                            @if (session()->has('photo_message'))
                                <div class="mt-2 p-2 text-sm text-green-700 bg-green-100 rounded">
                                    {{ session('photo_message') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Organization Details and Password Section -->
            <div class="md:col-span-2 space-y-4">
                <!-- Organization Details -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">School Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">School Name</label>
                            <p class="mt-1 text-gray-800">{{ $organization->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1 text-gray-800">{{ $organization->email ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Mobile Number</label>
                            <p class="mt-1 text-gray-800">{{ $organization->mobile_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">State</label>
                            <p class="mt-1 text-gray-800">{{ $organization->state ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Education Board</label>
                            <p class="mt-1 text-gray-800">{{ $organization->education_board ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">School Code</label>
                            <p class="mt-1 text-gray-800">{{ $organization->school_code ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Serial number</label>
                            <p class="mt-1 text-gray-800">{{ $organization->serial_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Address</label>
                            <p class="mt-1 text-gray-800">{{ $organization->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Password Change Section -->
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1">Current Password</label>
                            <div class="relative">
                                <input wire:model="currentPassword" type="{{ $showPassword ? 'text' : 'password' }}"
                                    placeholder="Enter current password"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-10">
                                <button wire:click="togglePasswordVisibility"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($showPassword)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        @endif
                                    </svg>
                                </button>
                            </div>
                            @error('currentPassword')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1">New Password</label>
                            <div class="relative">
                                <input wire:model="newPassword" type="{{ $showPassword ? 'text' : 'password' }}"
                                    placeholder="Enter new password"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-10">
                            </div>
                            @error('newPassword')
                                <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-medium mb-1">Confirm Password</label>
                            <div class="relative">
                                <input wire:model="confirmPassword" type="{{ $showPassword ? 'text' : 'password' }}"
                                    placeholder="Confirm new password"
                                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-10">
                            </div>
                        </div>

                        <button wire:click="updatePassword"
                            class="w-full py-3 bg-gradient-3 text-white rounded-lg hover:bg-gradient-3-hover transition duration-300 shadow-md hover:shadow-lg">
                            Update Password
                        </button>

                        @if (session()->has('password_message'))
                            <div class="p-2 text-sm text-green-700 bg-green-100 rounded-lg">
                                {{ session('password_message') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($activeTab === 'info')
        <!-- School Info Content -->
        <div class="max-w-4xl mx-auto p-6 border border-gray-200 rounded-lg shadow-md">
            <div class="space-y-6">
                <!-- About School -->
                <div class="p-1 rounded-lg">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter about School</h2>
                    <textarea wire:model="aboutSchool" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter school description..."></textarea>
                </div>

                <!-- Website Info -->
                <div class="p-1 rounded-lg ">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter School Website Info</h2>
                    <textarea wire:model="websiteInfo" rows="4"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter website information..."></textarea>
                </div>

                <!-- Website URL -->
                <div class="p-1 rounded-lg">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter School Website URL</h2>
                    <input type="url" wire:model="websiteUrl"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="https://example.com">
                </div>

                <!-- School Email -->
                <div class="p-1 rounded-lg">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter School Email</h2>
                    <input type="email" wire:model="schoolEmail"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="school@example.com">
                    @error('schoolEmail')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- School Mobile Number -->
                <div class="p-1 rounded-lg">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter School Mobile Number</h2>
                    <input type="text" wire:model="schoolMobileNo" inputmode="numeric" pattern="[0-9]*"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="9876543210">
                    @error('schoolMobileNo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- School Address -->
                <div class="p-1 rounded-lg">
                    <h2 class="text-md font-bold text-gray-800 mb-4">Enter School Address</h2>
                    <textarea wire:model="schoolAddress" rows="3"
                        class="w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="123 School St, City, Country"></textarea>
                    @error('schoolAddress')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- School Management -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h2 class="text-md font-bold text-gray-800 mb-4">School Management</h2>

                    @foreach ($schoolManagement as $index => $member)
                        <div
                            class="grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-white rounded-lg mb-4 border border-gray-200">
                            <!-- Photo Upload - First Column (3 cols) -->
                            <div class="md:col-span-3 flex items-center space-x-4">
                                <div class="relative">
                                    @if (isset($member['photo']))
                                        <img src="{{ $member['photo']->temporaryUrl() }}"
                                            class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                    @elseif(!empty($member['photo_path']))
                                        <img src="{{ $member['photo_path'] }}"
                                            class="h-16 w-16 rounded-full object-cover border-2 border-gray-200">
                                    @else
                                        <div
                                            class="h-16 w-16 rounded-full bg-gray-200 border-2 border-gray-300 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <label
                                        class="absolute -bottom-2 -right-2 bg-white p-1 rounded-full shadow-md cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <input type="file" class="hidden"
                                            wire:model="schoolManagement.{{ $index }}.photo">
                                    </label>
                                </div>

                                @if (!empty($member['photo_path']) || isset($member['photo']))
                                    <button wire:click="removeManagementPhoto({{ $index }})"
                                        class="text-red-600 hover:text-red-800 text-sm self-end">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>

                            <!-- Name - Second Column (4 cols) -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Enter Name</label>
                                <input type="text" wire:model="schoolManagement.{{ $index }}.name"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                @error("schoolManagement.$index.name")
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Designation - Third Column (4 cols) -->
                            <div class="md:col-span-4">
                                <label class="block text-sm font-medium text-gray-500 mb-1">Enter Designation</label>
                                <input type="text" wire:model="schoolManagement.{{ $index }}.designation"
                                    class="w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                @error("schoolManagement.$index.designation")
                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Remove Button - Fourth Column (1 col) -->
                            <div class="md:col-span-1 flex items-center justify-end">
                                <button wire:click="removeManagement({{ $index }})"
                                    class="text-red-600 hover:text-red-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach

                    <button wire:click="addManagement"
                        class="flex items-center justify-center w-full py-2 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 mt-4 text-gray-600 hover:text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Management Member
                    </button>
                </div>

                <!-- School Documents -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h2 class="text-md font-bold text-gray-800 mb-4">School Documents</h2>

                    <!-- Documents Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white rounded-lg overflow-hidden">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Type</th>
                                    <th
                                        class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <!-- Existing Documents -->
                                @foreach ($uploadedDocuments as $document)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">{{ $document['title'] }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ strtoupper($document['file_type']) }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ $document['file_path'] }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                            <button wire:click="removeDocument({{ $document['id'] }})"
                                                class="text-red-600 hover:text-red-900">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach

                                <!-- Newly Uploaded (Not Saved Yet) Documents -->
                                @foreach ($documentFiles as $index => $file)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <input type="text" wire:model="documentTitles.{{ $index }}"
                                                class="border rounded px-2 py-1 w-full" placeholder="Document title">
                                            @error('documentTitles.' . $index)
                                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                            {{ strtoupper($file->getClientOriginalExtension()) }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                            <button wire:click="removeUploadedFile({{ $index }})"
                                                class="text-red-600 hover:text-red-900">Remove</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Document Button -->
                    <div class="mt-4">
                        <label
                            class="cursor-pointer inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Add Documents
                            <input type="file" class="hidden" wire:model="documentFiles" multiple>
                        </label>
                        <strong>*Note:</strong>
                        <span class="text-gray-400">
                            Please save the document you have selected first, then upload another document.
                        </span>
                        @error('documentFiles.*')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end pt-4">
                    <button wire:click="saveSchoolInfo" class="px-6 py-2 bg-gray-200 shadow-md">
                        Save All Information
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
