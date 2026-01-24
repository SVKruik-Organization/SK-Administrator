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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('object');
            $table->string('name');
            $table->string('description');
            $table->integer('position');
            $table->string('language');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('custom_modules', function (Blueprint $table) {
            $table->uuid('user_profile_id')->after('id');
            $table->foreign('user_profile_id')->references('id')->on('user_profiles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
        Schema::table('custom_modules', function (Blueprint $table) {
            $table->dropForeign(['user_profile_id']);
            $table->dropColumn('user_profile_id');
        });
    }
};
