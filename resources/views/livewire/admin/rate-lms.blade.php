<div class="p-8">
    @if (!$rated)
        <!-- Rating Form with wire:submit -->
        <form wire:submit.prevent="submit">
            <div class="text-start mb-8">
                <h1 class="text-3xl font-medium text-gray-800 mb-2">Rate LMS</h1>
                <p class="text-gray-600 text-lg">Rate the LMS and write your review</p>
            </div>

            <!-- Star Rating -->
            <div class="mb-8 flex justify-center">
                <div class="rounded-3xl p-6 border-4 border-pink-300 shadow-lg bg-white">
                    <div class="flex justify-center gap-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="setRating({{ $i }})"
                                class="focus:outline-none transition-all duration-200 hover:scale-110">
                                <svg class="w-16 h-16 {{ $i <= $rating ? 'text-orange-500 fill-orange-500' : 'text-orange-300 fill-transparent' }}"
                                    stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                    style="filter: drop-shadow(0 4px 8px rgba(249, 168, 37, 0.3));">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                </svg>
                            </button>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Review Textarea -->
            <div class="mb-8">
                <div class="border-4 border-pink-300 rounded-3xl p-2 bg-white shadow-lg">
                    <textarea wire:model="feedback" rows="6"
                        class="w-full px-4 py-3 bg-transparent border-0 rounded-2xl focus:outline-none text-gray-700 resize-none text-base leading-relaxed"
                        placeholder="Please write about the LMS in at least 10 words."></textarea>
                </div>
                @error('feedback')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="px-12 py-3 bg-gradient-3 text-white rounded-2xl text-xl font-semibold transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl">
                    Submit
                </button>
            </div>
        </form>
    @else
        <!-- Thank You Screen (same as before) -->
        <div class="text-center py-8">
            <!-- Checkmark -->
            <div
                class="w-24 h-24 bg-gradient-to-r from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Thank You!</h1>
            <p class="text-gray-600 text-lg mb-8">We appreciate your feedback.</p>

            <!-- Divider -->
            <div class="border-t-2 border-gray-200 my-8 mx-auto w-1/2"></div>

            <!-- Rating Display -->
            <div class="mb-8">
                <p class="text-gray-700 mb-4 font-medium text-lg">Your Rating:</p>
                <div class="rounded-3xl p-6 border-4 border-pink-300 shadow-lg bg-white inline-block">
                    <div class="flex justify-center gap-4">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-12 h-12 {{ $i <= $rating ? 'text-orange-500 fill-orange-500' : 'text-orange-300 fill-transparent' }}"
                                stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"
                                style="filter: drop-shadow(0 2px 4px rgba(249, 168, 37, 0.3));">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Feedback Preview -->
            <div class="border-4 border-pink-300 rounded-3xl p-6 mb-8 bg-white shadow-lg mx-auto max-w-2xl">
                <p class="text-gray-700 italic text-base leading-relaxed">
                    "{{ $feedback }}"
                </p>
            </div>

            <!-- Success Message -->
            <div
                class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-3xl p-6 border-4 border-blue-200 shadow-lg max-w-2xl mx-auto">
                <p class="text-blue-700 font-semibold text-lg">
                    {{ session('message', 'Your feedback has been submitted successfully.') }}
                </p>
            </div>
        </div>
    @endif
</div>
