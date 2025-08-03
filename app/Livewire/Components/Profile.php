<?php

namespace App\Livewire\Components;

use App\Models\Admin\SchoolInfo as AdminSchoolInfo;
use App\Models\Admin\SchoolDocument;
use App\Models\Organization;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use WireUi\Traits\WireUiActions;

class Profile extends Component
{
    use WithFileUploads, WireUiActions;

    public $organization;
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;
    public $showPassword = false;
    public $photo;
    public $tempPhotoUrl;
    public $activeTab = 'profile';
    public $schoolInfo;

    // School Info Fields
    public $aboutSchool;
    public $websiteInfo;
    public $websiteUrl;
    public $schoolEmail;
    public $schoolMobileNo;
    public $schoolAddress;
    public $schoolManagement = [];
    public $documentFiles = [];
    public $documentTitles = [];
    public $uploadedDocuments = [];
    public $schoolDocumentsText;
    public $managementPhoto;

    public function mount()
    {
        $this->organization = Organization::with('schoolInfo')
            ->where('id', Auth::user()->organization_id)
            ->first();
        $this->loadSchoolInfo();
    }

    public function loadSchoolInfo()
    {
        $this->schoolInfo = AdminSchoolInfo::where('organization_id', Auth::user()->organization_id)->first();

        if ($this->schoolInfo) {
            $this->aboutSchool = $this->schoolInfo->about_school;
            $this->websiteInfo = $this->schoolInfo->website_info;
            $this->websiteUrl = $this->schoolInfo->website_url;
            $this->schoolEmail = $this->schoolInfo->school_email;
            $this->schoolMobileNo = $this->schoolInfo->school_mobile;
            $this->schoolAddress = $this->schoolInfo->school_address;
            $this->schoolManagement = $this->schoolInfo->managementTeam->toArray();
            $this->schoolDocumentsText = $this->schoolInfo->school_document_text;
            $this->uploadedDocuments = $this->schoolInfo->documents->toArray();
        } else {
            $this->schoolManagement = [
                ['name' => '', 'designation' => '', 'photo' => null]
            ];
        }
    }

    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updatedPhoto()
    {
        $this->validate([
            'photo' => ['image', 'max:2048'],
        ]);
        $this->tempPhotoUrl = $this->photo->temporaryUrl();
    }

    public function savePhoto()
    {
        $this->validate([
            'photo' => ['required', 'image', 'max:2048'],
        ]);

        if ($this->organization->logo) {
            $oldPhotoPath = parse_url($this->organization->logo, PHP_URL_PATH);
            Storage::disk('s3')->delete($oldPhotoPath);
        }

        $imagePath = $this->photo->store('organization-photos', 's3');
        Storage::disk('s3')->setVisibility($imagePath, 'public');
        $imageUrl = Storage::disk('s3')->url($imagePath);

        $this->organization->update(['logo' => $imageUrl]);
        $this->organization->refresh();

        $this->reset('photo', 'tempPhotoUrl');
        $this->notification()->success('Profile photo updated successfully!!!');
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function updatePassword()
    {
        $this->validate([
            'currentPassword' => ['required', 'current_password'],
            'newPassword' => [
                'required',
                'different:currentPassword',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'confirmPassword' => ['required', 'same:newPassword'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->newPassword)
        ]);

        $this->reset(['currentPassword', 'newPassword', 'confirmPassword']);
        $this->notification()->success('Password updated successfully!!');
    }

    public function addManagement()
    {
        $this->schoolManagement[] = [
            'name' => '',
            'designation' => '',
            'photo' => null
        ];
    }

    public function removeManagement($index)
    {
        if (!empty($this->schoolManagement[$index]['photo_path'])) {
            $oldPhotoPath = parse_url($this->schoolManagement[$index]['photo_path'], PHP_URL_PATH);
            Storage::disk('s3')->delete($oldPhotoPath);
        }

        unset($this->schoolManagement[$index]);
        $this->schoolManagement = array_values($this->schoolManagement);
        $this->notification()->success('Remove to Click Save all Button!');
    }

    public function updatedManagementPhoto($value, $index)
    {
        $this->validate([
            "schoolManagement.$index.photo" => 'image|max:2048',
        ]);
    }

    public function removeManagementPhoto($index)
    {
        if (isset($this->schoolManagement[$index]['photo']) && $this->schoolManagement[$index]['photo'] instanceof TemporaryUploadedFile) {
            $this->schoolManagement[$index]['photo'] = null;
        } elseif (!empty($this->schoolManagement[$index]['photo_path'])) {
            $oldPhotoPath = parse_url($this->schoolManagement[$index]['photo_path'], PHP_URL_PATH);
            Storage::disk('s3')->delete($oldPhotoPath);
            $this->schoolManagement[$index]['photo_path'] = null;
        }
    }

    public function saveSchoolInfo()
    {
        $this->validate([
            'aboutSchool' => 'nullable|string',
            'websiteInfo' => 'nullable|string',
            'websiteUrl' => 'nullable|url',
            'schoolEmail' => 'nullable|email',
            'schoolMobileNo' => 'nullable|regex:/^[0-9]+$/|min:10|max:15',
            'schoolAddress' => 'nullable|string|max:255',
            'documentFiles.*' => 'nullable|file|max:5120',
            'documentTitles.*' => 'required_with:documentFiles.*|string|max:255',
            'schoolManagement.*.name' => 'required|string',
            'schoolManagement.*.designation' => 'required|string',
            'schoolManagement.*.photo' => 'nullable|image|max:2048',
            'schoolDocumentsText' => 'nullable|string'
        ]);

        $schoolInfo = AdminSchoolInfo::updateOrCreate(
            ['organization_id' => $this->organization->id],
            [
                'about_school' => $this->aboutSchool,
                'website_info' => $this->websiteInfo,
                'website_url' => $this->websiteUrl,
                'school_email' => $this->schoolEmail,
                'school_mobile' => $this->schoolMobileNo,
                'school_address' => $this->schoolAddress,
                'school_document_text' => $this->schoolDocumentsText,
            ]
        );

        // Process management team
        $schoolInfo->managementTeam()->delete();
        foreach ($this->schoolManagement as $index => $member) {
            $memberData = [
                'name' => $member['name'],
                'designation' => $member['designation'],
                'sort_order' => $index,
            ];

            if (isset($member['photo']) && $member['photo'] instanceof TemporaryUploadedFile) {
                $photoPath = $member['photo']->store('school-management/photos', 's3');
                Storage::disk('s3')->setVisibility($photoPath, 'public');
                $memberData['photo_path'] = Storage::disk('s3')->url($photoPath);
            } elseif (!empty($member['photo_path'])) {
                $memberData['photo_path'] = $member['photo_path'];
            }

            $schoolInfo->managementTeam()->create($memberData);
        }

        // Process document uploads
        if ($this->documentFiles) {
            foreach ($this->documentFiles as $index => $file) {
                $filePath = $file->store('school-documents', 's3');
                Storage::disk('s3')->setVisibility($filePath, 'public');

                $schoolInfo->documents()->create([
                    'title' => $this->documentTitles[$index] ?? 'Document',
                    'file_path' => Storage::disk('s3')->url($filePath),
                    'file_type' => $file->getClientOriginalExtension(),
                    'sort_order' => count($this->uploadedDocuments) + $index,
                ]);
            }
        }
        $this->notification()->success('Save Successfully!');
        $this->loadSchoolInfo();
        $this->reset(['documentFiles', 'documentTitles']);
    }

    public function removeDocument($id)
    {
        $document = SchoolDocument::find($id);
        if ($document) {
            $filePath = parse_url($document->file_path, PHP_URL_PATH);
            Storage::disk('s3')->delete($filePath);
            $document->delete();
            $this->loadSchoolInfo();
        }
    }

    public function removeUploadedFile($index)
    {
        unset($this->documentFiles[$index]);
        unset($this->documentTitles[$index]);
        $this->documentFiles = array_values($this->documentFiles);
        $this->documentTitles = array_values($this->documentTitles);
    }

    public function render()
    {
        return view('livewire.components.profile');
    }
}
