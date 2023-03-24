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
        Schema::create('exames', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_exame');
            
            $table->int('id_hospital')->unasigned();

       
            $table->unsignedBigInteger('id_sala')->unasigned();
            $table->foreign('id_sala')->references('id_sala')->on('salas')
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
        Schema::dropIfExists('exame');
    }
};
