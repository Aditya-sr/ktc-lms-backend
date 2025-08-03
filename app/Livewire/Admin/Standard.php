<?php

namespace App\Livewire\Admin;

use App\Models\Student\Section;
use App\Models\Student\SectionSubject;
use App\Models\Student\Standard as StudentStandard;
use App\Models\Student\StandardSubject;
use App\Models\Student\Subject;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;


class Standard extends Component
{
    use WireUiActions, WithFileUploads;

    public $openStandard = false;
    public $openSection = false;
    public $openSubject = false;
    public $editId = null;
    public $standards, $sections, $subjects;

    // Standard fields
    public $standardName = '';
    public $standardCode = '';
    public $standardBoard = '';
    public $standardOrder = '';
    public $standardActive = true;
    public $showViewModal = false;
    public $viewModalTitle = '';
    public $viewData = [];
    public $activeTab = 'standard';



    // Section fields
    public $sectionName = '';
    public $sectionDescription = '';
    public $sectionActive = true;
    public $selectedStandard = null;
    public $subjectImage;
    public $subjectDetailImage;

    // Subject properties
    public $subjectName, $subjectCode, $subjectDescription, $subjectActive = true;
    public $selectedStandardForSubject, $selectedSectionsForSubject = [];
    public $isMandatory = true;

    protected $listeners = [
        'onViewStandardAdmin',
        'onEditStandard',
        'onDeleteStandard',
        'onImageClick',
        'onViewSectionAdmin',
        'onDeleteSection',
        'onEditSection',
        'onEditSubject',
        'onDeleteSubject',
        'onViewSubjectAdmin'
    ];


    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function mount()
    {
        $this->standards = StudentStandard::where('organization_id', Auth::user()->organization_id)
            ->orderBy('order')
            ->get();

        $this->sections = Section::with('standard')
            ->whereHas('standard', function ($query) {
                $query->where('organization_id', Auth::user()->organization_id);
            })
            ->orderBy('name')
            ->get();

        $this->subjects = Subject::where('organization_id', Auth::user()->organization_id)->get();
        $this->selectedSectionsForSubject = [];
    }

    public function updatedSelectedStandardForSubject($value)
    {
        if ($value) {
            $this->sections = Section::where('standard_id', $value)
                ->whereHas('standard', function ($query) {
                    $query->where('organization_id', Auth::user()->organization_id);
                })
                ->orderBy('name')
                ->get();
        } else {
            $this->sections = collect();
        }
        $this->selectedSectionsForSubject = [];
    }

    public function onStandard()
    {
        $this->editId = null;
        $this->resetStandardFields();
        $this->openStandard = true;
    }

    public function onSection()
    {
        $this->editId = null;
        $this->resetSectionFields();
        $this->openSection = true;
    }

    public function onSubject()
    {
        $this->editId = null;
        $this->openSubject = true;
        $this->resetSubjectFields();
    }

    public function closeModal()
    {
        $this->openStandard = false;
        $this->openSection = false;
        $this->openSubject = false;
        $this->reset([
            'editId',
            'standardName',
            'standardCode',
            'standardBoard',
            'standardOrder',
            'sectionName',
            'sectionDescription',
            'selectedStandard',
            'subjectName',
            'subjectCode',
            'subjectDescription',
            'subjectActive',
            'selectedStandardForSubject',
            'selectedSectionsForSubject',
            'isMandatory'
        ]);
        $this->dispatch('onStandardAddUpdate');
    }

    public function saveStandard()
    {
        $rules = [
            'standardName' => 'required|string|max:255',
            'standardCode' => 'required|string|max:50',
            'standardBoard' => 'required|string',
            // 'standardOrder' => 'required|integer',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->standardName,
            'code' => $this->standardCode,
            'board' => $this->standardBoard,
            'order' => $this->standardOrder,
            'is_active' => $this->standardActive,
            'organization_id' => Auth::user()->organization_id,
        ];

        if ($this->editId) {
            $standard = StudentStandard::find($this->editId);
            $standard->update($data);
            $this->notification()->success('Standard updated successfully!');
        } else {
            StudentStandard::create($data);
            $this->notification()->success('Standard created successfully!');
        }

        $this->closeModal();
        $this->mount();
        $this->dispatch('onStandardAddUpdate');
    }

    public function saveSection()
    {
        $rules = [
            'sectionName' => 'required|string|max:255',
            'selectedStandard' => 'required|exists:standards,id',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->sectionName,
            // 'description' => $this->sectionDescription,
            'standard_id' => $this->selectedStandard,
            'is_active' => $this->sectionActive,
        ];

        if ($this->editId) {
            $section = Section::find($this->editId);
            $section->update($data);
            $this->notification()->success('Section updated successfully!');
        } else {
            Section::create($data);
            $this->notification()->success('Section created successfully!');
        }

        $this->closeModal();
        $this->activeTab = 'section';
        $this->dispatch('onStandardAddUpdate');
    }

    public function saveSubject()
    {
        $rules = [
            'subjectName' => 'required|string|max:255',
            'subjectCode' => 'required|string|max:50|unique:subjects,code,' . $this->editId,
            'selectedStandardForSubject' => 'required|exists:standards,id',
            'selectedSectionsForSubject' => 'required|array',
            'selectedSectionsForSubject.*' => 'exists:sections,id',
            'subjectImage' => 'nullable|image|max:2048', // 2MB max
            'subjectDetailImage' => 'nullable|image|max:2048', // 2MB max
        ];


        $this->validate($rules);

        $subjectData = [
            'name' => $this->subjectName,
            'code' => $this->subjectCode,
            'description' => $this->subjectDescription,
            'organization_id' => Auth::user()->organization_id,
            'is_active' => $this->subjectActive,
        ];

        if ($this->editId) {
            $subject = Subject::find($this->editId);
            $subject->update($subjectData);

            // Update standard-subject relationship
            StandardSubject::updateOrCreate(
                ['standard_id' => $this->selectedStandardForSubject, 'subject_id' => $subject->id],
                ['organization_id' => Auth::user()->organization_id, 'is_mandatory' => $this->isMandatory]
            );

            // Sync section-subject relationships
            $sectionSubjects = [];
            foreach ($this->selectedSectionsForSubject as $sectionId) {
                $sectionSubjects[] = [
                    'section_id' => $sectionId,
                    'subject_id' => $subject->id,
                    'standard_id' => $this->selectedStandardForSubject,
                    'organization_id' => Auth::user()->organization_id
                ];
            }

            SectionSubject::where('subject_id', $subject->id)->delete();
            SectionSubject::insert($sectionSubjects);

            $this->notification()->success('Subject updated successfully!');
        } else {
            $subject = Subject::create($subjectData);

            // Create standard-subject relationship
            StandardSubject::create([
                'standard_id' => $this->selectedStandardForSubject,
                'subject_id' => $subject->id,
                'organization_id' => Auth::user()->organization_id,
                'is_mandatory' => $this->isMandatory
            ]);

            // Create section-subject relationships
            $sectionSubjects = [];
            foreach ($this->selectedSectionsForSubject as $sectionId) {
                $sectionSubjects[] = [
                    'section_id' => $sectionId,
                    'subject_id' => $subject->id,
                    'standard_id' => $this->selectedStandardForSubject,
                    'organization_id' => Auth::user()->organization_id
                ];
            }

            if ($this->subjectImage) {
                // Delete old image if exists
                if ($subject->image) {
                    $oldImagePath = parse_url($subject->image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $imagePath = $this->subjectImage->store('subject-images', 's3');
                Storage::disk('s3')->setVisibility($imagePath, 'public');
                $sectionSubjects['image'] = Storage::disk('s3')->url($imagePath);
            }


            // if (!$this->editId) {
            //     $userData['password'] = Hash::make('12345678');
            // }

            $subject->update($sectionSubjects);
            SectionSubject::insert($sectionSubjects);

            $this->notification()->success('Subject created successfully!');
        }

        $this->closeModal();
        $this->dispatch('onStandardAddUpdate');
        $this->activeTab = 'subject';
    }


    public function performDeleteSubject($id)
    {
        try {
            // Delete relationships first
            StandardSubject::where('subject_id', $id)->delete();
            SectionSubject::where('subject_id', $id)->delete();

            // Then delete the subject
            Subject::find($id)->delete();

            $this->notification()->success('Subject deleted successfully!');
        } catch (\Exception $e) {
            $this->notification()->error('Failed to delete subject: ' . $e->getMessage());
        }
    }

    public function editSubject($id)
    {
        $subject = Subject::find($id);
        if ($subject) {
            $this->editId = $id;
            $this->subjectName = $subject->name;
            $this->subjectCode = $subject->code;
            $this->subjectDescription = $subject->description;
            $this->subjectActive = $subject->is_active;

            // Get standard-subject relationship
            $standardSubject = StandardSubject::where('subject_id', $id)->first();
            if ($standardSubject) {
                $this->selectedStandardForSubject = $standardSubject->standard_id;
                $this->isMandatory = $standardSubject->is_mandatory;

                // Load sections for this standard
                $this->sections = Section::where('standard_id', $standardSubject->standard_id)
                    ->where('organization_id', Auth::user()->organization_id)
                    ->orderBy('name')
                    ->get();

                // Get sections for this subject
                $sectionSubjects = SectionSubject::where('subject_id', $id)
                    ->pluck('section_id')
                    ->toArray();
                $this->selectedSectionsForSubject = $sectionSubjects;
            }

            $this->openSubject = true;
        }
    }

    public function editStandard($id)
    {
        $standard = Standard::find($id);
        if ($standard) {
            $this->editId = $id;
            $this->standardName = $standard->name;
            $this->standardCode = $standard->code;
            $this->standardBoard = $standard->board;
            $this->standardOrder = $standard->order;
            $this->standardActive = $standard->is_active;
            $this->openStandard = true;
        }
    }

    public function editSection($id)
    {
        $section = Section::find($id);
        if ($section) {
            $this->editId = $id;
            $this->sectionName = $section->name;
            $this->sectionDescription = $section->description;
            $this->selectedStandard = $section->standard_id;
            $this->sectionActive = $section->is_active;
            $this->openSection = true;
        }
    }

    public function performDeleteStandard($id)
    {
        $standard = Standard::find($id);
        if ($standard) {
            $standard->delete();
            $this->notification()->success('Standard deleted successfully!');
        }
        $this->dispatch('onStandardAddUpdate');
    }


    public function performDeleteSection($id)
    {
        $section = Section::find($id);
        if ($section) {
            $section->delete();
            $this->notification()->success('Section deleted successfully!');
        }
        $this->dispatch('onStandardAddUpdate');
    }

    private function resetStandardFields()
    {
        $this->reset(['standardName', 'standardCode', 'standardBoard', 'standardOrder']);
        $this->standardActive = true;
    }

    private function resetSectionFields()
    {
        $this->reset(['sectionName', 'sectionDescription', 'selectedStandard']);
        $this->sectionActive = true;
    }

    private function resetSubjectFields()
    {
        $this->reset([
            'subjectName',
            'subjectCode',
            'subjectDescription',
            'subjectActive',
            'selectedStandardForSubject',
            'selectedSectionsForSubject',
            'isMandatory'
        ]);
        $this->subjectActive = true;
    }

    public function onEditStandard($id)
    {
        $standard = StudentStandard::find($id);

        if (!$standard) {
            abort(404, 'Standard not found');
        }

        $this->editId = $standard->id;
        $this->standardName = $standard->name;
        $this->standardCode = $standard->code;
        $this->standardBoard = $standard->board;
        $this->standardOrder = $standard->order;
        $this->standardActive = $standard->is_active;

        $this->openStandard = true;
        $this->dispatch('onStandardAddUpdate');
    }

    public function onDeleteStandard($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this class? This action cannot be undone.',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'performDeleteStandard',
                'params' => $id,
                'color' => 'negative',
                'size' => 'md',
            ],
            'reject' => [
                'label' => 'No',
                'size' => 'md',
            ],
        ]);
    }


    public function onViewStandardAdmin($id)
    {
        $standard = StudentStandard::find($id);

        if (!$standard) {
            $this->notification()->error('Standard not found!');
            return;
        }

        $this->viewModalTitle = 'Standard Details';
        $this->activeTab = 'standard';
        $this->viewData = $standard->toArray();
        $this->showViewModal = true;
    }

    public function onEditSection($id)
    {
        $section = Section::find($id);

        if (!$section) {
            abort(404, 'Section not found');
        }

        $this->editId = $section->id;
        $this->sectionName = $section->name;
        $this->sectionDescription = $section->description;
        $this->sectionActive = $section->is_active;
        $this->selectedStandard = $section->standard_id;

        $this->openSection = true;
        $this->dispatch('onStandardAddUpdate');
    }

    public function onDeleteSection($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this section? This action cannot be undone.',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'performDeleteSection',
                'params' => $id,
                'color' => 'negative',
                'size' => 'md',
            ],
            'reject' => [
                'label' => 'No',
                'size' => 'md',
            ],
        ]);
    }


    public function onViewSectionAdmin($id)
    {
        $section = Section::with('standard')->find($id);

        if (!$section) {
            $this->notification()->error('Subject not found!');
            return;
        }

        $this->viewModalTitle = 'Section Details';
        $this->activeTab = 'section';
        $this->viewData = $section->toArray();
        $this->showViewModal = true;
    }

    public function onEditSubject($id)
    {
        $subject = Subject::with('standards')->find($id);

        if (!$subject) {
            abort(404, 'Subject not found');
        }

        $this->editId = $subject->id;
        $this->subjectName = $subject->name;
        $this->subjectCode = $subject->code;
        $this->subjectDescription = $subject->description;
        $this->subjectActive = $subject->is_active;

        $this->selectedStandardForSubject = optional($subject->standards->first())->id;

        $this->openSubject = true;
        $this->dispatch('onStandardAddUpdate');
    }


    public function onDeleteSubject($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this subject? This action cannot be undone.',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'performDeleteSubject',
                'params' => $id,
                'color' => 'negative',
                'size' => 'md',
            ],
            'reject' => [
                'label' => 'No',
                'size' => 'md',
            ],
        ]);
    }

    public function onViewSubjectAdmin($id)
    {
        $subject = Subject::with('standards')->find($id);

        if (!$subject) {
            $this->notification()->error('Subject not found!');
            return;
        }

        $this->viewModalTitle = 'Subject Details';
        $this->activeTab = 'subject';
        $this->viewData = $subject->toArray();
        $this->showViewModal = true;
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewData = [];
        $this->viewModalTitle = '';
    }



    public function render()
    {
        return view('livewire.admin.standard');
    }
}
