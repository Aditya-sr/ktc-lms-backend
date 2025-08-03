<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Student\SectionSubject;
use App\Models\Student\StandardSubject;
use App\Models\Student\StudentDetail;
use App\Services\ResponseService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    protected ResponseService $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }

    public function getAllSubject(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->responseService->errorResponse(
                    'Authentication required',
                    401
                );
            }

            // Get student details
            $student = StudentDetail::where('user_id', $user->id)->first();

            if (!$student) {
                return $this->responseService->errorResponse(
                    'Student record not found',
                    404
                );
            }

            $subjects = collect();

            // First check if there are section-specific subjects
            if ($student->section_id) {
                $sectionSubjects = SectionSubject::where('section_id', $student->section_id)
                    ->where('standard_id', $student->standard_id)
                    ->where('organization_id', $user->organization_id)
                    ->with('subject')
                    ->get();

                if ($sectionSubjects->isNotEmpty()) {
                    $subjects = $sectionSubjects->map(function ($sectionSubject) {
                        return [
                            'id' => $sectionSubject->subject_id,
                            'name' => $sectionSubject->subject->name ?? null,
                            'image' => $sectionSubject->image ?? null,
                            'is_mandatory' => true,
                            'type' => 'section_subject'
                        ];
                    });
                }
            }

            // If no section subjects found, get standard subjects
            if ($subjects->isEmpty()) {
                $standardSubjects = StandardSubject::where('standard_id', $student->standard_id)
                    ->where('organization_id', $user->organization_id)
                    ->with('subject')
                    ->get();

                $subjects = $standardSubjects->map(function ($standardSubject) {
                    return [
                        'id' => $standardSubject->subject_id,
                        'name' => $standardSubject->subject->name ?? null,
                        'image' => $standardSubject->image ?? null,
                        'is_mandatory' => $standardSubject->is_mandatory,
                        'type' => 'standard_subject'
                    ];
                });
            }

            // Remove duplicates (in case same subject exists in both)
            $subjects = $subjects->unique('id')->values();

            return $this->responseService->success(
                $subjects,
                'Subjects retrieved successfully'
            );
        } catch (Exception $e) {
            return $this->responseService->errorResponse(
                'An error occurred: ' . $e->getMessage(),
                500
            );
        }
    }
}
