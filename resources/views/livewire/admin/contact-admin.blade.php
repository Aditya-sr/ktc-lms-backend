<div>
    <div class="flex justify-end items-center p-4">
        <button wire:click="onAddContact()"
            class="px-4 py-2 bg-gradient-3 hover:bg-gradient-3-hover text-white rounded-lg">
            Contact Admin
        </button>
    </div>

    {{-- Scrollable table container --}}
    <div class="flex-1 overflow-x-auto px-4">
        @livewire('admin.super-admin-contact-table')
    </div>

    <!-- Add/Edit Modal -->
    <x-modal-form show="{{ $open }}" title="Contact Super Admin" submitAction="{{ 'onSave' }}"
        submitButton="Send Message" closeAction="closeModal">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <x-input wire:model.defer="topic" label="Topic" required />
            <x-textarea wire:model.defer="admin_query" label="Your Message" required rows="5" />
            <x-input type="file" wire:model="image" label="Attachment (Optional)" />
            @if ($image)
                <div class="mt-2">
                    <img src="{{ $image->temporaryUrl() }}" class="h-32 w-auto">
                </div>
            @endif
        </div>
    </x-modal-form>

    <!-- View Modal -->
    <x-view-modal :show="$showViewModal" :title="$viewModalTitle" closeAction="closeViewModal" :image="$viewData['contact']->image ?? null">
        <div class="space-y-6 text-left">
            <!-- Header Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Organization</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['organization']->name ?? '—' }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Sent By</h3>
                    <p class="text-base font-semibold text-gray-800">{{ $viewData['user']->name ?? '—' }}</p>
                </div>
            </div>

            <!-- Message Details -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Topic</h3>
                <p class="text-base font-semibold text-gray-800">{{ $viewData['contact']->topic ?? '—' }}</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-1">Message</h3>
                <p class="text-base text-gray-800 whitespace-pre-line">{{ $viewData['contact']->admin_query ?? '—' }}
                </p>
            </div>

            <!-- Attachment -->
            @if ($viewData['contact']->image ?? false)
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Attachment</h3>
                    <div class="flex justify-center">
                        <img src="{{ $viewData['contact']->image }}"
                            class="max-w-full h-auto rounded-lg shadow-sm border border-gray-200">
                    </div>
                </div>
            @endif

            <!-- Reply Section -->
            @if ($viewData['contact']->super_admin_reply ?? false)
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-sm font-medium text-blue-700">Super Admin's Reply</h3>
                        <span class="text-xs text-blue-600">
                            {{ $viewData['contact']->updated_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                    <div class="bg-white p-3 rounded border border-blue-100">
                        <p class="text-base text-blue-800 whitespace-pre-line">
                            {{ $viewData['contact']->super_admin_text }}</p>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-100">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-sm font-medium text-yellow-700">Awaiting response from Super Admin</span>
                    </div>
                </div>
            @endif
        </div>
    </x-view-modal>
</div>
