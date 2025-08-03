<?php

use App\Models\Admin\Fee\FeeHead;
use App\Models\Admin\Fee\FeeTemplate;
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
        Schema::create('fee_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)->default(0);
            $table->foreignIdFor(FeeTemplate::class)->default(0);
            $table->foreignIdFor(FeeHead::class)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->boolean('is_optional')->default(false);
            $table->boolean('is_discount')->default(false);
            $table->integer('sort_order')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_template_items');
    }
};
