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
         Schema::create('platform_post', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')
            ->on('posts');
             $table->unsignedBigInteger('platform_id');
            $table->foreign('platform_id')->references('id')
            ->on('platforms');
            $table->string('platform_status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_post');
    }
};
