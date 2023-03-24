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
        Schema::create('usuario_deletado', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->id('id_usuario');
            $table->string('cpf', 15);
            $table->string('rg', 15);
            $table->string('nome_completo');
            $table->integer('cep');
            $table->string('endereco', 150);
            $table->string('data_nascimento', 20);
            $table->string('email');
            $table->timestamp('email_verified_at');
            $table->string('password');
            $table->integer('termo_uso');
            $table->string('perfil', 50);
            $table->integer('matricula');
            $table->string('cargo', 50);
            $table->longText('logs');
            $table->datetime('deletado');
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
        Schema::dropIfExists('usuario_deletado');
    }
};
