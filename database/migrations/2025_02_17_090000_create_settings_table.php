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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->string('key');
            $table->string('name');
            $table->string('value');
            $table->string('field');
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->fullDateTimes(withSoftDeletes: false);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }

};
