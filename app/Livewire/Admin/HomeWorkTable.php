<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\HomeWork;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class HomeWorkTable extends DataTableComponent
{
    protected $model = HomeWork::class;

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
            Column::make("Assign By", "user.name")
                ->searchable(),
            Column::make("Class", "standard.name")
                ->searchable(),
            Column::make("Section", "section.name")
                ->searchable(),
            Column::make("Subject", "subject.name")
                ->searchable(),
            Column::make("Title", "title")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable(),
            Column::make("Date", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        return HomeWork::with(['user', 'standard', 'section', 'subject'])
            ->where('home_works.organization_id', Auth::user()->organization_id);
    }
}
