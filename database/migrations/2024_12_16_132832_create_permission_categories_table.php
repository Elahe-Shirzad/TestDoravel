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
        Schema::create('permission_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 128);
            $table->string('name', 128);
            $table->string('style', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('show_in_menu')->default(false);
            $table->boolean('show_in_home')->default(false);
            $table->boolean('is_active')->default(true);
            $table->bigInteger('sort')->default(1);
            $table->foreignId('parent_id')->nullable()
                ->constrained('permission_categories')->cascadeOnDelete()->cascadeOnUpdate();
            $table->fullDateTimes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_categories');
    }
};
