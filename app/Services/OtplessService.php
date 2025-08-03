<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OtplessService
{
    protected $appId;
    protected $clientId;
    protected $clientSecret;

    public function __construct()
    {
        $this->appId = config('otpless.appId');
        $this->clientId = config('otpless.clientId');
        $this->clientSecret = config('otpless.clientSecret');
    }

    public function generateOtp()
    {
        return rand(100000, 999999);
    }

    public function sendOtp($phoneNumber)
    {  
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ])->post('https://auth.otpless.app/auth/otp/v1/send', [
            'phoneNumber' => $phoneNumber,
            'channel' => 'SMS',
            'expiry' => 1200,
        ]);
        return $response->json();
    }

    public function verifyOtp($phoneNumber, $otp ,$orderId)
    {  
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ])->post('https://auth.otpless.app/auth/otp/v1/verify', [
            'phoneNumber' => $phoneNumber,
            'otp' => $otp,
            'orderId' => $orderId
        ]);
        return $response->json();
    }

    public function resendOtp($orderId)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ])->post('https://auth.otpless.app/auth/otp/v1/resend', [
            'orderId' => $orderId
        ]);
        return $response->json();
    }
}
