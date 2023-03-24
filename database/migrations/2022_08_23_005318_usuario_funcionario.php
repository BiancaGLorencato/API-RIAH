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
        Schema::create('usuario_funcionario', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_funcionario');

       
            $table->unsignedBigInteger('id_usuario')->unasigned();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
            
            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('hospital')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->integer('matricula');
            $table->string('cargo', 50);
            $table->integer('logado');
            $table->timestamp('emailultimo_logado')->nullable();
            $table->integer('primeiro_login')->nullable();
            $table->integer('exame_write');
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
        Schema::dropIfExists('usuario_funcionario');
    }
};
