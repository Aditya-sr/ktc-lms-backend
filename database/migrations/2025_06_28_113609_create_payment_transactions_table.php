<?php

use App\Models\Organization;
use App\Models\Student\StudentDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->foreignIdFor(Organization::class)->default(0);
            $table->foreignIdFor(StudentDetail::class)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('convenience_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_mode')->nullable(); //, ['cash', 'cheque', 'online', 'card', 'bank_transfer', 'other']);
            $table->string('payment_gateway')->nullable();
            $table->string('status')->nullable(); //, ['initiated', 'pending', 'completed', 'failed', 'refunded'])->default('initiated');
            $table->json('gateway_response')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
