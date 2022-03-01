<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payement extends Model
{
    public function demande() 
    {
        return $this->belongsTo(Demande::class);
    }
}
