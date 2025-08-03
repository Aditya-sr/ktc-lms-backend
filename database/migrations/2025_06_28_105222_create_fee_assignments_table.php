<?php

use App\Models\Admin\Fee\FeeTemplate;
use App\Models\Organization;
use App\Models\Student\Section;
use App\Models\Student\Standard;
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
        Schema::create('fee_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Organization::class)->default(0);
            $table->foreignIdFor(StudentDetail::class)->default(0);
            $table->foreignIdFor(Standard::class)->default(0);
            $table->foreignIdFor(Section::class)->default(0);
            $table->foreignIdFor(FeeTemplate::class)->default(0);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('academic_session')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('concession_amount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->string('status')->nullable(); //, ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->date('assign_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fee_assignments');
    }
};
