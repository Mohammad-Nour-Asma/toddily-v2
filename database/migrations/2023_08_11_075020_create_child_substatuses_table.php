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
        Schema::create('child_substatuses', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->timestamps();

            $table->unsignedBigInteger('childStatus_id');
            $table->foreign('childStatus_id')->references('id')
                ->on('child_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('subStatus_id');
            $table->foreign('subStatus_id')->references('id')
                ->on('substatuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_substatuses');
    }
};
