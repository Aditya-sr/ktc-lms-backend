<?php

namespace App\Livewire\Admin;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\AdminEnquiry;
use Rappasoft\LaravelLivewireTables\Views\Columns\DateColumn;

class EnquiryTable extends DataTableComponent
{
    protected $model = AdminEnquiry::class;

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
            Column::make("Full name", "full_name")
                ->searchable(),
            Column::make("Type", "type")
                ->searchable(),
            Column::make("Email", "email")
                ->searchable(),
            Column::make("Mobile number", "mobile_number")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable(),
            DateColumn::make('Date', 'created_at')
                ->inputFormat('Y-m-d H:i:s')
                ->outputFormat('Y-m-d'),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
