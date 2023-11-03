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
