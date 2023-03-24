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
        Schema::create('prox_passo_urgencia', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_passo');

       
            $table->unsignedBigInteger('id_urgencia')->unasigned();
            $table->foreign('id_urgencia')->references('id_urgencia')->on('tipo_urgencia')
                   ->onUpdate('cascade')
                   ->onDelete('cascade')->nullable();;

            $table->unsignedBigInteger('id_especialidade')->unasigned();
            $table->foreign('id_especialidade')->references('id_especialidade')->on('especialidade')
                   ->onUpdate('cascade')
                   ->onDelete('cascade')->nullable();;

           
            $table->integer('triagem')->nullable();;
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
        
        Schema::dropIfExists('prox_passo_urgencia');
    }
};