<?php

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
        Schema::create('age_relaxations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Links to users table
            $table->boolean('relax_schedule_caste')->default(false); // Scheduled Castes, etc.
            $table->boolean('relax_retired')->default(false); // Retired Armed Forces
            $table->string('relax_retired_from')->nullable(); // Army, Navy, Air Force
            $table->string('relax_retired_position')->nullable(); // Rank
            $table->date('relax_retired_appoint')->nullable(); // Date of Appointment
            $table->date('relax_retired_retired')->nullable(); // Date of Retirement
            $table->boolean('relax_disable')->default(false); // Disabled Person
            $table->string('relax_disabled_nature')->nullable(); // Nature of Disability
            $table->boolean('relax_widow')->default(false); // Widow/Widower
            $table->string('relax_name_employ')->nullable(); // Name of Deceased Govt Employee
            $table->string('relax_designation')->nullable(); // Designation and BPS
            $table->string('relax_department')->nullable(); // Department
            $table->date('relax_date_death')->nullable(); // Date of Death
            $table->boolean('gov')->default(false); // Contract-based Govt Employee
            $table->string('gov_name')->nullable(); // Department Name
            $table->string('gov_designation')->nullable(); // Designation
            $table->string('gov_basic_pay')->nullable(); // Basic Pay Scale
            $table->date('gov_appoint_date')->nullable(); // Appointment Date
            $table->string('gov_retire_date')->nullable(); // Till Registration End Date
            $table->string('gov_appoint_nature')->nullable(); // Appointment Nature (e.g., Contract)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('age_relaxations');
    }
};