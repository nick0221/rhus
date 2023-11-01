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
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('fullname')->nullable();
            $table->string('extname')->nullable();
            $table->longText('address')->nullable();
            $table->string('civilstatus')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender');
            $table->string('height')->nullable();
            $table->string('weight')->nullable();
            $table->string('philhealthnum')->nullable();
            $table->boolean('isMember')->default(false);
            $table->string('image')->nullable();
            $table->foreignIdFor(\App\Models\Category::class, 'category_id')->nullable()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individuals');
    }
};
