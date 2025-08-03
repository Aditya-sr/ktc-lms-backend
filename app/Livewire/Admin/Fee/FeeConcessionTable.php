<?php

namespace App\Livewire\Admin\Fee;

use App\Helpers\StyleConstants;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Fee\FeeConcession;

class FeeConcessionTable extends DataTableComponent
{
    protected $model = FeeConcession::class;

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
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Code", "code")
                ->searchable(),
            Column::make("Amount", "amount")
                ->searchable(),
            Column::make("Type", "type")
                ->searchable(),
            Column::make("Is active", "is_active")
                ->searchable(),
            Column::make("Description", "description")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Eligibility criteria", "eligibility_criteria")
                ->searchable(),
            Column::make("Created at", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
