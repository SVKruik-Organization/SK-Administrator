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
        Schema::create('module_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('module_id');
            $table->foreign('module_id')->references('id')->on('modules');
            $table->string('name');
            $table->integer('position');
            $table->string('icon');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('custom_module_items', function (Blueprint $table) {
            $table->uuid('module_item_id')->after('id');
            $table->foreign('module_item_id')->references('id')->on('module_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('module_items');
        Schema::table('custom_module_items', function (Blueprint $table) {
            $table->dropForeign(['module_item_id']);
            $table->dropColumn('module_item_id');
        });
    }
};
