<?php

namespace App\Models\Admin\Fee;

use Illuminate\Database\Eloquent\Model;

class FeeInstallment extends Model
{
    protected $fillable = [
        'fee_assignment_id',
        'organization_id',
        'installment_number',
        'amount',
        'paid_amount',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
        'transaction_reference',
        'remarks',
        'metadata'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_date' => 'date',
        'metadata' => 'array',
    ];

    public function assignment()
    {
        return $this->belongsTo(FeeAssignment::class, 'fee_assignment_id');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->paid_amount;
    }

    public function markAsPaid($paymentData)
    {
        $this->update([
            'paid_amount' => $this->amount,
            'paid_date' => now(),
            'status' => 'paid',
            'payment_method' => $paymentData['method'],
            'transaction_reference' => $paymentData['reference'],
            'remarks' => $paymentData['remarks'] ?? null,
        ]);

        $this->assignment->updateStatus();
    }
}
