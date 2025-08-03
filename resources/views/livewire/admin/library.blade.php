<div>
    <div class="flex justify-end items-center p-4">
        <button wire:click="onAddLibrary()"
            class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Add Library Documents
        </button>
    </div>

    <x-modal-form show="{{ $open }}" title="{{ $editId ? 'Edit Library Item' : 'Add Library Item' }}"
        submitAction="onSave" submitButton="{{ $editId ? 'Update' : 'Create' }}" closeAction="closeModal">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mb-4">
            <!-- Cover Image and File Upload -->
            <div class="sm:col-span-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Cover Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        @if ($editId && !$coverImage)
                         @php $library = \App\Models\Admin\Library::find($editId) @endphp
                            @if ($library->cover_image)
                                <div class="flex items-center gap-2 mb-2">
                                    <img src="{{ $library->cover_image }}"
                                        class="h-32 w-24 object-cover border rounded">
                                    <button wire:click="$set('coverImage', null)" type="button"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        Remove
                                    </button>
                                </div>
                            @endif
                        @endif

                        @if ($tempCoverUrl)
                            <img src="{{ $tempCoverUrl }}" class="h-32 w-24 object-cover border rounded mb-2">
                        @endif

                        <input type="file" wire:model="coverImage" accept="image/*"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                        @error('coverImage')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document File (PDF)</label>
                        @if ($editId && !$file)
                         @php $library = \App\Models\Admin\Library::find($editId) @endphp
                            @if ($library->file_path)
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-blue-600">{{ basename($library->file_path) }}</span>
                                    <button wire:click="$set('file', null)" type="button"
                                        class="text-red-600 hover:text-red-800 text-sm">
                                        Remove
                                    </button>
                                </div>
                            @endif
                        @endif

                        <input type="file" wire:model="file" accept=".pdf"
                            class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100">
                        @error('file')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <x-input wire:model.defer="title" label="Title" required />
            <x-input wire:model.defer="author" label="Author" />
            <x-input wire:model.defer="publisher" label="Publisher" />
            <x-input wire:model.defer="publication_year" label="Publication Year" type="number" />
            <x-input wire:model.defer="isbn" label="ISBN" />
            <x-input wire:model.defer="edition" label="Edition" />
            <x-input wire:model.defer="pages" label="Pages" type="number" />

            <!-- Dropdown Selections -->
            <x-native-select label="Category" wire:model.defer="category" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                @endforeach
            </x-native-select>

            <x-native-select label="Type" wire:model.defer="type" required>
                <option value="">Select Type</option>
                @foreach ($types as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-native-select>

            <x-native-select label="Language" wire:model.defer="language" required>
                <option value="">Select Language</option>
                @foreach ($languages as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-native-select>

            <x-native-select label="Availability" wire:model.defer="availability" required>
                <option value="">Select Availability</option>
                @foreach ($availabilities as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </x-native-select>

            <!-- Description -->
            <div class="sm:col-span-2">
                <x-textarea wire:model.defer="description" label="Description" rows="3" />
            </div>
        </div>
    </x-modal-form>
    

    <x-view-modal 
    :show="$showViewModal" 
    :title="$viewModalTitle" 
    closeAction="closeViewModal">

    @if ($activeTab === 'library')
        <div class="flex justify-center mb-4">
           @if (!empty($libraryImageUrl))
           <img src="{{ $libraryImageUrl }}" class="h-32 w-24 object-cover rounded shadow" alt="Cover Image">
            @else
                <div class="h-32 w-24 bg-gray-200 flex items-center justify-center text-gray-500 rounded">
                    No Image
                </div>
            @endif
        </div>

        <p><strong>Title:</strong> {{ $viewData['detail']->title ?? '—' }}</p>
        <p><strong>Author:</strong> {{ $viewData['detail']->author ?? '—' }}</p>
        <p><strong>Publisher:</strong> {{ $viewData['detail']->publisher ?? '—' }}</p>
        <p><strong>Publication Year:</strong> {{ $viewData['detail']->publication_year ?? '—' }}</p>
        <p><strong>Edition:</strong> {{ $viewData['detail']->edition ?? '—' }}</p>
        <p><strong>ISBN:</strong> {{ $viewData['detail']->isbn ?? '—' }}</p>
        <p><strong>Pages:</strong> {{ $viewData['detail']->pages ?? '—' }}</p>
        <p><strong>Category:</strong> {{ $viewData['detail']->category ?? '—' }}</p>
        <p><strong>Language:</strong> {{ $viewData['detail']->language ?? '—' }}</p>
        <p><strong>Type:</strong> {{ $viewData['detail']->type ?? '—' }}</p>
        <p><strong>Availability:</strong> {{ $viewData['detail']->availability ?? '—' }}</p>
        <p><strong>Description:</strong> {{ $viewData['detail']->description ?? '—' }}</p>

        @if (!empty($viewData['detail']->file_path))
            <p><strong>Document:</strong> 
                <a href="{{ $viewData['detail']->file_path }}" target="_blank" class="text-blue-600 underline">View PDF</a>
            </p>
        @endif
    @endif

</x-view-modal>



    {{-- Scrollable table container --}}
    <div class="flex-1 overflow-x-auto px-4">
        @livewire('admin.library-table')
    </div>

</div>
