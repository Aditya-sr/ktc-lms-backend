<?php

namespace App\Livewire\Admin\Fee;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Fee\FeeAssignment;

class FeeAssignTable extends DataTableComponent
{
    protected $model = FeeAssignment::class;

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
            Column::make("Student detail", "student_detail_id")
                ->searchable(),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Fee template", "fee_template_id")
                ->searchable()->hideIf(1),
            Column::make("Academic session", "academic_session")
                ->searchable(),
            Column::make("Total amount", "total_amount")
                ->searchable(),
            Column::make("Concession amount", "concession_amount")
                ->searchable(),
            Column::make("Net amount", "net_amount")
                ->searchable(),
            Column::make("Status", "status")
                ->searchable(),
            Column::make("Assign date", "assign_date")
                ->searchable(),
            Column::make("Due date", "due_date")
                ->searchable(),
            Column::make("Notes", "notes")
                ->searchable(),
            Column::make("Admin id", "admin_id")
                ->searchable()->hideIf(1),
            Column::make("Metadata", "metadata")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
