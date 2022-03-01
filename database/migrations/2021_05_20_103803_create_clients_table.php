<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            // $table->bigInteger('id_client');
            // $table->increments('id_client');
            $table->id();
            $table->string('nom_client');
            $table->text('adresse');
            $table->bigInteger('telephone');
            $table->text('ICE')->nullable();
            $table->bigInteger('solde')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
