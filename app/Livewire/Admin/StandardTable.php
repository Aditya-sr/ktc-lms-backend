<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Student\Standard;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class StandardTable extends DataTableComponent
{
    protected $model = Standard::class;
    protected $listeners = ['onStandardAddUpdate' => '$refresh'];

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setTrAttributes(function ($row) {
                return [
                    'class' => 'cursor-pointer group',
                    'wire:click' => "\$dispatch('onViewStandardAdmin', {id: {$row->id}})",
                ];
            })
            ->setThAttributes(function ($column) {
                return [
                    'default' => false,
                    'class' => 'px-4 py-3 text-left text-xs font-medium whitespace-normal text-gray-500 uppercase tracking-wider dark:bg-gray-800 dark:text-gray-400'
                ];
            })
            ->setTdAttributes(function ($column, $row, $colIndex) {
                return [
                    'default' => false,
                    'class' => 'px-4 py-4 whitespace-normal font-medium dark:text-white text-xs'
                ];
            })
            ->setSearchFieldAttributes(StyleConstants::SEARCH);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Class name", "name")
                ->searchable(),
            Column::make('Sections / Subjects')
                ->label(function ($row) {
                    $sections = $row->sections->pluck('name')->map(fn($name) => "($name)")->implode(', ');
                    $subjects = $row->subjects->pluck('name')->implode(', ');
                    return $sections . ' | ' . $subjects;
                }),

            Column::make("Code", "code")
                ->searchable(),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("File path", "file_path")
                ->searchable()->hideIf(1),
            Column::make("Board", "board")
                ->searchable(),
            Column::make("Order", "order")
                ->searchable(),
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
                    'viewEvent' => 'onViewStandardAdmin',
                    'editEvent' => 'onEditStandard',
                    'deleteEvent' => 'onDeleteStandard',
                ])
        ];
    }

    public function builder(): Builder
    {
        return Standard::with(['sections', 'subjects'])
            ->where('organization_id', Auth::user()->organization_id);
    }
}
