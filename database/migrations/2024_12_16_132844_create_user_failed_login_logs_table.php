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
        Schema::create(getUserType().'_failed_login_logs', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->user();
            $table->string('ip', 39)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserType().'_failed_login_logs');
    }
};
