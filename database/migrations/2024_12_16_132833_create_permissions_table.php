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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_category_id')
                ->constrained('permission_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('parent_id')->nullable()
                ->constrained('permissions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('name', 128);
            $table->string('slug', 128);
            $table->string('guard_name', 128);
            $table->string('style', 255)->nullable();
            $table->string('extra_value', 255)->nullable();
            $table->string('extra_param', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->boolean('show_in_menu')->default(false);
            $table->boolean('show_in_home')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_special')->default(false);
            $table->bigInteger('sort')->default(1);
            $table->fullDateTimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
