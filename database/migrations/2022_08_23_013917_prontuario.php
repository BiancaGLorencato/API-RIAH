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
        Schema::create('prontuario', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_prontuario');

       
            $table->unsignedBigInteger('id_usuario')->unasigned();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            
            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('hospital')
                          ->onUpdate('cascade')
                          ->onDelete('cascade');
       
       
            $table->unsignedBigInteger('id_enf_triagem')->unasigned();
            $table->foreign('id_enf_triagem')->references('id_funcionario')->on('usuario_funcionario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade')->nullable();

            $table->unsignedBigInteger('id_urgencia')->unasigned();
            $table->foreign('id_urgencia')->references('id_urgencia')->on('tipo_urgencia')
                        ->onUpdate('cascade')
                        ->onDelete('cascade')->nullable();;

            $table->integer('nivel_gravidade')->nullable();
            $table->timestamp('data_inicio');

            $table->string('pressao', 20)->nullable();
            $table->integer('temperatura')->nullable();
            $table->integer('taixa_diabetica')->nullable();
            $table->longText('sintomas')->nullable();
            $table->longText('alergia')->nullable();
            $table->longText('obs_triagem')->nullable();
            $table->integer('alta')->nullable();
            $table->timestamp('data_atla')->nullable();
            $table->longText('remedios')->nullable();
            $table->longText('recomendacoes')->nullable();
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
        Schema::dropIfExists('prontuario');
    }
};
