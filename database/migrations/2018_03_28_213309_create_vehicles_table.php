<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug');
            $table->integer('price')->unsigned()->default(0);
            $table->year('year');
            $table->integer('miles')->unsigned()->default(0);
            $table->string('image')->nullable();
            $table->integer('category_id')->unsigned();
            $table->integer('source_id')->unsigned()->nullable();
            $table->enum('status', ['approved', 'pending'])->default('pending');

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
        Schema::dropIfExists('vehicles');
    }
}
