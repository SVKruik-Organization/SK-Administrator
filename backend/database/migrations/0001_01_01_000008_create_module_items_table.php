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
            $table->uuid('module_id')->nullable();
            $table->foreign('module_id')->references('id')->on('modules')->nullOnDelete();
            $table->json('name');
            $table->integer('position')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
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
