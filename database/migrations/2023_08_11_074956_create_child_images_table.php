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
        Schema::create('child_images', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->boolean('teacher_checked');
            $table->timestamps();

            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')->references('id')
                ->on('children')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_images');
    }
};
