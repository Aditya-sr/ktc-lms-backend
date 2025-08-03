<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Library;
use App\Models\Admin\Library as AdminLibrary;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class LibraryTable extends DataTableComponent
{
    protected $model = Library::class;
    protected $listeners = ['onUserAddUpdate' => '$refresh'];


    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setThAttributes(function ($column) {
            return ['default' => false, 'class' => 'px-4 py-3 text-left text-xs font-medium whitespace-normal text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400'];
        });

        $this->setTdAttributes(function ($column, $row, $colIndex) {
            $classes = ['px-4 py-4 whitespace-normal font-medium dark:text-white text-xs'];

            return [
                'default' => false,
                'class' => implode(' ', $classes)
            ];
        });
        $this->setSearchFieldAttributes(StyleConstants::SEARCH);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Title", "title")
                ->searchable(),
            Column::make("Author", "author")
                ->searchable(),
            Column::make("Publisher", "publisher")
                ->searchable(),
            Column::make("year", "publication_year")
                ->searchable(),
            Column::make("Isbn", "isbn")
                ->searchable()->hideIf(1),
            Column::make("Edition", "edition")
                ->searchable(),
            Column::make("Category", "category")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable()->hideIf(1),
            Column::make("Language", "language")
                ->searchable(),
            Column::make("Pages", "pages")
                ->searchable()->hideIf(1),
            Column::make("Cover image", "cover_image")
                ->searchable()->hideIf(1),
            Column::make("File path", "file_path")
                ->searchable()->hideIf(1),
            Column::make("Type", "type")
                ->searchable(),
            Column::make("Availability", "availability")
                ->searchable(),
            Column::make("User id", "user_id")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onViewLibraryAdmin',
                    'editEvent' => 'onEditLibrary',
                    'deleteEvent' => 'onDeleteLibrary',
                ])

        ];
    }


    public function builder(): Builder
    {
        return AdminLibrary::query()
            ->where('organization_id', Auth::user()->organization_id);
    }
}
