<div>
    <div class="flex justify-center items-center mb-6 p-4">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'teacher' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('teacher')">Teacher Enquires</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'student' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('student')">Student Enquiries</button>
            <button
                class="px-6 py-2 rounded-md transition-all {{ $activeTab === 'website' ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                wire:click="showTab('website')">Website Enquires</button>
        </div>
    </div>

    <div class="flex-1 overflow-x-auto p-4">
        @if ($activeTab === 'teacher')
            @livewire('admin.teacher-contact-table')
        @elseif($activeTab === 'student')
            @livewire('admin.student-contact-table')
        @elseif($activeTab === 'website')
            @livewire('admin.enquiry-table')
        @endif
    </div>
</div>
