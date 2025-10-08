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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->morphs(getUserType());
            $table->foreignId(getUserType().'_role_id')->constrained(getUserType().'_roles')->restrictOnDelete()->cascadeOnUpdate();
            $table->morphs('entity');
            $table->unsignedTinyInteger('action')->comment('1:create | 2:update | 3:delete');
            $table->longText('data');
            $table->string('page_route_name');
            $table->string('referer_route_name');
            $table->string('ip', 45);
            $table->dateTime('created_at');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }

};
