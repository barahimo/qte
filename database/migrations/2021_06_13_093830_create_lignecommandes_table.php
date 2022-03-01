<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLignecommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lignecommandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commande_id')
            ->constrained('commandes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            $table->foreignId('produit_id')
            ->constrained('produits')
            ->onDelete('cascade')
            ->onUpdate('cascade'); 
            $table->string('Qantite');   
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lignecommandes');
    }
}
