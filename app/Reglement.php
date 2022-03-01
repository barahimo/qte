<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reglement extends Model
{

    // public function commande() 
    // {
    //     return $this->hasMany(Commande::class);
    // }

    public function commande() 
    {
        return $this->belongsTo(Commande::class);
    }
}
