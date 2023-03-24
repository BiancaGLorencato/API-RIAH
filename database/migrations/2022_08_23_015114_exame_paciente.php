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
        
        Schema::create('exame_paciente', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_exame_pa');

       
            $table->unsignedBigInteger('id_exame')->unasigned();
            $table->foreign('id_exame')->references('id_exame')->on('exame')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->unsignedBigInteger('id_prontuario')->unasigned();
            $table->foreign('id_prontuario')->references('id_prontuario')->on('prontuario')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            
            $table->unsignedBigInteger('id_enfermeiro')->unasigned()->nullable();
            $table->foreign('id_enfermeiro')->references('id_funcionario')->on('usuario_funcionario')
                          ->onUpdate('cascade')
                          ->onDelete('cascade');

            $table->timestamp('data_chamada_exame')->nullable();
            $table->timestamp('data_atentimento')->nullable();
            $table->string('url_do_exame', 500)->nullable();
            $table->string('notas_do_exame', 500)->nullable();
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
        //
    }
};
