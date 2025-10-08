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
        Schema::create(getUserTypePlural(), function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 128);
            $table->string('last_name', 128);
            $table->string('mobile', 11)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255);
            $table->string('job_title', 128)->nullable();
            $table->string('national_code', 10);
            $table->string('image', 255)->nullable();
            $table->smallInteger('login_type')->nullable()->comment('1:basic | 2:otp');
            $table->smallInteger('status')->default(1)->comment('0:to_active | 1:active | 2:inactive | 3:block');
            $table->dateTime('email_verified_at')->nullable();
            $table->dateTime('mobile_verified_at')->nullable();
            $table->boolean('is_superadmin')->unsigned()->default(false);
            $table->fullDateTimesWithRelations(true);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserTypePlural());
    }
};
