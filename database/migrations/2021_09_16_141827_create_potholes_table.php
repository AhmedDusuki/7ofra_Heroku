<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePotholesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potholes', function (Blueprint $table) {
            $table->id();
            $table->string('lat');
            $table->string('lng');
            $table->json('reports');
            $table->json('remove reports');
            $table->string('user');
            $table->integer('grid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('potholes');
    }
}
