<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\ContactSuperAdmin;
use Rappasoft\LaravelLivewireTables\Views\Columns\BooleanColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\ComponentColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class SuperAdminContactTable extends DataTableComponent
{
    protected $model = ContactSuperAdmin::class;
    protected $listeners = ['onSuperAdminContactAdded' => '$refresh'];


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
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Topic", "topic")
                ->searchable(),
            Column::make("Admin query", "admin_query")
                ->searchable(),
            Column::make("Image", "image")
                ->searchable()->hideIf(1),
            Column::make("Super admin text", "super_admin_text")
                ->searchable()->hideIf(1),
            BooleanColumn::make("Super admin reply", "super_admin_reply")
                ->searchable(),
            DateColumn::make("Date", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
            ComponentColumn::make('Actions', 'id')
                ->component('actions')
                ->excludeFromColumnSelect()
                ->attributes(fn($value, $row, Column $column) => [
                    'schoolId' => $row->id,
                    'viewEvent' => 'onViewContact',
                    'editEvent' => 'onEditContact',
                    'deleteEvent' => 'onDeleteContact',
                ])
        ];
    }
}
