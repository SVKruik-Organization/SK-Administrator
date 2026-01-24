<?php

use App\Enums\PromptTypes;
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
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->nullableUuidMorphs('object');
            $table->string('type');
            $table->enum('level', array_map(fn ($case) => $case->name, PromptTypes::cases()));
            $table->json('data');
            $table->string('source');
            $table->string('url');
            $table->boolean('is_silent');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
    }
};
