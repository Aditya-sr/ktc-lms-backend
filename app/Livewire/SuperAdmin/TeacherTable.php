<?php

namespace App\Livewire\SuperAdmin;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class TeacherTable extends DataTableComponent
{
    protected $model = User::class;

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
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Email", "email")
                ->searchable(),
            Column::make("Mobile number", "mobile_number")
                ->searchable(),
            Column::make("Otp", "otp")
                ->searchable()->hideIf(1),
            Column::make("Is active", "is_active")
                ->searchable(),
            Column::make("Image", "image")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable(),
            Column::make("Role", "role")
                ->searchable(),
            Column::make("Otp expires at", "otp_expires_at")
                ->searchable()->hideIf(1),
            Column::make("Otp order id", "otp_order_id")
                ->searchable()->hideIf(1),
            Column::make("Education board", "education_board")
                ->searchable()->hideIf(1),
            Column::make("School code", "school_code")
                ->searchable(),
            Column::make("Serial number", "serial_number")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable()->hideIf(1),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }

    public function builder(): Builder
    {
        return User::query()->where('role', 'teacher');
    }
}
