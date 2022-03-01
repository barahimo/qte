<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lignedemande extends Model
{
    public function produit(){
        return $this->belongsTo(Produit::class);
    }
    public function demande(){
        return $this->belongsTo(Demande::class);
    }
}
