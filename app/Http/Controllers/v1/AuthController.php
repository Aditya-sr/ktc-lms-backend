<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Admin\SchoolInfo;
use App\Models\User;
use App\Services\OtplessService;
use App\Services\ResponseService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $otplessService;
    protected $responseService;

    public function unauthenticate()
    {
        $responseArray = [
            'status' => false,
            'message' => 'Your are not logged in',
            'status_code' => 403,
        ];
        return response()->json($responseArray, 403);
    }

    public function __construct(OtplessService $otplessService, ResponseService $responseService)
    {
        $this->otplessService = $otplessService;
        $this->responseService = $responseService;
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        $mobileNumber = $request->mobile_number;
        if (strpos($mobileNumber, '+91') !== 0) {
            $mobileNumberWithCountryCode = '+91' . $mobileNumber;
        } else {
            $mobileNumberWithCountryCode = $mobileNumber;
        }

        $user = User::where('mobile_number', $mobileNumberWithCountryCode)
            ->orWhere('mobile_number', $mobileNumber)
            ->first();
        if (!$user) {
            return $this->responseService->errorResponse(
                'The selected mobile number is invalid.',
                400
            );
        }

        try {
            $response = $this->otplessService->sendOtp($mobileNumberWithCountryCode);
            $orderId = $response['orderId'] ?? null;

            $user->otp_order_id = $orderId;
            $user->otp_expires_at = Carbon::now()->addMinutes(2);
            $user->save();

            $userdata = [
                'user_id' => $user->id,
                'mobile_number' => $mobileNumberWithCountryCode
            ];

            return $this->responseService->success(
                $userdata,
                'OTP sent successfully for password reset'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'Failed to send OTP for password reset: ' . $e->getMessage(),
                500
            );
        }
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        try {
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->responseService->errorResponse(
                    'User not found',
                    404
                );
            }

            $mobileNumber = $user->mobile_number;
            if (strpos($mobileNumber, '+91') !== 0) {
                $mobileNumber = '+91' . $mobileNumber;
            }

            $response = $this->otplessService->verifyOtp(
                $mobileNumber,
                $request->otp,
                $user->otp_order_id
            );

            if (isset($response['isOTPVerified']) && $response['isOTPVerified'] == true) {
                $user->otp = 0;
                $user->otp_expires_at = null;
                $user->save();

                return $this->responseService->success(
                    null,
                    'OTP verified successfully'
                );
            }

            return $this->responseService->errorResponse(
                'Invalid OTP or OTP has expired',
                401
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'Failed to verify OTP: ' . $e->getMessage(),
                500
            );
        }
    }

    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        try {
            $mobileWithCountryCode = strpos($request->mobile_number, '+91') !== 0
                ? '+91' . $request->mobile_number
                : $request->mobile_number;

            $user = User::where('mobile_number', $mobileWithCountryCode)
                ->orWhere('mobile_number', $request->mobile_number)
                ->first();

            if (!$user) {
                return $this->responseService->errorResponse(
                    'User not found',
                    404
                );
            }

            if ($user->otp_expires_at && Carbon::now()->lt($user->otp_expires_at)) {
                return $this->responseService->errorResponse(
                    'OTP is still valid. Please wait before requesting a new one.',
                    400
                );
            }

            $response = $this->otplessService->resendOtp($user->otp_order_id);
            $user->otp_order_id = $response['orderId'] ?? null;
            $user->otp_expires_at = Carbon::now()->addMinutes(2);
            $user->save();

            return $this->responseService->success(
                null,
                'OTP resent successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'Failed to resend OTP: ' . $e->getMessage(),
                500
            );
        }
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/', 'confirmed'],
            'user_id' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        try {
            $user = User::find($request->user_id);
            if (!$user) {
                return $this->responseService->errorResponse(
                    'User not found',
                    404
                );
            }

            if ($user->otp_expires_at !== null) {
                return $this->responseService->errorResponse(
                    'OTP not verified',
                    400
                );
            }

            $user->password = Hash::make($request->password);
            $user->save();

            $token = $user->createToken('authToken')->plainTextToken;
            $token = explode('|', $token)[1];

            return $this->responseService->authResponse(
                new UserResource($user),
                $token,
                'Password updated successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/',
                'confirmed'
            ]
        ], [
            'current_password.required' => 'Current password is required.',
            'new_password.required' => 'New password is required.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.regex' => 'New password must contain at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'new_password.confirmed' => 'New password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        try {
            // Get authenticated user using Auth facade
            $user = Auth::user();

            if (!$user) {
                return $this->responseService->errorResponse(
                    'Authentication required',
                    401
                );
            }

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return $this->responseService->errorResponse(
                    'Current password is incorrect',
                    401
                );
            }

            // Check if new password is same as current
            if (strcmp($request->current_password, $request->new_password) === 0) {
                return $this->responseService->errorResponse(
                    'New password must be different from current password',
                    400
                );
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Revoke all tokens and create new one
            $user->tokens()->delete();
            $token = $user->createToken('authToken')->plainTextToken;
            $token = explode('|', $token)[1];

            return $this->responseService->authResponse(
                new UserResource($user),
                $token,
                'Password updated successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }

    public function schoolInfo()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->responseService->errorResponse(
                    'Authentication required',
                    401
                );
            }

            $schoolInfo = SchoolInfo::with(['managementTeam', 'documents', 'organization'])
                ->where('organization_id', $user->organization_id)
                ->first();

            if (!$schoolInfo) {
                return $this->responseService->errorResponse(
                    'School information not found',
                    404
                );
            }

            $schoolData = $schoolInfo->toArray();

            // Add full URLs for management team photos
            $schoolData['management_team'] = $schoolInfo->managementTeam->map(function ($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'designation' => $member->designation,
                    'photo_url' => $member->photo_path,
                    'sort_order' => $member->sort_order
                ];
            });

            // Add full URLs for documents
            $schoolData['documents'] = $schoolInfo->documents->map(function ($document) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'file_url' => $document->file_path,
                    'file_type' => $document->file_type,
                    'sort_order' => $document->sort_order
                ];
            });

            $schoolData['organization'] = [
                'logo_url' => $schoolInfo->organization->logo,
                'name' => $schoolInfo->organization->name
            ];

            return $this->responseService->success(
                $schoolData,
                'School information retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }
}
