<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('id_produit');
            $table->string('nom_produit');
            $table->text('code_produit');
            $table->decimal('TVA');
            $table->decimal('prix_produit_HT');
            $table->text('descreption')->nullable();
            $table->foreignId('categorie_id')
            ->constrained('categories')
            ->onDelete('cascade')
            ->onUpdate('cascade');;

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
        Schema::dropIfExists('produits');
    }
}
