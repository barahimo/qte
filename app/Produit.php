<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function App\Providers\get_limit_pagination;

class Produit extends Model
{
    use SoftDeletes;

    protected $fillable = ["nom_produit","description","prix_produits"];

    public function categorie() 
    {
        return $this->belongsTo(Categorie::class);
    }
    public function Commande() 
    {
        return $this->belongsToMany(Commande::class);
    }

    public function Demande() 
    {
        return $this->belongsToMany(Demande::class);
    }

    public function stocks() 
    {
        return $this->hasMany(Stock::class);
    }

    protected $date = ['deleted_at'];

        public static function getProduits(){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $produits = Produit::with('categorie')
            ->orderBy('id', 'desc')
            ->where('user_id',$user_id)
            ->paginate(get_limit_pagination());
        return $produits;
    }

    public static function searchProduit($search){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $produits = Produit::with('categorie')
            ->where([
                [function ($query) use ($search) {
                    $query->where('nom_produit','like',"%$search%")
                    ->orWhere('code_produit','like',"%$search%")
                    ->orWhere('TVA','like',"%$search%")
                    ->orWhere('quantite','like',"%$search%")
                    ->orWhere('prix_HT','like',"%$search%")
                    ->orWhere('prix_TTC','like',"%$search%")
                    ->orWhere('prix_produit_HT','like',"%$search%")
                    ->orWhere('prix_produit_TTC','like',"%$search%");
                }],
                ['user_id',$user_id]
            ])
            ->orWhereHas('categorie',function($query) use($search,$user_id){
                $query->where([['nom_categorie','like',"%$search%"],['user_id',$user_id]]);
            })
            ->orderBy('id','desc')
            ->paginate(get_limit_pagination());
        return $produits;
    }
}
