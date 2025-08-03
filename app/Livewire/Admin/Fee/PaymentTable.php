<?php

namespace App\Livewire\Admin\Fee;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use App\Models\Admin\Fee\PaymentTransaction;

class PaymentTable extends DataTableComponent
{
    protected $model = PaymentTransaction::class;

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
            Column::make("Transaction id", "transaction_id")
                ->searchable()->hideIf(1),
            Column::make("Student detail id", "student_detail_id")
                ->searchable(),
            Column::make("Organization id", "organization_id")
                ->searchable()->hideIf(1),
            Column::make("Amount", "amount")
                ->searchable(),
            Column::make("Tax amount", "tax_amount")
                ->searchable()->hideIf(1),
            Column::make("Convenience fee", "convenience_fee")
                ->searchable(),
            Column::make("Total amount", "total_amount")
                ->searchable(),
            Column::make("Payment mode", "payment_mode")
                ->searchable(),
            Column::make("Payment gateway", "payment_gateway")
                ->searchable()->hideIf(1),
            Column::make("Status", "status")
                ->searchable(),
            Column::make("Gateway response", "gateway_response")
                ->searchable()->hideIf(1),
            Column::make("Remarks", "remarks")
                ->searchable()->hideIf(1),
            Column::make("Admin id", "admin_id")
                ->searchable()->hideIf(1),
            Column::make("Created at", "created_at")
                ->searchable(),
            Column::make("Updated at", "updated_at")
                ->searchable()->hideIf(1),
        ];
    }
}
