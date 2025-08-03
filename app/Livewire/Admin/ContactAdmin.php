<?php

namespace App\Livewire\Admin;

use App\Models\Admin\ContactSuperAdmin;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ContactAdmin extends Component
{
    use WireUiActions, WithFileUploads;

    public $showViewModal = false;
    public $open = false;
    public $openImage = false;
    public $imagePath = null;
    public $editId = null;
    public $viewModalTitle = '';
    public $viewData = [];

    // Form fields
    public $topic = '';
    public $admin_query = '';
    public $image;
    public $super_admin_reply = '';

    // For viewing replies
    public $contacts = [];
    public $organization;

    protected $listeners = ['onViewContact', 'onDeleteContact', 'onEditContact'];

    public function mount()
    {
        $this->organization = Organization::find(Auth::user()->organization_id);
        $this->loadContacts();
    }

    public function loadContacts()
    {
        $this->contacts = ContactSuperAdmin::with(['user', 'organization'])
            ->where('organization_id', Auth::user()->organization_id)
            ->latest()
            ->get();
    }

    public function onAddContact()
    {
        $this->open = true;
        $this->resetForm();
    }

    public function closeModal()
    {
        $this->open = false;
        $this->resetForm();
    }

    public function onSave()
    {
        $this->validate([
            'topic' => 'required|string|max:255',
            'admin_query' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $contact = $this->editId ? ContactSuperAdmin::find($this->editId) : new ContactSuperAdmin();

            $data = [
                'user_id' => Auth::id(),
                'organization_id' => Auth::user()->organization_id,
                'topic' => $this->topic,
                'admin_query' => $this->admin_query,
            ];

            // Handle image upload
            if ($this->image) {
                // Delete old image if exists
                if ($contact->image) {
                    $oldImagePath = parse_url($contact->image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                }

                $imagePath = $this->image->store('contact-images', 's3');
                Storage::disk('s3')->setVisibility($imagePath, 'public');
                $data['image'] = Storage::disk('s3')->url($imagePath);
            }

            $contact->fill($data);
            $contact->save();

            $this->notification()->success(
                $this->editId ? 'Message updated successfully!' : 'Message sent to Super Admin successfully!'
            );
            $this->loadContacts();
            $this->closeModal();
            $this->dispatch('onSuperAdminContactAdded');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error saving message',
                $e->getMessage()
            );
        }
    }

    public function onViewContact($id)
    {
        $contact = ContactSuperAdmin::with(['user', 'organization'])->find($id);

        if (!$contact) {
            $this->notification()->error('Contact message not found!');
            return;
        }

        $this->viewModalTitle = 'Message Details';
        $this->viewData = [
            'contact' => $contact,
            'organization' => $contact->organization,
            'user' => $contact->user,
        ];

        $this->showViewModal = true;
    }

    public function onDeleteContact($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this message?',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'doDeleteContact',
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

    public function doDeleteContact($id)
    {
        $contact = ContactSuperAdmin::find($id);

        if ($contact) {
            // Delete image if exists
            if ($contact->image) {
                $oldImagePath = parse_url($contact->image, PHP_URL_PATH);
                Storage::disk('s3')->delete($oldImagePath);
            }

            $contact->delete();
            $this->dispatch('onSuperAdminContactAdded');
            $this->notification()->success('Message deleted successfully!');
            $this->loadContacts();
        } else {
            $this->notification()->error('Message not found!');
        }
    }

    public function closeViewModal()
    {
        $this->showViewModal = false;
        $this->viewData = [];
    }

    public function onEditContact($id)
    {
        $contact = ContactSuperAdmin::find($id);

        if (!$contact) {
            $this->notification()->error('Contact message not found!');
            return;
        }

        $this->editId = $contact->id;
        $this->topic = $contact->topic;
        $this->admin_query = $contact->admin_query;
        $this->imagePath = $contact->image;

        $this->open = true;
    }

    protected function resetForm()
    {
        $this->reset([
            'topic',
            'admin_query',
            'image',
            'editId'
        ]);
    }

    public function render()
    {
        return view('livewire.admin.contact-admin');
    }
}
