<?php

use App\Enums\ScheduledTaskStatus;
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
            $table->string('type');
            $table->json('data');
            $table->enum('status', array_map(fn ($case) => $case->name, ScheduledTaskStatus::cases()));
            $table->timestamp('scheduled_at');
            $table->integer('tries');
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
