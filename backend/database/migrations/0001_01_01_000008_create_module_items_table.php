<?php

declare(strict_types=1);

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
            $table->foreignUuid('module_id')->nullable()->constrained('modules')->nullOnDelete();
            $table->json('name');
            $table->integer('position')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::table('custom_module_items', function (Blueprint $table) {
            $table->foreignUuid('module_item_id')->after('id')->constrained('module_items')->onDelete('cascade');
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
