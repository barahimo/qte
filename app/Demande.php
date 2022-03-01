<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use function App\Providers\get_limit_pagination;


class Demande extends Model
{
    // use SoftDeletes;
    
    // protected $fillable = [];

    public function fournisseur() 
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function Produit() 
    {
        return $this->belongsToMany(Produit::class);
    }

    public function payement() 
    {
        return $this->belongsTo(Payement::class);
    }

    // public function Facture() 
    // {
    //     // return $this->hasMany(Facture::class);
    //     // return $this->belongsTo(Facture::class);
    //     return $this->hasOne(Facture::class);
    // }
    // protected $date = ['deleted_at'];

    public function payements() 
    {
        return $this->hasMany(Payement::class);
    }


    public static function getDemandes()
    {
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';
        
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $demandes = Demande::with(['fournisseur','payements'])
            ->where('user_id',$user_id)
            ->orderBy('id','desc')
            ->whereBetween('date', [$dateFrom, $date])
            ->paginate(get_limit_pagination());
        return $demandes;
    }

    public static function searchDemande($facture,$status,$fournisseur,$search,$from,$to)
    {
        // ------------------------------------
        // $facture = $request->facture;//f - nf - all - null
        // $status = $request->status;//r - nr - all - null
        // $fournisseur = $request->fournisseur;
        // $search = $request->search;//f - nf - all - null
        // ------------------------------------
        // ------------------------------------
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $r = Demande::with(['fournisseur','payements'])->where('reste','<=',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nr = Demande::with(['fournisseur','payements'])->where('reste','>',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $f = Demande::with(['fournisseur','payements'])->where('facture','!=','')->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nf = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $fr = Demande::with(['fournisseur','payements'])->where('facture','!=','')->where('reste','<=',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $fnr = Demande::with(['fournisseur','payements'])->where('facture','!=','')->where('reste','>',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nfr = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->where('reste','<=',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        $nfnr = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->where('reste','>',0)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc');
        // ------------------------------------
        if($search){
            $demandes = Demande::with(['fournisseur','payements'])
                ->where(function ($query) use ($search,$user_id) {
                        $query->where('code','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%')
                        ->orWhere('facture','like','%'.$search.'%')
                        ->orWhere('total','like','%'.$search.'%')
                        ->orWhere('avance','like','%'.$search.'%')
                        ->orWhere('reste','like','%'.$search.'%')
                        ->orWhereHas('fournisseur', function($query) use ($search,$user_id)  {
                            $query->where([['nom_fournisseur','like','%'.$search.'%'],['user_id',$user_id]]);
                        });
                    })
                ->where('user_id',$user_id)
                ->whereBetween('date', [$from, $to])
                ->orderBy('id','desc')
                ->paginate(get_limit_pagination());
        }
        else{
            if($fournisseur){
                if(!$facture && !$status)  //echo '[]';
                    $demandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $demandes = $r->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $demandes = $nr->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $demandes = Demande::with(['fournisseur','payements'])->where('fournisseur_id',$fournisseur)->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc')->paginate(get_limit_pagination());
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $demandes = $f->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $demandes = $fr->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $demandes = $fnr->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $demandes = $nf->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $demandes = $nfr->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $demandes = $nfnr->where('fournisseur_id',$fournisseur)->paginate(get_limit_pagination());
                else //echo '[]';
                    $demandes = [];
            }
            else{
                if(!$facture && !$status)  //echo '[]';
                    $demandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $demandes = $r->paginate(get_limit_pagination());
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $demandes = $nr->paginate(get_limit_pagination());
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $demandes = Demande::with(['fournisseur','payements'])->where('user_id',$user_id)->whereBetween('date', [$from, $to])->orderBy('id','desc')->paginate(get_limit_pagination());
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $demandes = $f->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $demandes = $fr->paginate(get_limit_pagination());
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $demandes = $fnr->paginate(get_limit_pagination());
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $demandes = $nf->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $demandes = $nfr->paginate(get_limit_pagination());
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $demandes = $nfnr->paginate(get_limit_pagination());
                else //echo '[]';
                    $demandes = [];
            }
        } 
        // ------------------------------------
        return $demandes;
    }
}
