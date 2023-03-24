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
        Schema::create('especialidade', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_especialidade');

       
            $table->unsignedBigInteger('id_sala')->unasigned();
            $table->foreign('id_sala')->references('id_sala')->on('salas')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('hospital')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');

            $table->string('nome', 500);

        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::dropIfExists('especialidade');
    }
};
