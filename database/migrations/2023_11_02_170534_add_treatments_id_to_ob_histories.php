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
        Schema::table('ob_histories', function (Blueprint $table) {
            $table->foreignId('treatments_id')->references('id')->on('treatments')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ob_histories', function (Blueprint $table) {
            $table->dropForeign(['treatments_id']);
            $table->dropColumn('treatments_id');
        });
    }
};
