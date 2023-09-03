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
        Schema::create('children', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            $table->string('image');
            $table->boolean('isExtra');

            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('classRoom_id');
            $table->foreign('classRoom_id')->references('id')
                ->on('class_rooms')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('children');
    }
};
