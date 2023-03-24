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
        Schema::create('especialista_funcionario', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_especialista');

       
            $table->unsignedBigInteger('id_funcionario')->unasigned();
            $table->foreign('id_funcionario')->references('id_funcionario')->on('usuario_funcionario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            
            $table->unsignedBigInteger('id_especialidade')->unasigned();
            $table->foreign('id_especialidade')->references('id_especialidade')->on('especialidade')
           ->onUpdate('cascade')
           ->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('id_especialista_fun');
    }
};
