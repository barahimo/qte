<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReglementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reglements', function (Blueprint $table) {
            $table->id();
            $table->string('nom_client');
            $table->string('mode_reglement');
           
            $table->float('avance', 8, 2)->default(0);
           
            $table->float('reste', 8, 2)->default(0);
            $table->date('date');
            $table->string('reglement');
            $table->foreignId('commande_id')
            ->constrained('commandes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
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
        Schema::dropIfExists('reglements');
    }
}
