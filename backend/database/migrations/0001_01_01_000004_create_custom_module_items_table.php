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
        Schema::create('custom_module_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('custom_module_id');
            $table->foreign('custom_module_id')->references('id')->on('custom_modules');
            $table->integer('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_module_items');
    }
};
