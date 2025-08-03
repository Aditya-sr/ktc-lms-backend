<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'basic_info' => $this->formatBasicInfo(),
            'professional_info' => $this->formatProfessionalInfo(),
            'contact_info' => $this->formatContactInfo(),
            'assignments' => $this->formatAssignments(),
            'organization_info' => $this->formatOrganizationInfo()
        ];
    }

    protected function formatBasicInfo()
    {
        return [
            'name' => $this->user->name ?? null,
            'email' => $this->user->email ?? null,
            'mobile_number' => $this->user->mobile_number ?? null,
            'image_url' => $this->user->image ? asset('storage/' . $this->user->image) : null,
            'gender' => $this->user->gender ?? null,
            'dob' => optional($this->user->dob)->format('Y-m-d'),
            'role' => $this->user->role ?? null,
            'is_active' => $this->user->is_active ?? null
        ];
    }

    protected function formatProfessionalInfo()
    {
        return [
            'employee_id' => $this->employee_id ?? null,
            'date_of_joining' => $this->date_of_joining,
            'qualification' => $this->qualification ?? null,
            'total_subjects' => $this->assignedSubjects->count(),
            'total_sections' => $this->teacherSections->count()
        ];
    }

    protected function formatContactInfo()
    {
        return [
            'phone' => $this->phone ?? null,
            'emergency_contact' => $this->emergency_contact ?? null,
            'address' => $this->address ?? null,
            'city' => $this->city ?? null,
            'state' => $this->state ?? null,
            'pincode' => $this->pincode ?? null
        ];
    }

    protected function formatAssignments()
    {
        return [
            'subjects' => $this->assignedSubjects->map(function ($subject) {
                return [
                    'subject_id' => $subject->subject_id ?? null,
                    'subject_name' => $subject->subject->name ?? null,
                    'standard_id' => $subject->standard_id ?? null,
                    'standard_name' => $subject->standard->name ?? null,
                    'section_id' => $subject->section_id ?? null,
                    'section_name' => $subject->section->name ?? null
                ];
            }),
            'sections' => $this->teacherSections->map(function ($section) {
                return [
                    'section_id' => $section->section_id ?? null,
                    'section_name' => $section->section->name ?? null,
                    'standard_id' => $section->section->standard_id ?? null,
                    'standard_name' => $section->section->standard->name ?? null
                ];
            })->unique()
        ];
    }

    protected function formatOrganizationInfo()
    {
        return [
            'name' => $this->organization->name ?? null,
            'code' => $this->organization->code ?? null,
            'logo_url' => $this->organization->logo ? asset('storage/' . $this->organization->logo) : null
        ];
    }
}
