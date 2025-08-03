<?php

namespace App\Models\Admin\Fee;

use Illuminate\Database\Eloquent\Model;

class PaymentAllocation extends Model
{
    protected $fillable = ['organization_id', 'payment_transaction_id', 'fee_installment_id', 'amount'];
}
