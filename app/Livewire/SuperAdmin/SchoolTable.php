<?php

namespace App\Livewire\SuperAdmin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use App\Models\Organization;
use Illuminate\Support\Facades\Log;

class SchoolTable extends DataTableComponent
{
    protected $model = Organization::class;
    protected $listeners = ['refreshSchools' => '$refresh'];

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
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->sortable()->hideIf(1),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Email", "email")
                ->searchable(),
            Column::make("Mobile", "mobile_number")
                ->searchable(),
            Column::make("School Code", "school_code")
                ->searchable(),
            Column::make("Status")
                ->label(fn($row) => $row->status ? 'Active' : 'Inactive')
                ->html(),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onView',
                    'editEvent' => 'editSchool',
                    'deleteConfirmEvent' => 'onDelete',
                    'deleteEvent' => 'onDeleteConfirmed',
                ])

        ];
    }
}
