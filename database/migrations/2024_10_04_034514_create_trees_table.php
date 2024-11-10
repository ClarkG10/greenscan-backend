<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trees', function (Blueprint $table) {
            $table->id('tree_id'); // Auto-incrementing ID (Primary Key)
            $table->string('common_name'); // Common name of the tree
            $table->string('scientific_name'); // Scientific name of the tree
            $table->string('family_name')->nullable(); // Family name of the tree
            $table->string('economic_use')->nullable(); // Economic use of the tree
            $table->string('iucn_status')->nullable(); // IUCN status of the tree
            $table->float('dbh')->nullable(); // Diameter at breast height (DBH)
            $table->float('dab')->nullable(); // Diameter at base height (DAB)
            $table->float('t_height')->nullable(); // Total height of the tree
            $table->float('tree_volume')->nullable(); // Volume of the tree
            $table->float('biomass')->nullable(); // Biomass of the tree
            $table->float('carbon_stored')->nullable(); // Carbon stored in the tree
            $table->integer('age')->nullable(); // Age of the tree
            $table->string('tree_health')->nullable(); // Health status of the tree
            $table->float('price')->nullable(); // Price of the tree
            $table->decimal('longitude', 10, 7)->nullable(); // Longitude for mapping
            $table->decimal('latitude', 10, 7)->nullable(); // Latitude for 
            $table->unsignedBigInteger('user_id');
            $table->timestamps(); // Timestamps for created_at and updated_at


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trees'); // Drop the trees table if it exists
    }
};
