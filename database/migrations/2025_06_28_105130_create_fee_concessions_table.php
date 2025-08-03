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
        Schema::create('fee_concessions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('type')->nullable();  //Percentage , Fixed
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->foreignIdFor(Organization::class)->default(0);
            $table->json('eligibility_criteria')->nullable(); // JSON rules for auto-application
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_concessions');
    }
};
