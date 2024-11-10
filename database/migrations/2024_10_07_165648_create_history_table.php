<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * 
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tree_id'); // Reference to the tree
            $table->unsignedBigInteger('user_id'); // Reference to the user who performed the action
            $table->string('action'); // Action performed (e.g., 'updated', 'deleted', 'created')
            $table->json('old_data')->nullable(); // JSON containing the old tree data before update
            $table->json('new_data')->nullable(); // JSON containing the new tree data after update
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('tree_id')->references('tree_id')->on('trees')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};
