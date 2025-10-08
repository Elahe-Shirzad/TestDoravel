<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(getUserType().'_roles', function (Blueprint $table) {
            $table->id();
            $table->user();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->fullDateTimesWithRelations('created_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserType().'_roles');
    }
};
