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
        Schema::create('file_types', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->string('name',128);
            $table->string('code',32);
            $table->unsignedSmallInteger('type')->default(0)->comment('0:general | 1:'.getUserType().'_document');
            $table->foreignId('file_directory_id')->constrained('file_directories')->restrictOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('max_size')->default(0)->comment('bytes | zero means unlimited');
            $table->string('allowed_extensions',255)->nullable()->comment('null means all extensions valid');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_private')->default(false);
            $table->boolean('is_active')->default(true);
            $table->fullDateTimesWithRelations();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_types');
    }

};
