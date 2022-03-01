<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('cadre');
            $table->date('date');
            $table->decimal('oeil_gauche')->default(0);
            $table->decimal('oeil_droite')->default(0);
            $table->decimal('mesure_vue')->default(0);
            $table->decimal('totale')->nullable()->default(0);
            $table->decimal('mesure_visage')->default(0);
            $table->foreignId('client_id')
            ->constrained('clients')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            // $table->foreignId('produit_id')->constrained('produits');
            
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
        Schema::dropIfExists('commandes');
    }
}
