<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ["nom","logo","adresse","code_postal","ville","pays","tel","site","email","note","iff","ice","capital","rc","patente","cnss","banque","rib"];
}
