<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lignecommande extends Model
{
    public function produit(){
        return $this->belongsTo(Produit::class);
    }
    public function commande(){
        return $this->belongsTo(Commande::class);
    }
}
