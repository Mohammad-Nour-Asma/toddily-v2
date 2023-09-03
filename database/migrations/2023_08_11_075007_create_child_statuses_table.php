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
        Schema::create('child_statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('child_id');
            $table->foreign('child_id')->references('id')
                ->on('children')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('status_id');
            $table->foreign('status_id')->references('id')
                ->on('statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_statuses');
    }
};
