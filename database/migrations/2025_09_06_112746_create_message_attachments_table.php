<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->cascadeOnDelete();
            $table->string('path');               // storage path (public disk)
            $table->string('mime', 100);
            $table->unsignedBigInteger('size');   // bytes
            $table->string('original_name');
            $table->unsignedInteger('width')->nullable();   // for images
            $table->unsignedInteger('height')->nullable();  // for images
            $table->unsignedInteger('duration')->nullable();// for videos (sec, optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
    }
};
