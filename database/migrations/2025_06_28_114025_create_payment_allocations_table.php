<?php

use App\Models\Admin\Fee\FeeInstallment;
use App\Models\Admin\Fee\PaymentTransaction;
use App\Models\Organization;
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
        Schema::create('payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)->default(0);
            $table->foreignIdFor(PaymentTransaction::class)->default(0);
            $table->foreignIdFor(FeeInstallment::class)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_allocations');
    }
};
