<div class="p-4">
    <!-- List section -->
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'create' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('create')">Create Rule & Regulations</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'view' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('view')">View Rule & Regulations</button>
        </div>
    </div>
    <!-- List section end -->

    <div class="p-4">

        <!-- Rich Text Editor -->
        @if ($activeTab === 'create')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Rules & Regulations Content</label>
                <x-rich-text wire:model="content" :initial-value="$content" />
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button wire:click="saveContent" class="px-4 py-2 bg-gradient-3 rounded">
                    Save Rules
                </button>
            </div>
        @endif

        @if ($activeTab === 'view')
            <!-- Display existing content -->
            @if ($existingContent)
                <h3 class="text-lg font-semibold mb-4">Current Rules Preview</h3>
                <div class="mt-8 p-4 border rounded-lg">
                    <div class="prose max-w-none" wire:ignore>
                        {!! $existingContent->content['html'] !!}
                    </div>
                    <p class="text-sm text-gray-500 mt-2">
                        Last updated:
                        {{ \Carbon\Carbon::parse($existingContent->content['last_updated'])->format('d M Y, h:i A') }}
                    </p>
                </div>
            @endif
        @endif
    </div>
</div>
