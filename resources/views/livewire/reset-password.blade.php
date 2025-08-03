<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-50 to-pink-50">
    <div class="flex flex-col md:flex-row w-full max-w-5xl h-[600px] rounded-2xl overflow-hidden shadow-lg bg-white">
        <div
            class="hidden md:flex md:w-1/2 bg-gradient-to-br from-purple-100 to-pink-100 flex-col items-center justify-center p-8 relative">
            <div
                class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyMTYsIDE4MCwgMjU1LCAwLjEpIj48L3JlY3Q+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSJ1cmwoI3BhdHRlcm4pIj48L3JlY3Q+PC9zdmc+')]">
            </div>
            <div class="relative z-10 w-3/4 max-w-xs">
                <img src="{{ asset('admin-image/Frame 1171279095.png') }}" alt="Illustration"
                    class="w-full h-auto object-contain">
            </div>
        </div>

        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-8 md:p-12">
            <div class="mb-8 w-20 h-20">
                <img src="{{ asset('website-image/Group 11525.png') }}" alt="Logo"
                    class="w-full h-full object-contain">
            </div>

            @if ($step === 1)
                <div class="text-center mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Reset Password</h1>
                    <p class="text-gray-500 mt-2">Enter your mobile number to receive OTP</p>
                </div>

                <div class="w-full max-w-sm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Mobile Number</label>
                        <input type="tel" wire:model.live="mobile" placeholder="Enter your mobile number"
                            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @error('mobile')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <button wire:click="sendOtp"
                        class="w-full py-3 bg-gradient-3 text-white rounded-lg hover:bg-gradient-3-hover transition duration-300 shadow-md hover:shadow-lg">
                        Send OTP
                    </button>
                </div>
            @elseif($step === 2)
                <div class="text-center mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Verify OTP</h1>
                    <p class="text-gray-500 mt-2">Enter the OTP sent to
                        {{ substr($mobile, 0, 3) }}*****{{ substr($mobile, -2) }}</p>
                </div>

                <div class="w-full max-w-sm" x-data="{
                    otp: @js($otp),
                    countdown: @js($countdown),
                    init() {
                        this.$watch('otp', (newOtp) => {
                            // Sync with Livewire
                            @this.set('otp', newOtp);
                
                            // Auto-verify when all boxes are filled with digits
                            if (newOtp.every(digit => digit.match(/^[0-9]$/))) {
                                @this.dispatch('verifyOtp');
                            }
                        });
                
                        // Start countdown
                        this.countdown = @js($countdown);
                        const countdownInterval = setInterval(() => {
                            if (this.countdown > 0) {
                                this.countdown--;
                                @this.set('countdown', this.countdown);
                            } else {
                                clearInterval(countdownInterval);
                            }
                        }, 1000);
                    },
                    handleInput(index) {
                        if (this.otp[index].length === 1 && index < 5) {
                            this.$refs[`otp${index + 1}`].focus();
                        }
                    },
                    handleBackspace(index) {
                        if (this.otp[index].length === 0 && index > 0) {
                            this.$refs[`otp${index - 1}`].focus();
                        }
                    },
                    formatCountdown(seconds) {
                        if (seconds <= 0) return '';
                        const minutes = Math.floor(seconds / 60);
                        const secs = seconds % 60;
                        return `${minutes}:${secs.toString().padStart(2, '0')}`;
                    }
                }">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">OTP Code</label>
                        <div class="flex justify-between space-x-2">
                            @for ($i = 0; $i < 6; $i++)
                                <input type="text" x-model="otp[{{ $i }}]" x-ref="otp{{ $i }}"
                                    wire:model.live="otp.{{ $i }}" maxlength="1"
                                    @input="handleInput({{ $i }})"
                                    @keydown.backspace="handleBackspace({{ $i }})"
                                    class="w-12 h-12 text-center text-xl border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    @if ($i === 0) autofocus @endif inputmode="numeric"
                                    pattern="[0-9]*"
                                    x-on:keypress="return ($event.charCode >= 48 && $event.charCode <= 57)">
                            @endfor
                        </div>
                        @error('enteredOtp')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <button wire:click="resendOtp" x-bind:disabled="countdown > 0"
                                x-bind:class="countdown > 0 ? 'text-gray-400' :
                                    'text-purple-600 hover:text-purple-800 hover:underline'">
                                Resend OTP
                            </button>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500" x-text="formatCountdown(countdown)"></span>
                        </div>
                    </div>

                    <button wire:click="verifyOtp"
                        class="w-full py-3 bg-gradient-3 text-white rounded-lg hover:bg-gradient-3-hover transition duration-300 shadow-md hover:shadow-lg">
                        Verify OTP
                    </button>
                </div>
            @elseif($step === 3)
                <div class="text-center mb-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Set New Password</h1>
                    <p class="text-gray-500 mt-2">Create a strong new password</p>
                </div>

                <div class="w-full max-w-sm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">New Password</label>
                        <div class="relative">
                            <input type="password" wire:model.live="password" placeholder="Enter new password"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-10">
                            <button
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" wire:model.live="password_confirmation"
                                placeholder="Confirm new password"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-10">
                        </div>
                    </div>

                    <button wire:click="resetPassword"
                        class="w-full py-3 bg-gradient-3 text-white rounded-lg hover:bg-gradient-3-hover transition duration-300 shadow-md hover:shadow-lg">
                        Reset Password
                    </button>
                </div>
            @endif

            <div class="mt-6 text-center">
                <a href="{{ route('admin.login') }}"
                    class="text-sm text-purple-600 hover:text-purple-800 hover:underline">Back to Login</a>
            </div>
        </div>
    </div>
</div>
