<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            $table->float('totale_HT', 8, 2)->default(0);

            $table->float('totale_TVA', 8, 2)->default(0);
            $table->float('totale_TTC', 8, 2)->default(0);

            
            $table->foreignId('commande_id')
            ->constrained('commandes')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            // ->unique();
            // $table->foreignId('clients_id')->constrained('clients');
            $table->string('reglement');
        
            
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
        Schema::dropIfExists('factures');
    }
}
