<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use function App\Providers\get_limit_pagination;


class Categorie extends Model
{
    protected $fillable = ["nom","description"];

    public function produit() 
    {
        return $this->hasMany(Produit::class);
    }

    public static function getCategories(){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories = Categorie::orderBy('id', 'desc')
            ->where('user_id',$user_id)
            ->paginate(get_limit_pagination());
        return $categories;
    }

    public static function searchCategorie($search){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories = Categorie::where([
                [function ($query) use ($search) {
                        $query->where('nom_categorie','like',"%$search%")
                        ->orWhere('description','like',"%$search%");
                }],
                ['user_id',$user_id]
            ])
            ->orderBy('id','desc')
            ->paginate(get_limit_pagination());
        return $categories;
    }
}
