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
        Schema::create('followup_checkups', function (Blueprint $table) {
            $table->id();
            $table->dateTime('followupDate')->nullable();
            $table->foreignIdFor(\App\Models\Individual::class, 'individual_id')->nullable();
            $table->foreignIdFor(\App\Models\Treatment::class, 'treatment_id')->nullable();
            $table->string('remarksNote')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('followup_checkups');
    }
};
