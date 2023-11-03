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
        Schema::create('ob_histories', function (Blueprint $table) {
            $table->id();
            $table->date('lmp')->nullable();
            $table->string('aog')->nullable();
            $table->date('edc')->nullable();
            $table->foreignIdFor(\App\Models\Individual::class, 'individual_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ob_histories');
    }
};
