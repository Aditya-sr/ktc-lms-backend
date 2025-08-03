<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Student\StudentDetail;
use App\Services\ResponseService;
use App\Services\StudentAttendanceService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $responseService;
    protected $attendanceService;

    public function __construct(StudentAttendanceService $attendanceService, ResponseService $responseService)
    {
        $this->responseService = $responseService;
        $this->attendanceService = $attendanceService;
    }

    /**
     * Get students for attendance marking
     */
    public function getStudentsForAttendance(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user || !$user->teacherDetail) {
                return $this->responseService->errorResponse('Authentication required', 401);
            }

            $date = $request->date ?? now()->toDateString();
            $students = $this->attendanceService->getStudentsForAttendance(
                $user->teacherDetail->id,
                $user->organization_id,
                $date
            );

            if ($students->isEmpty()) {
                return $this->responseService->errorResponse('No students found for attendance marking', 404);
            }

            return $this->responseService->success(
                $students,
                'Students retrieved successfully for attendance'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Bulk submit attendance
     */
    public function bulkSubmitAttendance(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->responseService->errorResponse('Authentication required', 401);
            }

            $validated = $request->validate([
                'attendance_date' => 'required|date',
                'attendances' => 'required|array|min:1',
                'attendances.*.student_detail_id' => 'required|exists:student_details,id',
                'attendances.*.status' => 'required|boolean',
                'attendances.*.remarks' => 'nullable|string'
            ]);

            $results = $this->attendanceService->bulkSubmitAttendance(
                $validated,
                $user->id,
                $user->organization_id
            );

            // Get summary for the day
            $firstStudent = StudentDetail::find($validated['attendances'][0]['student_detail_id']);
            $summary = $this->attendanceService->getDailyAttendanceSummary(
                $user->organization_id,
                $firstStudent->standard_id,
                $firstStudent->section_id,
                $validated['attendance_date']
            );

            return $this->responseService->success([
                'processed_count' => count($results),
                'summary' => $summary,
                'details' => $results
            ], 'Attendance submitted successfully');
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }

    /**
     * Get attendance summary for a class
     */
    public function getAttendanceSummary(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->responseService->errorResponse('Authentication required', 401);
            }

            $validated = $request->validate([
                'date' => 'required|date',
                'standard_id' => 'required|exists:standards,id',
                'section_id' => 'required|exists:sections,id'
            ]);

            $summary = $this->attendanceService->getDailyAttendanceSummary(
                $user->organization_id,
                $validated['standard_id'],
                $validated['section_id'],
                $validated['date']
            );

            return $this->responseService->success(
                $summary,
                'Attendance summary retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }
}
