<div class="p-4">
    <!-- List section -->
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'list' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('list')">All Performers</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'view' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('view')">View Specific Performance</button>
        </div>
    </div>
    <!-- List section end -->

    <div class="p-4">
        @if ($activeTab === 'list')
             @livewire('admin.performance-table')
        @endif

        @if ($activeTab === 'view')
        @endif
    </div>
</div>
