<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\ExamCopy;

class ExamCopyTable extends DataTableComponent
{
    protected $model = ExamCopy::class;

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
            Column::make("User id", "user_id")
                ->searchable()->hideIf(1),
            Column::make("Student", "student_detail_id")
                ->searchable(),
            Column::make("Standard", "standard_id")
                ->searchable(),
            Column::make("Section", "section_id")
                ->searchable(),
            Column::make("Subject", "subject_id")
                ->searchable(),
            Column::make("Teacher", "teacher_detail_id")
                ->searchable(),
            Column::make("Exam", "exam_id")
                ->searchable(),
            Column::make("Marks obtained", "marks_obtained")
                ->searchable()->hideIf(1),
            Column::make("Max marks", "max_marks")
                ->searchable()->hideIf(1),
            Column::make("Percentage", "percentage")
                ->searchable()->hideIf(1),
            Column::make("Grade", "grade")
                ->searchable()->hideIf(1),
            Column::make("Remarks", "remarks")
                ->searchable()->hideIf(1),
            Column::make("absent", "is_absent")
                ->searchable(),
            Column::make("recheck", "is_recheck")
                ->searchable(),
            Column::make("Breakup", "breakup")
                ->searchable()->hideIf(1),
            Column::make("Date", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
