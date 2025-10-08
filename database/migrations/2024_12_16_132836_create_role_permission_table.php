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
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('role_id')->constrained('roles')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('created_at');
            $table->foreignId('created_by')->nullable()->constrained(getUserTypePlural())
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permission');
    }
};
