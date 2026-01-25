<?php

use App\Enums\PromptType;
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
            $table->uuidMorphs('object');
            $table->enum('type', array_map(fn ($case) => $case->name, PromptType::cases()));
            $table->json('data');
            $table->string('source')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_silent')->default(false);
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
