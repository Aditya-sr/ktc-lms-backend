<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Fee extends Component
{
    public $activeTab = 'fee';
    public $subTab = 'type';

    public $tabs = [
        'fee' => [
            'type' => 'Fee Type',
            'cycle' => 'Fee Cycles',
            'concession' => 'Fee Concession',
        ],

        'fee_template' => [
            'template' => 'Template',
            'template_item' => 'Templates Items',
        ],
        'fee_assignment' => [
            'assign' => 'Assign Fees',
            'installment' => 'Fees Installment'
        ],
        'payment_transaction' => [
            'record' => 'Payment',
            'allocation' => 'Payment Allocation'
        ]
    ];

    public function showTab($tab)
    {
        $this->activeTab = $tab;
        $this->subTab = array_key_first($this->tabs[$tab]);
    }

    public function setSubTab($subTab)
    {
        $this->subTab = $subTab;
    }

    public function render()
    {
        return view('livewire.admin.fee');
    }
}
