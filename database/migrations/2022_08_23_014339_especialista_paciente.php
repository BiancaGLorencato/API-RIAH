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
        
        Schema::create('especialista_paciente', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_esp_pa');

       
            $table->unsignedBigInteger('id_especialista')->unasigned();
            $table->foreign('id_especialista')->references('id_especialista')->on('especialista_funcionario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->unsignedBigInteger('id_prontuario')->unasigned();
            $table->foreign('id_prontuario')->references('id_prontuario')->on('prontuario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->timestamp('data_chamada_especialista')->nullable();
            $table->timestamp('data_atendimento')->nullable();
            $table->longText('obs')->nullable();
            $table->integer('status')->nullable();
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('especialista_paciente');
    }
};
