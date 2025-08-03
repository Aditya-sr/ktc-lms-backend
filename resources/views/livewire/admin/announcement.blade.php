<div>
    <!-- Add Button -->
    <div class="flex justify-between items-center mb-6 p-4">
        <h2 class="text-2xl font-bold text-gray-800">Announcements</h2>
        <button wire:click="openModal" class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            <i class="fas fa-plus mr-2"></i> Add Announcement
        </button>
    </div>

    <!-- Announcement List -->
    <div class="space-y-4 p-4">
        @forelse ($announcements as $announcement)
            <div class="p-4 bg-white rounded-lg shadow hover:shadow-md transition-shadow border border-gray-100">
                <div class="flex items-start gap-4">
                    <!-- Announcement Logo -->
                    <div class="flex-shrink-0">
                        <!-- Announcement SVG Icon -->
                        <x-icon name="megaphone" outline class="h-10 w-10" />
                    </div>

                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-lg text-gray-800 mb-1">
                                    {{ $announcement->announcement_name }}
                                </h3>
                                <p class="text-gray-600">
                                    {{ $announcement->announcement_content }}
                                </p>
                                <div class="mt-2 flex items-center text-sm text-gray-500">
                                    <span class="mr-3">
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $announcement->user->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $announcement->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-lg shadow">
                <i class="fas fa-bullhorn text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-500 text-lg">No announcements available</p>
                <button wire:click="openModal" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg">
                    Create your first announcement
                </button>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $announcements->links() }}
    </div>

    <!-- Modal Form -->
    <x-modal-form show="{{ $open }}" title="{{ $editId ? 'Edit Announcement' : 'Add Announcement' }}"
        submitAction="save" submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">
        <div class="grid grid-cols-1 gap-4">
            <x-input wire:model.defer="announcementName" label="Announcement Name" required />
            <x-textarea wire:model.defer="announcementContent" label="Announcement Content" required />
            <x-native-select wire:model.defer="type" label="Type" required>
                <option value="all">All</option>
                <option value="user">Student</option>
                <option value="teacher">Teacher</option>
            </x-native-select>

            <!-- Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Announcement Image</label>
                @if ($editId && !$announcementImage)
                    @php $announcement = \App\Models\Admin\Announcement::find($editId) @endphp
                    @if ($announcement->announcement_image)
                        <div class="mt-1 flex items-center">
                            <img src="{{ $announcement->announcement_image }}" class="h-20 w-20 object-cover rounded">
                            <button wire:click="deleteFile('image')" type="button"
                                class="ml-2 text-red-600 hover:text-red-800">
                                Remove
                            </button>
                        </div>
                    @endif
                @endif
                <input type="file" wire:model="announcementImage"
                    class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100">
                @error('announcementImage')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <!-- PDF Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Announcement PDF</label>
                @if ($editId && !$announcementPdf)
                    @php $announcement = \App\Models\Admin\Announcement::find($editId) @endphp
                    @if ($announcement->announcement_pdf)
                        <div class="mt-1 flex items-center">
                            <a href="{{ $announcement->announcement_pdf }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800">
                                View PDF
                            </a>
                            <button wire:click="deleteFile('pdf')" type="button"
                                class="ml-2 text-red-600 hover:text-red-800">
                                Remove
                            </button>
                        </div>
                    @endif
                @endif
                <input type="file" wire:model="announcementPdf" accept=".pdf"
                    class="mt-1 block w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-md file:border-0
                file:text-sm file:font-semibold
                file:bg-blue-50 file:text-blue-700
                hover:file:bg-blue-100">
                @error('announcementPdf')
                    <span class="text-red-500 text-xs">{{ $message }}</span>
                @enderror
            </div>
        </div>
    </x-modal-form>
</div>
