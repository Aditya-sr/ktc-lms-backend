<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Teacher\TeacherDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;

class TeacherTable extends DataTableComponent
{
    protected $model = TeacherDetail::class;
    protected $listeners = ['onUserAddUpdate' => '$refresh'];

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
            Column::make("User id", "user_id")
                ->searchable(),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Employee id", "employee_id")
                ->searchable(),
            Column::make("Date of joining", "date_of_joining")
                ->searchable(),
            Column::make("Qualification", "qualification")
                ->searchable(),
            Column::make("Phone", "phone")
                ->searchable(),
            Column::make("Address", "address")
                ->searchable(),
            Column::make("City", "city")
                ->searchable(),
            Column::make("State", "state")
                ->searchable(),
            Column::make("Pincode", "pincode")
                ->searchable()->hideIf(1),
            Column::make("Emergency contact", "emergency_contact")
                ->searchable(),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
            BooleanColumn::make("Active", "user.is_active"),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onViewTeacherAdmin',
                    'editEvent' => 'onEditTeacher',
                    'deleteEvent' => 'onDeleteTeacher',
                ])

        ];
    }

    public function builder(): Builder
    {
        return TeacherDetail::query()
            ->join('users', 'teacher_details.user_id', '=', 'users.id')
            ->where('users.organization_id', Auth::user()->organization_id)
            ->select('teacher_details.*');
    }
}
