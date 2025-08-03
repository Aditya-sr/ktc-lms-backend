<?php

namespace App\Livewire\Admin\Fee;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Fee\PaymentAllocation;

class PaymentAllocationTable extends DataTableComponent
{
    protected $model = PaymentAllocation::class;

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
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Payment transaction id", "payment_transaction_id")
                ->searchable(),
            Column::make("Fee installment id", "fee_installment_id")
                ->searchable(),
            Column::make("Amount", "amount")
                ->searchable(),
            Column::make("Created at", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
