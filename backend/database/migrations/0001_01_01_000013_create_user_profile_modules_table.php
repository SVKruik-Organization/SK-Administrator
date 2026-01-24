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
        Schema::create('user_profile_modules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_profile_id');
            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
            $table->uuid('module_id');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->integer('position');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profile_modules');
    }
};
