<?php

namespace App\Models\Admin\Fee;

use App\Models\Student\StudentDetail;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class FeeAssignment extends Model
{
    protected $fillable = [
        'student_detail_id',
        'organization_id',
        'fee_template_id',
        'academic_session',
        'total_amount',
        'concession_amount',
        'net_amount',
        'status',
        'assign_date',
        'due_date',
        'notes',
        'admin_id',
        'metadata'
    ];

    protected $casts = [
        'assign_date' => 'date',
        'due_date' => 'date',
        'metadata' => 'array',
    ];

    public function student()
    {
        return $this->belongsTo(StudentDetail::class, 'student_detail_id');
    }

    public function template()
    {
        return $this->belongsTo(FeeTemplate::class, 'fee_template_id');
    }

    public function installments()
    {
        return $this->hasMany(FeeInstallment::class)
            ->orderBy('installment_number');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function concession()
    {
        return $this->belongsTo(FeeConcession::class);
    }

    public function updateStatus()
    {
        $totalInstallments = $this->installments()->count();
        $paidInstallments = $this->installments()->where('status', 'paid')->count();

        if ($paidInstallments === 0) {
            $this->status = 'draft';
        } elseif ($paidInstallments === $totalInstallments) {
            $this->status = 'completed';
        } else {
            $this->status = 'active';
        }

        $this->save();
    }
}
