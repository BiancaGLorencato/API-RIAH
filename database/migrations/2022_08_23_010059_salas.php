<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_sala');

       
            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('hospital')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

           
            $table->integer('andar');
            $table->string('nome', 100);
            $table->integer('numero');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sala');
    }
};
