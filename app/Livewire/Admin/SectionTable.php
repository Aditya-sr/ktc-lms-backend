<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Student\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class SectionTable extends DataTableComponent
{
    protected $model = Section::class;
    protected $listeners = ['onStandardAddUpdate' => '$refresh'];

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
        $this->setTrAttributes(function ($row) {
            return [
                'class' => 'cursor-pointer group',
                'wire:click' => "\$dispatch('onViewSectionAdmin', {id: {$row->id}})",
            ];
        });
        $this->setSearchFieldAttributes(StyleConstants::SEARCH);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make('Standard ID', 'standard_id')->hideIf(true),
            Column::make('Standard Name')
                ->label(fn($row) => $row->standard->name ?? '❌'),
            Column::make("Section Name", "name")
                ->searchable(),
            Column::make("Image", "image")
                ->searchable()->hideIf(1),
            // Column::make("Description", "description")
            //     ->searchable(),
            BooleanColumn::make("active", "is_active")
                ->searchable(),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onViewSectionAdmin',
                    'editEvent' => 'onEditSection',
                    'deleteEvent' => 'onDeleteSection',
                ])
        ];
    }

    public function builder(): Builder
    {
        return Section::query()
            ->whereHas('standard', function ($query) {
                $query->where('organization_id', Auth::user()->organization_id);
            });
    }
}
