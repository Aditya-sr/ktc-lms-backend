<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\OtplessService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\On;
use Livewire\Component;

class ResetPassword extends Component
{
    public $step = 1;
    public $mobile;
    public $otp = ['', '', '', '', '', ''];
    public $enteredOtp = '';
    public $password;
    public $password_confirmation;
    public $countdown = 120;
    public $orderId;
    public $mobileWithCountryCode;

    protected $rules = [
        'mobile' => 'required|digits:10',
        'enteredOtp' => 'required|digits:6',
        'password' => 'required|min:8|confirmed',
    ];

    public function sendOtp(OtplessService $otplessService)
    {
        $this->validate(['mobile' => 'required|digits:10']);

        $this->mobileWithCountryCode = '+91' . $this->mobile;

        $user = User::where('mobile_number', $this->mobile)
            ->orWhere('mobile_number', $this->mobileWithCountryCode)
            ->first();

        if (!$user) {
            $this->addError('mobile', 'The selected mobile number is invalid.');
            return;
        }

        try {
            $response = $otplessService->sendOtp($this->mobileWithCountryCode);
            $this->orderId = $response['orderId'] ?? null;

            $user->update([
                'otp_order_id' => $this->orderId,
                'otp_expires_at' => Carbon::now()->addMinutes(2),
            ]);

            $this->startCountdown();
            $this->step = 2;
        } catch (\Exception $e) {
            $this->addError('mobile', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    #[On('verifyOtp')]
    public function verifyOtp(OtplessService $otplessService)
    {
        $this->enteredOtp = implode('', $this->otp);

        $this->validate([
            'enteredOtp' => 'required|digits:6',
        ]);

        $user = User::where('mobile_number', $this->mobile)
            ->orWhere('mobile_number', $this->mobileWithCountryCode)
            ->first();

        if (!$user) {
            $this->addError('otp', 'User not found');
            return;
        }

        if (Carbon::now()->gt($user->otp_expires_at)) {
            $this->addError('otp', 'OTP has expired');
            return;
        }

        try {
            $verification = $otplessService->verifyOtp($this->mobileWithCountryCode, $this->enteredOtp, $user->otp_order_id);
            if ($verification['isOTPVerified'] ?? false) {
                $this->step = 3;
            } else {
                $this->otp = ['', '', '', '', '', ''];
                $this->enteredOtp = '';
                $this->addError('otp', $verification['message'] ?? 'Invalid OTP');
            }
        } catch (\Exception $e) {
            $this->otp = ['', '', '', '', '', ''];
            $this->enteredOtp = '';
            $this->addError('otp', 'OTP verification failed: ' . $e->getMessage());
        }
    }

    public function resetPassword()
    {
        $this->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('mobile_number', $this->mobile)
            ->orWhere('mobile_number', $this->mobileWithCountryCode)
            ->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($this->password),
                'otp_order_id' => null,
                'otp_expires_at' => null,
            ]);

            session()->flash('message', 'Password reset successfully!');
            return redirect()->route('admin.login');
        }

        $this->addError('password', 'User not found');
    }

    public function resendOtp(OtplessService $otplessService)
    {
        if ($this->countdown <= 0) {
            $this->sendOtp($otplessService);
        }
    }

    public function startCountdown()
    {
        $this->dispatch('start-countdown');
    }

    public function decrementCountdown()
    {
        if ($this->countdown > 0) {
            $this->countdown--;
        }
    }

    public function render()
    {
        return view('livewire.reset-password')->layout('components.layouts.fullscreen');
    }
}
