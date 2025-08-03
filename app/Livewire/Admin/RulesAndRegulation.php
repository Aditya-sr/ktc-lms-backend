<?php

namespace App\Livewire\Admin;

use App\Models\Admin\RulesAndRegulation as AdminRulesAndRegulation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class RulesAndRegulation extends Component
{
    use WireUiActions;

    public $activeTab = 'create';

    public function showTab($tab)
    {
        $this->activeTab = $tab;
    }

    public $content = '';
    public $organizationId;
    public $organizations;
    public $existingContent = null;

    protected $rules = [
        'content' => 'required',
        'organizationId' => 'required|exists:organizations,id'
    ];

    public function mount()
    {
        $this->organizationId = Auth::user()->organization_id;
        $this->loadContent();
    }

    public function loadContent()
    {
        $this->existingContent = AdminRulesAndRegulation::where('organization_id', $this->organizationId)
            ->first();

        $this->content = $this->existingContent?->content['html'] ?? '';
    }

    public function updatedOrganizationId()
    {
        $this->loadContent();
    }

    public function saveContent()
    {
        $this->validate();

        $data = [
            'organization_id' => $this->organizationId,
            'content' => [
                'html' => $this->content,
                'last_updated' => now()->toDateTimeString()
            ]
        ];

        if ($this->existingContent) {
            $this->existingContent->update($data);
            $this->notification()->success('Rules updated successfully!');
        } else {
            AdminRulesAndRegulation::create($data);
            $this->notification()->success('Rules created successfully!');
        }

        $this->loadContent();
    }

    public function render()
    {
        return view('livewire.admin.rules-and-regulation');
    }
}
