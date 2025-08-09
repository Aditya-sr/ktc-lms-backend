<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Class AdminController
 *
 * Handles admin authentication: login, logout, and password reset.
 *
 * @package App\Http\Controllers\v1
 *
 * @method \Illuminate\Database\Eloquent\Relations\MorphMany tokens()
 */
class AdminController extends Controller
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->responseService->errorResponse(
                implode(', ', $validator->errors()->all()),
                400
            );
        }

        $user = User::where('email', $request->email)
            ->where('role', 'admin')
            ->first();

        if (!$user) {
            return $this->responseService->error(
                'The provided email or admin does not exist in our records.',
                401
            );
        }

        if (!Hash::check($request->password, $user->password)) {
            return $this->responseService->error(
                'The provided password is incorrect.',
                401
            );
        }

        if (!$user->is_active) {
            return $this->responseService->error(
                'Your account has been deactivated. Please contact support.',
                403
            );
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return $this->responseService->authResponse(
            new UserResource($user),
            $token,
            'Admin login successful.'
        );
    }


    public function adminProfile(Request $request)
    {
        try {
            $user = Auth::user();


            return $this->responseService->success(
                new UserResource($user),
                'Admin profile retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->responseService->errorResponse(
                'Failed to retrieve admin profile: ' . $e->getMessage(),
                500
            );
        }
    }

    // public function adminLogoutAllDevices(Request $request)
    // {
    //     $user = $request->user();

    //     if ($user) {
    //         $user->tokens()->delete();
    //         return $this->responseService->successResponse(
    //             'Admin logged out from all devices successfully.'
    //         );
    //     }

    //     return $this->responseService->errorResponse(
    //         'No user is currently logged in.',
    //         401
    //     );
    // }

    // public function adminLogout(Request $request)
    // {
    //     $user = $request->user();

    //     if ($user && $user->currentAccessToken()) {
    //         $user->currentAccessToken()->delete();
    //         return $this->responseService->successResponse(
    //             'Admin logged out from this device successfully.'
    //         );
    //     }

    //     return $this->responseService->errorResponse(
    //         'No user is currently logged in.',
    //         401
    //     );
    // }

    /**
     * Reset admin password
     */
    // public function resetPassword(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'old_password' => 'required',
    //         'new_password' => 'required|min:8|confirmed', // new_password_confirmation is required
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->responseService->errorResponse(
    //             implode(', ', $validator->errors()->all()),
    //             400
    //         );
    //     }

    //     $user = $request->user();

    //     if (!$user) {
    //         return $this->responseService->errorResponse(
    //             'No user is currently logged in.',
    //             401
    //         );
    //     }

    //     if (!Hash::check($request->old_password, $user->password)) {
    //         return $this->responseService->errorResponse(
    //             'Old password is incorrect.',
    //             401
    //         );
    //     }

    //     $user->update([
    //         'password' => Hash::make($request->new_password),
    //     ]);

    //     // Optional: Logout from all devices after password change
    //     $user->tokens()->delete();

    //     return $this->responseService->successResponse(
    //         'Password reset successfully. Please log in again.'
    //     );
    // }
}
