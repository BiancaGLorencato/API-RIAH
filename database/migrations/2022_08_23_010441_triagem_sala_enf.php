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
        Schema::create('triagem_sala_enf', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('triagem_sala_enf');

       
            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('salas')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->unsignedBigInteger('id_enfermeiro')->unasigned();
            $table->foreign('id_enfermeiro')->references('id_funcionario')->on('usuario_funcionario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            $table->integer('id_sala')->nullable();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('triagem_sala_enf');
    }
};
