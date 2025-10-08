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
        Schema::create('file_disks', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->string('title',128);
            $table->string('name',32)->comment('must be english because its the key of storage in laravel filesystems e.g., internal244');
            $table->string('hostname', 45)->nullable();
            $table->unsignedSmallInteger('port')->nullable();
            $table->unsignedSmallInteger('driver')->comment('1:local | 2:sftp');
            $table->string('base_path',128)->nullable()->default('/')->comment('root path');
            $table->unsignedSmallInteger('auth_type')->default(0)->comment('0:no auth | 1:basic | 2:ssh key based');
            $table->unsignedInteger('priority')->default(0);
            $table->json('auth_fields')->nullable();
            $table->json('options')->nullable();
            $table->text('description')->nullable();
            $table->date('started_at');
            $table->boolean('is_expired')->default(false);
            $table->date('expired_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->fullDateTimesWithRelations();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_disks');
    }

};
