<?php

declare(strict_types=1);

use App\Enums\ScheduledTaskStatus;
use App\Enums\ScheduledTaskType;
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
        Schema::create('scheduled_tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', array_map(fn ($case) => $case->name, ScheduledTaskType::cases()));
            $table->enum('status', array_map(fn ($case) => $case->name, ScheduledTaskStatus::cases()));
            $table->json('data')->nullable();
            $table->timestamp('scheduled_at');
            $table->integer('tries')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scheduled_tasks');
    }
};
