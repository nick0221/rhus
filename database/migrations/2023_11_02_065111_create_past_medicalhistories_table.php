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
        Schema::create('past_medicalhistories', function (Blueprint $table) {
            $table->id();
            $table->date('historyDate')->nullable();
            $table->longText('description')->nullable();
            $table->foreignIdFor(\App\Models\Individual::class, 'individual_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('past_medicalhistories');
    }
};
