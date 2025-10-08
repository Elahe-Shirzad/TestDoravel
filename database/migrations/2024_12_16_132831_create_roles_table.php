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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128);
            $table->string('slug', 128);
            $table->string('guard_name', 128);
            $table->smallInteger('login_type')->comment('1:basic | 2:otp');
            $table->bigInteger('sort')->default(1);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_superrole')->default(false);
            $table->fullDateTimesWithRelations(true);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
