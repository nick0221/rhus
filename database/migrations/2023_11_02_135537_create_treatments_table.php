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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Individual::class, 'individual_id');
            $table->foreignId('past_medicalhistories_id')->references('id')->on('past_medicalhistories')->cascadeOnDelete();
            $table->foreignId('ob_histories_id')->references('id')->on('ob_histories')->cascadeOnDelete();
            $table->foreignId('travel_histories_id')->references('id')->on('travel_histories')->cascadeOnDelete();
            $table->foreignId('family_histories_id')->references('id')->on('family_histories')->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\Category::class, 'category_id')->nullable();
            $table->boolean('isDependent')->default(false);
            $table->string('dependentPhilhealthNum')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phMemberName')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
