<?php

namespace App\Livewire\Admin\Teacher;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Teacher\AssignTeacherStandard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class AssignTeacherTable extends DataTableComponent
{
    protected $model = AssignTeacherStandard::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Teacher name", "teacher.user.name")
                ->searchable(),
            Column::make("Teacher email", "teacher.user.email")
                ->searchable(),
            Column::make("Standard Name", "standard.name")
                ->searchable(),
            Column::make("Section", "section.name")
                ->searchable(),
            DateColumn::make("Date", "created_at")
                ->searchable(),
            DateColumn::make("Update", "updated_at")
                ->searchable(),
        ];
    }

    public function builder(): Builder
    {
        return AssignTeacherStandard::query()
            ->with(['teacher', 'standard', 'section'])
            ->where('assign_teacher_standards.organization_id', Auth::user()->organization_id);
    }
}
