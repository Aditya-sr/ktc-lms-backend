<?php

namespace App\Livewire\Admin\Fee;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Fee\FeeHead;

class FeeTypeTable extends DataTableComponent
{
    protected $model = FeeHead::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->searchable()->hideIf(1),
            Column::make("Name", "name")
                ->searchable(),
            Column::make("Code", "code")
                ->searchable()->hideIf(1),
            Column::make("Is active", "is_active")
                ->searchable(),
            Column::make("Type", "type")
                ->searchable(),
            Column::make("Is refundable", "is_refundable")
                ->searchable(),
            Column::make("Is taxable", "is_taxable")
                ->searchable()->hideIf(1),
            Column::make("Organization id", "organization_id")
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
