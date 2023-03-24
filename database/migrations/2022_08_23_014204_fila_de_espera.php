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
        
        Schema::create('fila_de_espera', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_espera');

       
            $table->unsignedBigInteger('id_sala')->unasigned();
            $table->foreign('id_sala')->references('id_sala')->on('salas')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->unsignedBigInteger('id_paciente')->unasigned();
            $table->foreign('id_paciente')->references('id_usuario')->on('prontuario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

           
            
            $table->timestamp('data_inicio')->nullable();
            $table->integer('nivel_gravidade')->nullable();
            $table->timestamp('horario_alta')->nullable();
            $table->integer('chamando')->nullable();
            $table->integer('finalizado')->nullable();
            $table->longText('log');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fila_de_espera');
    }
};
