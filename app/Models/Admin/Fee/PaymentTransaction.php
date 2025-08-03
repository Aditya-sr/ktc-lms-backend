<?php

namespace App\Models\Admin\Fee;

use App\Models\Student\StudentDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'student_detail_id',
        'organization_id',
        'amount',
        'tax_amount',
        'convenience_fee',
        'total_amount',
        'payment_mode',
        'payment_gateway',
        'status',
        'gateway_response',
        'remarks',
        'admin_id'
    ];

    protected $casts = [
        'gateway_response' => 'array',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'convenience_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function allocations()
    {
        return $this->hasMany(PaymentAllocation::class);
    }

    public function allocatePayment(FeeInstallment $installment, $amount)
    {
        $remaining = $installment->amount - $installment->paid_amount;
        $allocatedAmount = min($amount, $remaining);

        $this->allocations()->create([
            'fee_installment_id' => $installment->id,
            'amount' => $allocatedAmount
        ]);

        $installment->increment('paid_amount', $allocatedAmount);

        if ($installment->paid_amount >= $installment->amount) {
            $installment->status = 'paid';
            $installment->paid_date = now();
        } else {
            $installment->status = 'partial';
        }

        $installment->save();

        return $allocatedAmount;
    }
}
