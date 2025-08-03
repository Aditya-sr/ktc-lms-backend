<?php

namespace App\Livewire\Admin;

use App\Helpers\Constants;
use App\Models\Admin\Library as AdminLibrary;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use WireUi\Traits\WireUiActions;

class Library extends Component
{
    use WireUiActions, WithFileUploads;

    public $open = false;
    public $showViewModal = false;
    public $editId = null;
    public $libraries = [];
    public $title = '';
    public $author = '';
    public $publisher = '';
    public $publication_year = '';
    public $isbn = '';
    public $edition = '';
    public $category = '';
    public $description = '';
    public $language = 'Hindi';
    public $pages = '';
    public $coverImage;
    public $file;
    public $type = 'book';
    public $availability = 'available';
    public $location_id = '';
    public $tempCoverUrl = null;
    public $tempFileUrl = null;
    public $categories = null;
    public $types = null;
    public $availabilities = null;
    public $languages = null;
    public $viewModalTitle = '';
    public $viewData = [];
    public $activeTab = 'library';
    public $libraryImageUrl;


    protected $listeners = ['onViewLibraryAdmin','onEditLibrary', 'onDeleteLibrary'];

    public function mount()
    {
        $this->categories = Constants::LIBRARY_CATEGORIES;
        $this->types = Constants::LIBRARY_TYPES;
        $this->availabilities = Constants::LIBRARY_AVAILABILITY;
        $this->languages = Constants::LANGUAGES;
    }

    public function updatedCoverImage()
    {
        $this->validate([
            'coverImage' => 'image|max:2048',
        ]);
        $this->tempCoverUrl = $this->coverImage->temporaryUrl();
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'file|mimes:pdf|max:5120',
        ]);
        $this->tempFileUrl = $this->file->getClientOriginalName();
    }

    public function onAddLibrary()
    {
        $this->open = true;
    }

    public function closeModal()
    {
        $this->open = false;
        $this->resetForm();
        $this->dispatch('refresh-library-list');
    }

    public function onSave()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'isbn' => 'nullable|string|unique:libraries,isbn,' . $this->editId,
            'edition' => 'nullable|string',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'language' => 'required|string',
            'pages' => 'nullable|integer|min:1',
            'coverImage' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf|max:5120',
            'type' => 'required|in:book,journal,thesis,ebook,other',
            'availability' => 'required|in:available,checked_out,lost,reserved',
            'location_id' => 'nullable|exists:library_locations,id'
        ]);

        try {
            $data = [
                'title' => $this->title,
                'author' => $this->author,
                'publisher' => $this->publisher,
                'publication_year' => $this->publication_year,
                'isbn' => $this->isbn,
                'edition' => $this->edition,
                'category' => $this->category,
                'description' => $this->description,
                'language' => $this->language,
                'pages' => $this->pages,
                'type' => $this->type,
                'availability' => $this->availability,
                'organization_id' => Auth::user()->organization_id,
                'user_id' => Auth::user()->id
            ];

            if ($this->editId) {
                $library = AdminLibrary::findOrFail($this->editId);

                // Handle cover image update
                if ($this->coverImage) {
                    // Delete old image if exists
                    if ($library->cover_image) {
                        $oldImagePath = parse_url($library->cover_image, PHP_URL_PATH);
                        Storage::disk('s3')->delete($oldImagePath);
                    }

                    $imagePath = $this->coverImage->store('library/covers', 's3');
                    Storage::disk('s3')->setVisibility($imagePath, 'public');
                    $data['cover_image'] = Storage::disk('s3')->url($imagePath);
                }

                // Handle file update
                if ($this->file) {
                    // Delete old file if exists
                    if ($library->file_path) {
                        $oldFilePath = parse_url($library->file_path, PHP_URL_PATH);
                        Storage::disk('s3')->delete($oldFilePath);
                    }

                    $filePath = $this->file->store('library/files', 's3');
                    Storage::disk('s3')->setVisibility($filePath, 'public');
                    $data['file_path'] = Storage::disk('s3')->url($filePath);
                }

                $library->update($data);
                $this->notification()->success('Library item updated successfully!');
            } else {
                // Handle new cover image
                if ($this->coverImage) {
                    $imagePath = $this->coverImage->store('library/covers', 's3');
                    Storage::disk('s3')->setVisibility($imagePath, 'public');
                    $data['cover_image'] = Storage::disk('s3')->url($imagePath);
                }

                // Handle new file
                if ($this->file) {
                    $filePath = $this->file->store('library/files', 's3');
                    Storage::disk('s3')->setVisibility($filePath, 'public');
                    $data['file_path'] = Storage::disk('s3')->url($filePath);
                }

                AdminLibrary::create($data);
                $this->notification()->success('Library item added successfully!');
            }

            $this->closeModal();
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error Saving Library Item',
                $e->getMessage()
            );
            logger()->error('Library save error: ' . $e->getMessage());
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'editId',
            'title',
            'author',
            'publisher',
            'publication_year',
            'isbn',
            'edition',
            'category',
            'description',
            'language',
            'pages',
            'coverImage',
            'file',
            'type',
            'availability',
            'tempCoverUrl',
            'tempFileUrl'
        ]);
        $this->resetErrorBag();
    }

   public function onEditLibrary($id)
    {
        $library = AdminLibrary::findOrFail($id); // ✅ use correct model alias

        $this->editId = $library->id;
        $this->title = $library->title;
        $this->author = $library->author;
        $this->publisher = $library->publisher;
        $this->publication_year = $library->publication_year;
        $this->isbn = $library->isbn;
        $this->edition = $library->edition;
        $this->category = $library->category;
        $this->description = $library->description;
        $this->language = $library->language;
        $this->pages = $library->pages;
        $this->type = $library->type;
        $this->availability = $library->availability;

        $this->open = true;
    }

    public function onDeleteLibrary($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'icon' => 'exclamation-circle',
            'iconColor' => 'text-red-500',
            'description' => 'Are you sure you want to delete this library item? The action cannot be undone.',
            'accept' => [
                'label' => 'Yes, delete it',
                'method' => 'doDeleteLibrary',
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

    public function doDeleteLibrary($id)
    {
        try {
            $library = AdminLibrary::findOrFail($id);

            // Delete cover image if exists
            if ($library->cover_image) {
                $imagePath = parse_url($library->cover_image, PHP_URL_PATH);
                Storage::disk('s3')->delete($imagePath);
            }

            // Delete file if exists
            if ($library->file_path) {
                $filePath = parse_url($library->file_path, PHP_URL_PATH);
                Storage::disk('s3')->delete($filePath);
            }

            $library->delete();

            $this->notification()->success('Library item deleted successfully!');
            $this->dispatch('refresh-library-list');
        } catch (\Exception $e) {
            $this->notification()->error(
                'Error Deleting Library Item',
                $e->getMessage()
            );
        }
    }

     public function onViewLibraryAdmin($id)
    {
        $libraryDetail = AdminLibrary::find($id);

        if (!$libraryDetail) {
            $this->notification()->error('Library item not found!');
            return;
        }

        $this->libraryImageUrl = $libraryDetail->cover_image;
        $this->viewModalTitle = 'Library Details';
        $this->activeTab = 'library';

        $this->viewData = [
            'user' => $libraryDetail->user,
            'detail' => $libraryDetail,
        ];

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
        return view('livewire.admin.library');
    }
}
