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
        Schema::create(getUserType().'_activations', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->user();
            $table->string('code', 16);
            $table->string('hashcode', 64)->nullable();
            $table->json('extra_data')->nullable();
            $table->smallInteger('target_type')->comment('1:mobile | 2:email');
            $table->string('target', 255);
            $table->smallInteger('type')->comment('1:activation_code | 2:two factor authentication | 3:forgot password');
            $table->boolean('is_used')->default(false);
            $table->dateTime('used_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->fullDateTimes(withSoftDeletes: false);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserType().'_activations');
    }
};
