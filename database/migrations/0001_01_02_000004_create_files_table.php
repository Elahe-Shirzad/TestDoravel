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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->portal();
            $table->language();
            $table->string('name', 128);
            $table->nullableMorphs('uploader');
            $table->foreignId('file_type_id')->constrained('file_types')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('file_disk_id')->nullable()->constrained('file_disks')->restrictOnDelete()->cascadeOnUpdate();
            $table->string('file_disk_name', 32)->nullable();
            $table->string('mime_type', 128);
            $table->string('extension', 255);
            $table->unsignedBigInteger('size')->comment('bytes');
            $table->string('path', 255);
            $table->string('full_month', 6)->comment('format: 140106');
            $table->string('original_name', 255)->comment('without extension');
            $table->boolean('is_private')->default(false);
            $table->fullDateTimes();
            $table->foreignId('deleted_by')->nullable()->constrained(getUserTypePlural())->cascadeOnDelete()->cascadeOnUpdate();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }

};
