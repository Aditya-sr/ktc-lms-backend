<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Student\Chapter;

class ContentTable extends DataTableComponent
{
    protected $model = Chapter::class;

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
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Standard id", "standard_id")
                ->searchable(),
            Column::make("Section id", "section_id")
                ->searchable(),
            Column::make("User id", "user_id")
                ->searchable(),
            Column::make("Subject id", "subject_id")
                ->searchable(),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Image path", "image_path")
                ->searchable()->hideIf(1),
            Column::make("Pdf path", "pdf_path")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable(),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
