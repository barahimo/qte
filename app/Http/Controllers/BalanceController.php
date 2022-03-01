<?php

namespace App\Http\Controllers;

use App\Balance;
use App\Categorie;
use App\Commande;
use App\Company;
use App\Demande;
use App\Lignecommande;
use App\Lignedemande;
use App\Payement;
use App\Produit;
use App\Reglement;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('statususer');
    }
    /** 
    *--------------------------------------------------------------------------
    * Mes Fonctions
    *--------------------------------------------------------------------------
    **/
    public function getPermssion($string){
        $list = [];
        $array = explode("','",$string);
        foreach ($array as $value) 
            foreach (explode("['",$value) as $val) 
                if($val != '')
                    array_push($list, $val);
        $array = $list;
        $list = [];
        foreach ($array as $value) 
            foreach (explode("']",$value) as $val) 
                if($val != '')
                    array_push($list, $val);
        return $list;
    }

    public function hasPermssion($string){
        $permission = $this->getPermssion(Auth::user()->permission);
        $permission_ = $this->getPermssion(User::find(Auth::user()->user_id)->permission);
        $result = 'no';
        if(
            (Auth::user()->is_admin == 2) ||
            (Auth::user()->is_admin == 1 && in_array($string,$permission)) ||
            (Auth::user()->is_admin == 0 && in_array($string,$permission) && in_array($string,$permission_))
        )
        $result = 'yes';
        return $result;
    }
    /** 
    *--------------------------------------------------------------------------
    * productsCategoryBalance
    *--------------------------------------------------------------------------
    **/
    public function productsCategoryBalance(Request $request){
        $data=Produit::where('categorie_id',$request->id)->get();
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * BALANCE
    *--------------------------------------------------------------------------
    **/
    public function balance()
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lists = Commande::with('client')
            ->where('user_id',$user_id)
            ->paginate(3);
        $commandes = Commande::with('client')
            ->where('user_id',$user_id)
            ->get();
        $reglements = Reglement::with(['commande' => function($query){$query->with('client');}])
            ->where('user_id',$user_id)
            ->get();
        $demandes = Demande::with('fournisseur')
            ->where('user_id',$user_id)
            ->get();
        $payements = Payement::with(['demande' => function($query){$query->with('fournisseur');}])
            ->where('user_id',$user_id)
            ->get();
        // ########################### //
        $obj = [];
        $total_debit = 0;
        $total_credit = 0;
        foreach ($commandes as $key => $value) {
            $total_debit = $total_debit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->client->nom_client,
                'nature'=>'BL',
                'code'=>$value->code,
                'debit'=>$value->total,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        foreach ($reglements as $key => $value) {
            $total_credit = $total_credit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->commande->client->nom_client,
                'nature'=>'RC',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->avance,
            ];
            array_push($obj,$json);
        }
        foreach ($demandes as $key => $value) {
            $total_credit = $total_credit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->fournisseur->nom_fournisseur,
                'nature'=>'BA',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->total,
            ];
            array_push($obj,$json);
        }
        foreach ($payements as $key => $value) {
            $total_debit = $total_debit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->demande->fournisseur->nom_fournisseur,
                'nature'=>'RF',
                'code'=>$value->code,
                'debit'=>$value->avance,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        $obj = json_decode(json_encode($obj));
        $obj = $this->sorting($obj,'date','date','desc');
        $data = $this->paginate($obj);
        return view(
            'managements.balances.balance',
            // compact('total_debit','total_credit','obj')
            // compact('obj')
            compact('commandes','demandes','obj','data','lists')
        );
    }
    /** 
    *--------------------------------------------------------------------------
    * getMouvement
    *--------------------------------------------------------------------------
    **/
    public function getMouvement(Request $request)
    {
        // $from = date('2021-08-09');
        // $to = date('2021-08-10');
        $from = $request->from;
        $to = $request->to;

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commandes = Commande::with('client')
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $reglements = Reglement::with(['commande' => function($query){$query->with('client');}])
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $demandes = Demande::with('fournisseur')
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        $payements = Payement::with(['demande' => function($query){$query->with('fournisseur');}])
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();
        // ########################### //
        $obj = [];
        $total_debit = 0;
        $total_credit = 0;
        foreach ($commandes as $key => $value) {
            $total_debit = $total_debit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->client->nom_client,
                'nature'=>'BL',
                'code'=>$value->code,
                'debit'=>$value->total,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        foreach ($reglements as $key => $value) {
            $total_credit = $total_credit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->commande->client->nom_client,
                'nature'=>'RC',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->avance,
            ];
            array_push($obj,$json);
        }
        foreach ($demandes as $key => $value) {
            $total_credit = $total_credit + $value->total;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->fournisseur->nom_fournisseur,
                'nature'=>'BA',
                'code'=>$value->code,
                'debit'=>null,
                'credit'=>$value->total,
            ];
            array_push($obj,$json);
        }
        foreach ($payements as $key => $value) {
            $total_debit = $total_debit + $value->avance;
            $json = [
                'date'=>$value->date,
                'nom'=>$value->demande->fournisseur->nom_fournisseur,
                'nature'=>'RF',
                'code'=>$value->code,
                'debit'=>$value->avance,
                'credit'=>null,
            ];
            array_push($obj,$json);
        }
        // return compact('commandes','reglements','demandes','payements');
        return compact('total_debit','total_credit','obj');
    }
    public function getMouvementPrint(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $type = $request->type;
        $link = $request->link;
        $order = $request->order;

        $mouvements = Balance::getMouvementPrint($from,$to,$type,$link,$order);
        $total_debit = Balance::dataMouvements($from,$to)['total_debit'];
        $total_credit = Balance::dataMouvements($from,$to)['total_credit'];

        return compact('mouvements','total_debit','total_credit');
    }
    /** 
    *--------------------------------------------------------------------------
    * mouvement
    *--------------------------------------------------------------------------
    **/
    public function mouvement()
    {
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';

        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        $data = Balance::getDataMouvements($dateFrom,$date);
        $mouvements = Balance::dataMouvements($dateFrom,$date)['data'];
        $total_debit = Balance::dataMouvements($dateFrom,$date)['total_debit'];
        $total_credit = Balance::dataMouvements($dateFrom,$date)['total_credit'];

        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $date = Carbon::now();
        if($this->hasPermssion('list7') == 'yes')
        return view('managements.balances.mouvement',compact('dateFrom','dateTo','date','company','permission','data','mouvements','total_debit','total_credit'));
        else
        return view('application');
    }
    public function fetch_mouvement(Request $request)
    {
        if($request->ajax()){
            $from = $request->from;
            $to = $request->to;
            $type = $request->type;
            $link = $request->link;
            $order = $request->order;
            $data = Balance::fetchDataMouvements($from,$to,$type,$link,$order);
            $total_debit = Balance::dataMouvements($from,$to)['total_debit'];
            $total_credit = Balance::dataMouvements($from,$to)['total_credit'];
            return view('managements.balances.mouvement_data',compact('data','total_debit','total_credit'))->render();
        }   
    }
    public function mouvement_save()
    {
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $date = Carbon::now();
        if($this->hasPermssion('list7') == 'yes')
        return view('managements.balances.mouvement',compact('date','company','permission'));
        else
        return view('application');
    }
    /** 
    *--------------------------------------------------------------------------
    * getInventaire
    *--------------------------------------------------------------------------
    **/
    public function getInventaire(Request $request)
    {
        // $from = date('2021-08-09');
        // $to = date('2021-08-10');
        $from = $request->from;
        $to = $request->to;
        $produit_id = $request->produit_id;

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        if($produit_id > 0){
            $lignesdemandes = Lignedemande::with(['demande'=>
                function ($query) {$query->with('fournisseur');},'produit'])
            ->whereHas('demande',function ($query) use($from,$to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->where('produit_id',$produit_id)
            ->where('user_id',$user_id)
            ->get();
            $lignescommandes = Lignecommande::with(['commande'=>
                function ($query) {$query->with('client');},'produit'])
            ->whereHas('commande',function ($query) use($from,$to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->where('produit_id',$produit_id)
            ->where('user_id',$user_id)
            ->get();
        }
        else{
            $lignesdemandes = Lignedemande::with(['demande'=>
                function ($query) {$query->with('fournisseur');},'produit'])
            ->whereHas('demande',function ($query) use($from,$to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->where('user_id',$user_id)
            ->get();
            $lignescommandes = Lignecommande::with(['commande'=>
                function ($query) {$query->with('client');},'produit'])
            ->whereHas('commande',function ($query) use($from,$to) {
                $query->whereBetween('date', [$from, $to]);
            })
            ->where('user_id',$user_id)
            ->get();
        }
        $obj = [];
        $quantite_entree = 0;
        $quantite_sortie = 0;
        $total_entree = 0;
        $total_sortie = 0;
        foreach ($lignesdemandes as $key => $value) {
            $quantite_entree = $total_entree + $value->quantite;
            $total_entree = $total_entree + $value->total_produit;
            $json = [
                'id'=>$value->id,
                'date'=>$value->demande->date,
                'produit_id'=>$value->produit_id,
                'ref_produit'=>$value->produit->code_produit,
                'nom_produit'=>$value->produit->nom_produit,
                'type'=>'Entrée',
                'nom'=>$value->demande->fournisseur->nom_fournisseur,
                'prix'=>$value->prix,
                'quantite'=>$value->quantite,
                'total'=>$value->total_produit,
            ];
            array_push($obj,$json);
        }
        foreach ($lignescommandes as $key => $value) {
            $quantite_sortie = $total_sortie + $value->quantite;
            $total_sortie = $total_sortie + $value->total_produit;
            $json = [
                'id'=>$value->id,
                'date'=>$value->commande->date,
                'produit_id'=>$value->produit_id,
                'ref_produit'=>$value->produit->code_produit,
                'nom_produit'=>$value->produit->nom_produit,
                'type'=>'Sortie',
                'nom'=>$value->commande->client->nom_client,
                'prix'=>$value->prix,
                'quantite'=>$value->quantite,
                'total'=>$value->total_produit,
            ];
            array_push($obj,$json);
        }
        return compact('quantite_entree','quantite_sortie','total_entree','total_sortie','obj');
    }
    public function getInventairePrint(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $category_id = $request->category_id;
        $produit_id = $request->produit_id;
        $type = $request->type;
        $link = $request->link;
        $order = $request->order;

        $inventaires = Balance::getInventairesPrint($from,$to,$category_id,$produit_id,$type,$link,$order);
        $quantite_entree = Balance::dataInventaires($from,$to,$category_id,$produit_id)['quantite_entree'];
        $quantite_sortie = Balance::dataInventaires($from,$to,$category_id,$produit_id)['quantite_sortie'];
        $total_entree = Balance::dataInventaires($from,$to,$category_id,$produit_id)['total_entree'];
        $total_sortie = Balance::dataInventaires($from,$to,$category_id,$produit_id)['total_sortie'];
        return compact('inventaires','quantite_entree','quantite_sortie','total_entree','total_sortie');
    }
    /** 
    *--------------------------------------------------------------------------
    * inventaire
    *--------------------------------------------------------------------------
    **/
    public function inventaire()
    {
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';

        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $produit_id = 0;
        $category_id = 0;

        $data = Balance::getDataInventaires($dateFrom,$date,$category_id,$produit_id);
        $inventaires = Balance::dataInventaires($dateFrom,$date,$category_id,$produit_id)['data'];
        $quantite_entree = Balance::dataInventaires($dateFrom,$date,$category_id,$produit_id)['quantite_entree'];
        $quantite_sortie = Balance::dataInventaires($dateFrom,$date,$category_id,$produit_id)['quantite_sortie'];
        $total_entree = Balance::dataInventaires($dateFrom,$date,$category_id,$produit_id)['total_entree'];
        $total_sortie = Balance::dataInventaires($dateFrom,$date,$category_id,$produit_id)['total_sortie'];

        $categories=Categorie::where('user_id',$user_id)->get();//get data from table
        
        if($this->hasPermssion('list7_2') == 'yes')
        return view('managements.balances.inventaire',compact('dateFrom','dateTo','date','data','inventaires','quantite_entree','quantite_sortie','total_entree','total_sortie','company','permission','categories'));
        else
        return view('application');
    }
    public function fetch_inventaire(Request $request)
    {
        if($request->ajax()){
            $from = $request->from;
            $to = $request->to;
            $category_id = $request->category_id;
            $produit_id = $request->produit_id;
            $type = $request->type;
            $link = $request->link;
            $order = $request->order;
            $data = Balance::fetchDataInventaires($from,$to,$category_id,$produit_id,$type,$link,$order);
            $quantite_entree = Balance::dataInventaires($from,$to,$category_id,$produit_id)['quantite_entree'];
            $quantite_sortie = Balance::dataInventaires($from,$to,$category_id,$produit_id)['quantite_sortie'];
            $total_entree = Balance::dataInventaires($from,$to,$category_id,$produit_id)['total_entree'];
            $total_sortie = Balance::dataInventaires($from,$to,$category_id,$produit_id)['total_sortie'];

            return view('managements.balances.inventaire_data',compact('data','quantite_entree','quantite_sortie','total_entree','total_sortie'))->render();
        }   
    }
    public function inventaire_save()
    {
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $date = Carbon::now();

        $categories=Categorie::where('user_id',$user_id)->get();//get data from table
        
        if($this->hasPermssion('list7_2') == 'yes')
        return view('managements.balances.inventaire',compact('date','company','permission','categories'));
        else
        return view('application');
    }
}
