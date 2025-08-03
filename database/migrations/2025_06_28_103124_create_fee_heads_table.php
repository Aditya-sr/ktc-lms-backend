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
        Schema::create('fee_heads', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); 
            $table->string('code')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('type')->default();
            $table->boolean('is_refundable')->default(false);
            $table->boolean('is_taxable')->default(false);
            $table->foreignIdFor(Organization::class)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_heads');
    }
};
