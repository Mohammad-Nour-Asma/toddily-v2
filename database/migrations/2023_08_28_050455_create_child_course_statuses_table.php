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
        Schema::create('child_course_statuses', function (Blueprint $table) {
            $table->id();
            $table->string("description");
            $table->timestamps();

            $table->unsignedBigInteger('child_course_id');
            $table->foreign('child_course_id')->references('id')
                ->on('child_courses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_course_statuses');
    }
};
