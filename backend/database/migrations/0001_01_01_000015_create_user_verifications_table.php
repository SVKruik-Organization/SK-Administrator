<?php

declare(strict_types=1);

use App\Enums\VerificationReason;
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
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_email');
            $table->foreign('user_email')->references('email')->on('users');
            $table->integer('pin');
            $table->enum('reason', array_map(fn ($case) => $case->name, VerificationReason::cases()));
            $table->timestamp('expires_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_verifications');
    }
};
