<?php

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
        Schema::create('fee_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // Annual, Semester, Monthly, Term
            $table->string('frequency')->nullable(); // yearly, half_yearly, quarterly, monthly, custom
            $table->integer('installments_count')->default(1);
            $table->integer('due_day_of_month')->nullable(); // e.g., 5th of every month
            $table->boolean('is_active')->default(true);
            $table->foreignIdFor(Organization::class)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_cycles');
    }
};
