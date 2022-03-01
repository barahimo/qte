<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

use function App\Providers\get_limit_pagination;

class Commande extends Model
{
    // use SoftDeletes;
    
    // protected $fillable = ["cadre","avance","reste","solde"];

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function reglement() 
    {
        return $this->belongsTo(Reglement::class);
    }

    public function Produit() 
    {
        return $this->belongsToMany(Produit::class);
    }
    public function Facture() 
    {
        // return $this->hasMany(Facture::class);
        // return $this->belongsTo(Facture::class);
        return $this->hasOne(Facture::class);
    }
    // protected $date = ['deleted_at'];


    public function reglements() 
    {
        return $this->hasMany(Reglement::class);
    }

    public static function getCommandes()
    {
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';
        
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $commandes = Commande::with(['client','reglements'])
            ->where('user_id',$user_id)
            ->whereBetween('date', [$dateFrom, $date])
            ->orderBy('id','desc')
            ->paginate(get_limit_pagination());
        return $commandes;
    }

    public static function searchCommande($facture,$status,$client,$search,$from,$to)
    {
        // ------------------------------------
        // $facture = $request->facture;//f - nf - all - null
        // $status = $request->status;//r - nr - all - null
        // $client = $request->client;
        // $search = $request->search;//f - nf - all - null
        // ------------------------------------
        // ------------------------------------
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $r = Commande::with(['client','reglements'])->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nr = Commande::with(['client','reglements'])->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $f = Commande::with(['client','reglements'])->where('facture','f')->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nf = Commande::with(['client','reglements'])->where('facture','nf')->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $fr = Commande::with(['client','reglements'])->where('facture','f')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $fnr = Commande::with(['client','reglements'])->where('facture','f')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nfr = Commande::with(['client','reglements'])->where('facture','nf')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nfnr = Commande::with(['client','reglements'])->where('facture','nf')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        // ------------------------------------
        if($search){
            $commandes = Commande::with(['client','reglements'])
                ->where(function ($query) use ($search,$user_id) {
                        $query->where('code','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%')
                        ->orWhere('facture','like','%'.$search.'%')
                        ->orWhere('total','like','%'.$search.'%')
                        ->orWhere('avance','like','%'.$search.'%')
                        ->orWhere('reste','like','%'.$search.'%')
                        ->orWhereHas('client', function($query) use ($search,$user_id)  {
                            $query->where([['nom_client','like','%'.$search.'%'],['user_id',$user_id]]);
                        });
                    })
                ->where('user_id',$user_id)
                ->whereBetween('date', [$from, $to])
                ->orderBy('id','desc')
                ->paginate(get_limit_pagination());
        }
        else{
            if($client){
                if(!$facture && !$status)  //echo '[]';
                    $commandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $commandes = $r->where('client_id',$client)->paginate(get_limit_pagination());
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $commandes = $nr->where('client_id',$client)->paginate(get_limit_pagination());
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $commandes = Commande::with(['client','reglements'])->where('client_id',$client)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc')->paginate(get_limit_pagination());
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $commandes = $f->where('client_id',$client)->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $commandes = $fr->where('client_id',$client)->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $commandes = $fnr->where('client_id',$client)->paginate(get_limit_pagination());
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $commandes = $nf->where('client_id',$client)->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $commandes = $nfr->where('client_id',$client)->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $commandes = $nfnr->where('client_id',$client)->paginate(get_limit_pagination());
                else //echo '[]';
                    $commandes = [];
            }
            else{
                if(!$facture && !$status)  //echo '[]';
                    $commandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $commandes = $r->paginate(get_limit_pagination());
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $commandes = $nr->paginate(get_limit_pagination());
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $commandes = Commande::with(['client','reglements'])->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc')->paginate(get_limit_pagination());
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $commandes = $f->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $commandes = $fr->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $commandes = $fnr->paginate(get_limit_pagination());
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $commandes = $nf->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $commandes = $nfr->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $commandes = $nfnr->paginate(get_limit_pagination());
                else //echo '[]';
                    $commandes = [];
            }
        } 
        // ------------------------------------
        return $commandes;
    }

}
