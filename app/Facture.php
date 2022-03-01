<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use function App\Providers\get_limit_pagination;


class Facture extends Model
{
    protected $fillable = ["cadre","avance","reste","solde"];

    public function Commande() 
    {
        // return $this->hasOne(Commande::class);
        return $this->belongsTo(Commande::class);
    }

    public static function getFactures()
    {
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $factures = Facture::with(['commande' => function ($query) {
            $query->with('client')->get();
        }])
        ->where('user_id',$user_id)
        ->whereBetween('date', [$dateFrom, $date])
        ->orderBy('id','desc')
        ->paginate(get_limit_pagination());
        return $factures;
    }
  
    public static function searchFacture($search,$from,$to)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $factures = Facture::with(['commande' => function ($query) {
            $query->with('client')->get();
        }])
        ->where(function ($query) use ($search,$user_id) {
            $query->where('code','like',"%$search%")
            ->orWhere('date','like',"%$search%")
            ->orWhere('total_HT','like',"%$search%")
            ->orWhere('total_TVA','like',"%$search%")
            ->orWhere('total_TTC','like',"%$search%")
            ->orWhereHas('commande',function($query) use($search,$user_id){
                $query->where([
                    ['code','like',"%$search%"],
                    ['user_id',$user_id]
                    ])
                    ->orWhereHas('client',function($query) use($search,$user_id){
                        $query->where([
                            ['nom_client','like',"%$search%"],
                            ['user_id',$user_id]
                        ]);
                    });
                });
            },
        )
        ->where('user_id',$user_id)
        ->whereBetween('date', [$from, $to])
        ->orderBy('id','desc')
        ->paginate(get_limit_pagination());
        return $factures;
    }
}

