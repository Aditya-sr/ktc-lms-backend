<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Exam;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;

class ExamTable extends DataTableComponent
{
    protected $model = Exam::class;

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
            Column::make("Exam name", "exam_name")
                ->searchable(),
            Column::make("Academic year", "academic_year")
                ->searchable(),
            Column::make("Start date", "start_date")
                ->searchable(),
            Column::make("End date", "end_date")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable()->hideIf(1),
            BooleanColumn::make("Published", "is_published")
                ->searchable(),
            Column::make("Exam type", "exam_type")
                ->searchable(),
            Column::make("Total marks", "total_marks")
                ->searchable(),
            Column::make("Passing marks", "passing_marks")
                ->searchable(),
            Column::make("Created by", "created_by")
                ->searchable(),
            Column::make("Updated by", "updated_by")
                ->searchable(),
            Column::make("Status", "status")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
