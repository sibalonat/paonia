<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            /* $table->string('name'); */
            $table->string('email');
            $table->string('usertype')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('is_logged')->default(0);
            $table->string('nome')->default('nome utente');
            $table->string('cognome')->default('nome utente');
            $table->string('permessi')->default(0);
            $table->string('utente_id')->default(0);
            $table->string('specialita')->default(0);
            $table->string('attivita')->default(0);
            $table->string('tipo')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
