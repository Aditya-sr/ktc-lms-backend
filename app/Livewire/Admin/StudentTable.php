<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use App\Models\Student\StudentDetail;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;

class StudentTable extends DataTableComponent
{
    protected $model = StudentDetail::class;
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
            Column::make("Name", "full_name")
                ->searchable(),
            Column::make("Email", "email")
                ->searchable(),
            Column::make("Mob number", "phone")
                ->searchable(),
            Column::make("Roll no", "roll_no")
                ->searchable()->sortable(),
            Column::make("Admission no", "admission_no")
                ->searchable()->sortable(),
            Column::make("Mob number", "phone")
                ->searchable(),
            BooleanColumn::make("Active", "user.is_active")
                ->searchable(),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onViewStudentAdmin',
                    'editEvent' => 'onEditStudent',
                    'deleteEvent' => 'onDeleteStudent',
                ])
        ];
    }

    public function builder(): Builder
    {
        return StudentDetail::query()
            ->with(['user'])
            ->where('user.organization_id', Auth::user()->organization_id);
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Active Status', 'is_active')
                ->options([
                    '' => 'All',
                    '1' => 'Active',
                    '0' => 'Inactive',
                ])
                ->filter(function (Builder $builder, string $value) {
                    if ($value === '1') {
                        $builder->whereHas('user', function ($query) {
                            $query->where('is_active', true);
                        });
                    } elseif ($value === '0') {
                        $builder->whereHas('user', function ($query) {
                            $query->where('is_active', false);
                        });
                    }
                })
        ];

        return [];
    }
}
