<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Student\Subject;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class SubjectTable extends DataTableComponent
{
    protected $model = Subject::class;
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
                'wire:click' => "\$dispatch('onViewSubjectAdmin', {id: {$row->id}})",
            ];
        });

        $this->setSearchFieldAttributes(StyleConstants::SEARCH);
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Code", "code")
                ->searchable(),
            Column::make("Organization id", "organization_id")
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
                    'viewEvent' => 'onViewSubjectAdmin',
                    'editEvent' => 'onEditSubject',
                    'deleteEvent' => 'onDeleteSubject',
                ])
        ];
    }


    public function builder(): Builder
    {
        return Subject::query()
            ->whereHas('standards', function ($query) {
                $query->where('standards.organization_id', Auth::user()->organization_id);
            });
    }
}
