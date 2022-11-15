<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id(); 
            $table->float('score');

            $table->morphs('rateable');//Se refiere a la entidad que yo quiero puntuar

            /*
            *La linea de arriba equivale a escribir las siguientes lineas 
            * $table->unsignedBigInteger('rateable_id');
            * $table->string('rateable_type');
            */

            $table->morphs('qualifier');//Se refiere a la entidad que va a calificar

            /*
            *La linea de arriba equivale a escribir las siguientes lineas 
            * $table->integer('qualifier_id');
            * $table->string('qualifier_type');
            */

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
        Schema::dropIfExists('ratings');
    }
}
