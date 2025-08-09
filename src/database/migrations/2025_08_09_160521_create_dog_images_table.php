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
        Schema::create('dog_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dog_id')->index()->nullable();
            $table->string('image', 500);
            $table->timestamps();

            $table->foreign('dog_id')
                ->references('id')
                ->on('dogs')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dog_images');
    }
};
