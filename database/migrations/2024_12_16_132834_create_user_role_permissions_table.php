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
        $userType = getUserType();
        $tableName = getUserTypePlural();

        Schema::create($userType.'_role_permissions', function (Blueprint $table) use ($userType, $tableName) {
            $table->id();
            $table->user();
            $table->foreignId($userType.'_role_id')->constrained($userType.'_roles')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('permission_id')->constrained('permissions')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->dateTime('created_at');
            $table->dateTime('expired_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained($tableName)
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('expired_by')->nullable()->constrained($tableName)
                ->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserType().'_role_permissions');
    }
};
