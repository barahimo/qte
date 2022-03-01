<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use function App\Providers\get_limit_pagination;


class Client extends Model
{
    // use SoftDeletes;

    protected $fillable = ["adresse","telephone","ICE","solde"];

    public function commande() 
    {
        return $this->hasMany(Commande::class);
    }

    protected $date = ['deleted_at'];

    public static function getClients(){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $clients = Client::orderBy('id','desc')
            ->where('user_id',$user_id)
            ->paginate(get_limit_pagination());
        return $clients;
    }

    public static function searchClient($search){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $clients = Client::where([
            [function ($query) use ($search) {
                    $query->where('nom_client','like',"%$search%")
                    ->orWhere('code','like',"%$search%")
                    ->orWhere('adresse','like',"%$search%")
                    ->orWhere('solde','like',"%$search%")
                    ->orWhere('telephone','like',"%$search%");
            }],
            ['user_id',$user_id]
        ])
        ->orderBy('id','desc')
        ->paginate(get_limit_pagination());
        return $clients;
    }
}
