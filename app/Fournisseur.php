<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use function App\Providers\get_limit_pagination;

class Fournisseur extends Model
{
    // use SoftDeletes;

    protected $fillable = ["code",  "nom_fournisseur",  "adresse", "code_postal",  "ville",  "pays",  "tel",  "site",  "email",  "note", "iff",  "ice",  "capital",  "rc",  "patente",  "cnss",  "banque",  "rib"];

    // public function commande() 
    // {
    //     return $this->hasMany(Commande::class);
    // }

    protected $date = ['deleted_at'];

    public static function getFournisseurs(){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $fournisseurs = Fournisseur::orderBy('id','desc')
            ->where('user_id',$user_id)
            ->paginate(get_limit_pagination());
        return $fournisseurs;
    }

    public static function searchFournisseur($search){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $fournisseurs = Fournisseur::where([
            [function ($query) use ($search) {
                    $query->where('nom_fournisseur','like',"%$search%")
                    ->orWhere('code','like',"%$search%")
                    ->orWhere('adresse','like',"%$search%")
                    ->orWhere('tel','like',"%$search%");
            }],
            ['user_id',$user_id]
        ])
        ->orderBy('id','desc')
        ->paginate(get_limit_pagination());
        return $fournisseurs;
    }
}
