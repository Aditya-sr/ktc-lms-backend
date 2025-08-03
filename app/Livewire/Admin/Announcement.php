<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Admin\Announcement as AnnouncementModel;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Storage;

class Announcement extends Component
{
    use WithPagination, WithFileUploads;

    public $open = false;
    public $editId = null;
    
    #[Rule('required|string|max:255')]
    public $announcementName = '';
    
    #[Rule('required|string')] 
    public $announcementContent = '';
    
    #[Rule('required|in:all,user,teacher')]
    public $type = 'all';
    
    #[Rule('nullable|image|max:2048')] // 2MB max
    public $announcementImage;
    
    #[Rule('nullable|mimes:pdf|max:5120')] // 5MB max
    public $announcementPdf;

    public function render()
    {
        $announcements = AnnouncementModel::latest()
            ->paginate(10);
            
        return view('livewire.admin.announcement', compact('announcements'));
    }

    public function openModal()
    {
        $this->open = true;
        $this->resetForm();
    }

    public function save()
    {
        $this->validate();
        
        $data = [
            'organization_id' => Auth::user()->organization_id,
            'user_id' => Auth::user()->id,
            'announcement_name' => $this->announcementName,
            'announcement_content' => $this->announcementContent,
            'type' => $this->type,
        ];

        // Handle image upload
        if ($this->announcementImage) {
            $imagePath = $this->announcementImage->store('announcement-images', 's3');
            Storage::disk('s3')->setVisibility($imagePath, 'public');
            $data['announcement_image'] = Storage::disk('s3')->url($imagePath);
        }

        // Handle PDF upload
        if ($this->announcementPdf) {
            $pdfPath = $this->announcementPdf->store('announcement-pdfs', 's3');
            Storage::disk('s3')->setVisibility($pdfPath, 'public');
            $data['announcement_pdf'] = Storage::disk('s3')->url($pdfPath);
        }

        if ($this->editId) {
            $announcement = AnnouncementModel::find($this->editId);
            
            // Delete old files if new ones are uploaded
            if ($this->announcementImage && $announcement->announcement_image) {
                $oldImagePath = parse_url($announcement->announcement_image, PHP_URL_PATH);
                Storage::disk('s3')->delete($oldImagePath);
            }
            
            if ($this->announcementPdf && $announcement->announcement_pdf) {
                $oldPdfPath = parse_url($announcement->announcement_pdf, PHP_URL_PATH);
                Storage::disk('s3')->delete($oldPdfPath);
            }
            
            $announcement->update($data);
            $message = 'Announcement updated successfully!';
        } else {
            AnnouncementModel::create($data);
            $message = 'Announcement created successfully!';
        }

        $this->closeModal();
        $this->dispatch('notify', type: 'success', message: $message);
    }

    public function edit($id)
    {
        $announcement = AnnouncementModel::findOrFail($id);
        $this->editId = $id;
        $this->announcementName = $announcement->announcement_name;
        $this->announcementContent = $announcement->announcement_content;
        $this->type = $announcement->type;
        $this->open = true;
    }

    public function deleteFile($type)
    {
        if ($type === 'image') {
            if ($this->editId && $this->announcementImage) {
                $this->announcementImage = null;
            } elseif ($this->editId) {
                $announcement = AnnouncementModel::find($this->editId);
                if ($announcement->announcement_image) {
                    $oldImagePath = parse_url($announcement->announcement_image, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldImagePath);
                    $announcement->update(['announcement_image' => null]);
                }
            }
        } elseif ($type === 'pdf') {
            if ($this->editId && $this->announcementPdf) {
                $this->announcementPdf = null;
            } elseif ($this->editId) {
                $announcement = AnnouncementModel::find($this->editId);
                if ($announcement->announcement_pdf) {
                    $oldPdfPath = parse_url($announcement->announcement_pdf, PHP_URL_PATH);
                    Storage::disk('s3')->delete($oldPdfPath);
                    $announcement->update(['announcement_pdf' => null]);
                }
            }
        }
    }

    public function closeModal()
    {
        $this->open = false;
        $this->resetForm();
    }

    protected function resetForm()
    {
        $this->reset([
            'editId', 
            'announcementName', 
            'announcementContent', 
            'type',
            'announcementImage',
            'announcementPdf'
        ]);
        $this->resetErrorBag();
    }
}