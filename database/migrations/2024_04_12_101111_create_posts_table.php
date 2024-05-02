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
        Schema::create('posts', function (Blueprint $table) {
            $table->id('post_id');
            $table->string('title');
            $table->string('content');
            $table->string('slug');
            $table->string('feature_image');
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('delete_by');
            $table->timestamps();
            $table->foreign('categories_id')->references('categories_id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tag_id')->references('tag_id')->on('tags')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('delete_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
