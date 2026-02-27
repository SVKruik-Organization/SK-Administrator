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
        Schema::create('user_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->json('name');
            $table->json('description')->nullable();
            $table->integer('position')->default(0);
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('role_id')->after('id');
            $table->foreign('role_id')->references('id')->on('user_roles');
        });

        Schema::table('guest_users', function (Blueprint $table) {
            $table->uuid('role_id')->after('id');
            $table->foreign('role_id')->references('id')->on('user_roles');
        });

        Schema::table('module_item_grants', function (Blueprint $table) {
            $table->uuid('role_id')->after('id');
            $table->foreign('role_id')->references('id')->on('user_roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::table('guest_users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
        Schema::table('module_item_grants', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
