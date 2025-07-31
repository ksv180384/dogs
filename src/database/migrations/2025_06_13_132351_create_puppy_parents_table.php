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
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id');
            $table->string('name');
            $table->string('image', 500)->nullable()->default(null);
            $table->text('description')->nullable()->default(null);
            $table->date('birthdate');
            $table->enum('type', ['dog', 'puppy']);
            $table->enum('status', ['active', 'hidden']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('puppy_parents');
    }
};
