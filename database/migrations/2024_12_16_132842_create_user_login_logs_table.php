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

        Schema::create($userType . '_login_logs', function (Blueprint $table) use ($userType) {
            $table->id();
            $table->portal();
            $table->language();
            $table->user();
            $table->foreignId($userType . '_role_id')->nullable()
                ->constrained($userType . '_roles')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('ip', 39)->comment('ipv4 or ipv6');
            $table->string('user_agent', 512);
            $table->smallInteger('login_type')->comment('1:basic | 2:otp');
            $table->dateTime('logged_in_at');
            $table->dateTime('logged_out_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(getUserType() . '_login_logs');
    }
};
