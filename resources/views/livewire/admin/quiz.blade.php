<div class="p-4">
    <div class="flex justify-center items-center mb-6">
        <div class="flex gap-2 bg-white p-1 rounded-lg shadow-md">
            @foreach (array_keys($tabs) as $tabKey)
                <button
                    class="px-6 py-2 rounded-md transition-all {{ $activeTab === $tabKey ? 'bg-gradient-3 text-white shadow-md' : 'text-gray-600 hover:bg-pink-200' }}"
                    wire:click="showTab('{{ $tabKey }}')">
                    {{ ucwords(str_replace('_', ' ', $tabKey)) }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Sub Tabs --}}
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            @foreach ($tabs[$activeTab] as $subTabKey => $subTabLabel)
                <button wire:click="setSubTab('{{ $subTabKey }}')"
                    class="relative whitespace-nowrap py-4 px-1 font-medium text-sm {{ $subTab === $subTabKey ? 'text-gradient-3' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    {{ $subTabLabel }}
                    @if ($subTab === $subTabKey)
                        <div
                            class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-[#ff00cc] via-[#cc00ff] to-[#3366ff]">
                        </div>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Scrollable table container --}}
    <div class="p-2">
        @if ($activeTab === 'mcq_questions')
            @if ($subTab === 'mcq_questions')
                @livewire('admin.mcq-table')
            @endif
        @elseif($activeTab === 'student_answers')
            @if ($subTab === 'answers')
                @livewire('admin.mcq-answer-table')
            @endif
        @endif
    </div>
</div>
