<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalenderierTable extends Migration
{
    
    public function up()
    {
        Schema::create('calenderier', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->string('nom_prenom');
            $table->date('date_debut');
            $table->date('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calenderier');
    }
}
