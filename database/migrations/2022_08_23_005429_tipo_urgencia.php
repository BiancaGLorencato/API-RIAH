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
        Schema::create('tipo_urgencia', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_urgencia');
           
            $table->unsignedBigInteger('id_hospital')->unasigned();
            $table->foreign('id_hospital')->references('id_hospital')->on('hospital')
                   ->onUpdate('cascade')
                   ->onDelete('cascade');
           
            $table->string('nome_urgencia', 500);
            $table->integer('status');
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
        Schema::dropIfExists('tipos_urgencia');
    }
};
