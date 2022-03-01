<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToLignecommandes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lignecommandes', function (Blueprint $table) {
            $table->timestamps();
            $table->string('nom_produit');
            $table->datetime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lignecommandes', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
            $table->dropColumn('nom_produit');
        
        });
    }
}
