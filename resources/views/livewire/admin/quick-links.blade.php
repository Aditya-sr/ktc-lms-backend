<div class="max-w-7xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Quick Links</h1>
    </div>

    <div class="rounded-lg">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 p-4 max-h-[600px] overflow-y-auto ">
            @foreach ($links as $link)
                <a href="{{ route($link['route'], ['organization' => request()->route('organization')]) }}"
                   class="flex flex-col items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 bg-white transition">
                    <div class="w-10 h-10 bg-{{ $link['color'] }}-100 rounded-full flex items-center justify-center mb-2">
                        <x-icon name="{{ $link['icon'] }}" class="w-5 h-5 text-{{ $link['color'] }}-600" />
                    </div>
                    <span class="text-sm font-medium text-center">{{ $link['title'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
