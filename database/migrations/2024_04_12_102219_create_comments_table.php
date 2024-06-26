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
        Schema::create('comments', function (Blueprint $table) {
            $table->id('comment_id');
            $table->string('comment_content');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('commneted_by')->nullable();
            $table->timestamps();
            $table->foreign('post_id')->references('post_id')->on('posts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('commneted_by')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
     
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
