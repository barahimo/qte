<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Client;
use App\Facture;
use App\Commande;
use App\Company;
use App\Lignecommande;
use App\Lignedemande;
use App\Produit;
use App\Reglement;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('statususer');
    }

    /** 
    *--------------------------------------------------------------------------
    * Mes fonctions
    *--------------------------------------------------------------------------
    **/
    public function updateQuantite($produit_id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $entree = Lignedemande::selectRaw('count(quantite) as qte_count_entree,sum(quantite) as qte_sum_entree')->where('produit_id','=',$produit_id)->where('user_id',$user_id)->first();
        $sortie = lignecommande::selectRaw('count(quantite) as qte_count_sortie,sum(quantite) as qte_sum_sortie')->where('produit_id','=',$produit_id)->where('user_id',$user_id)->first();
        $qte_count_entree = $entree->qte_count_entree;
        $qte_count_sortie = $sortie->qte_count_sortie;
        $qte_sum_entree = $entree->qte_sum_entree;
        $qte_sum_sortie = $sortie->qte_sum_sortie;
        $nentree = 0;
        $nsortie = 0;
        if($qte_count_entree>0) $nentree = $qte_sum_entree;
        if($qte_count_sortie>0) $nsortie = $qte_sum_sortie;
        $quantite =  ($nentree - $nsortie);
        return $quantite;
    }
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

    public function getAdresse($company){
        // ############################################################### //
        $adresse1 = '';
        // ############################################################### //
        ($company && ($company->nom || $company->nom != null)) ? $adresse1 .= 'Siège social : '.$company->nom.' - ' : $adresse1 .= 'Siège social : nom_societé';
        // -------------------------------------//
        ($company && ($company->adresse || $company->adresse != null)) ? $adresse1 .= $company->adresse.' , ' : $adresse1 .= '';
        // -------------------------------------//
        ($company && ($company->code_postal || $company->code_postal != null)) ? $adresse1 .= $company->code_postal.' , ' : $adresse1 .= '';
        // -------------------------------------//
        ($company && ($company->ville || $company->ville != null)) ?  $adresse1 .= $company->ville.' , ' : $adresse1 .= '';
        // -------------------------------------//
        ($company && ($company->pays || $company->pays != null)) ? $adresse1 .= $company->pays : $adresse1 .= '';
        // ############################################################### //
        $adresse2 = '';
        // ############################################################### //
        ($company && ($company->capital || $company->capital != null)) ? $adresse2 .= 'Capital : '.$company->capital.' - ' : $adresse2 .= '';
        // -------------------------------------//
        ($company && ($company->ice || $company->ice != null)) ? $adresse2 .= 'ICE : '.$company->ice.' - ' : $adresse2 .= '';
        // -------------------------------------//
        ($company && ($company->iff || $company->iff != null)) ? $adresse2 .= 'I.F. : '.$company->iff.' - ' : $adresse2 .= '';
        // ############################################################### //
        $adresse3 = '';
        // ############################################################### //
        ($company && ($company->rc || $company->rc != null)) ? $adresse3 .= 'R.C. : '.$company->rc.' - ' : $adresse3 .= '';
        // -------------------------------------//
        ($company && ($company->patente || $company->patente != null)) ? $adresse3 .= 'Patente : '.$company->patente.' - ' : $adresse3 .= '';
        // -------------------------------------//
        ($company && ($company->cnss || $company->cnss != null)) ? $adresse3 .= 'CNSS : '.$company->cnss.' - ' : $adresse3 .= '';
        // ############################################################### //
        $adresse4 = '';
        // ############################################################### //
        ($company && ($company->tel || $company->tel != null)) ? $adresse4 .= 'Tél : '.$company->tel.' - ' : $adresse4 .= '';
        // -------------------------------------//
        ($company && ($company->site || $company->site != null)) ? $adresse4 .= 'Site : '.$company->site.' - ' : $adresse4 .= '';
        // -------------------------------------//
        ($company && ($company->email || $company->email != null)) ? $adresse4 .= 'Email : '.$company->email.' - ' : $adresse4 .= '';
        // ############################################################### //
        $adresse5 = '';
        // ############################################################### //
        ($company && ($company->banque || $company->banque != null)) ? $adresse5 .= 'BANQUE : '.$company->banque.' - ' : $adresse5 .= '';
        // -------------------------------------//
        ($company && ($company->rib || $company->rib != null)) ? $adresse5 .= 'RIB : '.$company->rib.' - ' : $adresse5 .= '';
        // ############################################################### //
        $adresse = '';
        if($adresse1 != '')
            $adresse .= $adresse1.'<br>';
        if($adresse2 != '')
            $adresse .= $adresse2.'<br>';
        if($adresse3 != '')
            $adresse .= $adresse3.'<br>';
        if($adresse4 != '')
            $adresse .= $adresse4.'<br>';
        if($adresse5 != '')
            $adresse .= $adresse5.'<br>';
        return $adresse;
    }
    /** 
    *--------------------------------------------------------------------------
    * searchFactureWithDate
    *--------------------------------------------------------------------------
    **/
    public function searchFactureWithDate(Request $request){
        $search = $request->search;
        $from = $request->from;
        $to = $request->to;
        
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
        ->get();

        return $factures; 
    }
    /** 
    *--------------------------------------------------------------------------
    * searchFacture
    *--------------------------------------------------------------------------
    **/
    public function searchFacture(Request $request){
        $search = $request->search;
        
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $factures = Facture::with(['commande' => function ($query) {
                $query->with('client')->get();
            }])
            ->where([
                [function ($query) use ($search) {
                    $query->where('code','like',"%$search%")
                    ->orWhere('date','like',"%$search%")
                        ->orWhere('total_HT','like',"%$search%")
                        ->orWhere('total_TVA','like',"%$search%")
                        ->orWhere('total_TTC','like',"%$search%");
                }],
                ['user_id',$user_id]
            ])
            ->orWhereHas('commande',function($query) use($search,$user_id){
                $query->where([['code','like',"%$search%"],['user_id',$user_id]])
                    ->orWhereHas('client',function($query) use($search,$user_id){
                    $query->where([['nom_client','like',"%$search%"],['user_id',$user_id]]);
                });
            })
            ->orderBy('id','desc')
            ->get();
        return $factures; 
    }
    /** 
    *--------------------------------------------------------------------------
    * getBalance
    *--------------------------------------------------------------------------
    **/
    public function getFacture(Request $request)
    {
        // $from = date('2021-08-09');
        // $to = date('2021-08-10');
        $from = $request->from;
        $to = $request->to;

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        
            $factures = Facture::with(['commande' => function ($query) {
                $query->with('client')->get();
            }])
            ->whereBetween('date', [$from, $to])
            ->where('user_id',$user_id)
            ->get();    
        return compact('factures');
    }
    /** 
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;

        $factures_paginate = Facture::getFactures();
        
        $data = Facture::with(['commande' => function ($query) {
            $query->with('client')->get();
        }])
        ->where('user_id',$user_id)
        ->whereBetween('date', [$dateFrom, $date])
        ->orderBy('id','desc');
        $totaux_ttc = $data->sum('total_TTC');
        $totaux_ht = $data->sum('total_HT');
        $factures = $data->get();

        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        if($this->hasPermssion('list6') == 'yes')
        return view('managements.factures.index', compact(['factures','factures_paginate','totaux_ttc','totaux_ht','permission','date','dateFrom','dateTo','company']));
        else
        return view('application');
    }

    function fetch_facture(Request $request){
        if($request->ajax()){
            $search = $request->search;
            $from = $request->from;
            $to = $request->to;
            $factures_paginate = Facture::searchFacture($search,$from,$to);
            return view('managements.factures.index_data',compact('factures_paginate'))->render();
        }
    }

    public function index_save(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $date = Carbon::now();
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $factures = Facture::with(['commande' => function ($query) {
            $query->with('client')->get();
        }])
        ->where('user_id',$user_id)
        ->orderBy('id','desc')->get();
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        if($this->hasPermssion('list6') == 'yes')
        return view('managements.factures.index', compact(['factures','permission','date','company']));
        else
        return view('application');
    }

    public function create(Request $request)
    {
        $date = Carbon::now();
        $datecomplet = $date->isoFormat('YYYY-MM-DD');
        $year = $date->isoFormat('YY');
        $month = $date->isoFormat('MM');
        // -------- test la date -------- //
        // $time = strtotime('02/16/2023');
        // $date = date('Y-m-d',$time);
        // $year = date('y',$time);
        // $month = date('m',$time);
        // ---------------------        
        // $factures = Facture::where('code', 'like', "FA-$year%")->get(); //
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $factures = Facture::where('code', 'like', "FA-$year%")->where('user_id',$user_id)->get();
        // ---------------------------------------
        if(count($factures)>0) {
            $tab=array();
            foreach ($factures as $key=>$facture) {
                $fcode = $facture->code;
                $list = explode("-",$fcode);
                $n = $list[2];
                array_push($tab,$n);
            }
            $index = -1;
            foreach ($factures as $key=>$facture) {
                $fcode = $facture->code;
                $list = explode("-",$fcode);
                $n = $list[2];
                if(max($tab) == $n){
                    $index = $key;
                    break;
                }
            }
            $fcode =  $factures[$index]->code;
        } 
        else {
            $fcode = null;
        }
        // ---------------------------------------
        $str = 1;
        if(isset($fcode)){
            $list = explode("-",$fcode);
            // $f = $list[0];
            $y = substr($list[1],0,2);
            // $m = substr($list[1],2,2);
            $n = $list[2];
            ($y == $year) ? $str = $n+1 : $str = 1;
        } 
        $pad = str_pad($str,4,"0",STR_PAD_LEFT);
        $code = 'FA-'.$year.''.$month.'-'.$pad;
        // ---------------------        
        $categories=Categorie::where('user_id',$user_id)->get();//get data from table
        $clients = Client::where('user_id',$user_id)->get();
        if($this->hasPermssion('create4') == 'yes')
        return view('managements.factures.create', [
            'clients' =>$clients,
            'categories' => $categories,
            'date' => $date,
            'code' => $code,
        ]);
        else
        return redirect()->back();
    }

    public function store(Request $request)
    { 
        $code_facture = $request->input('code_facture');
        // ################################################################################### //
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $getFactures = Facture::where('user_id',$user_id)->get();
        // ################################################################################### //
        $list = explode("-",$code_facture);
        //  ##########################################################//
        //  ----- Vérifier si le code de la facture est valide ------ //
        //  ##########################################################//
        if($code_facture && count($list) == 3 && $list[0] == 'FA' && is_numeric($list[1]) && is_numeric($list[2])){
            $y1 = substr($list[1],0,2);
            $n1 = $list[2];
            $txt1 = $y1.'-'.$n1;
            $existe = false;
            foreach ($getFactures as $key=>$facture) {
                $fcode = $facture->code;
                $list = explode("-",$fcode);
                $y2 = substr($list[1],0,2);
                $n2 = $list[2];
                $txt2 = $y2.'-'.$n2;
                if($txt1 == $txt2){
                    $existe = true;
                    break;
                }
            }
            //  ##################################################################//
            //  ----- Vérifier si le code de la facture existe déja ou pas ------ //
            //  ##################################################################//
            if(!$existe){
                // ################################################################################### //
                // ################################################################################### //
                // ################################################################################### //
                $lignes = $request->input('lignes');
                $date = $request->input('date');
                $client = $request->input('client');
                // $gauche = $request->input('gauche');
                // --------------------- //
                $sphere_gauche_loin=$request->input('sphere_gauche_loin');
                $cylindre_gauche_loin=$request->input('cylindre_gauche_loin');
                $axe_gauche_loin=$request->input('axe_gauche_loin');
                $lentille_gauche_loin=$request->input('lentille_gauche_loin');
                $eip_gauche_loin=$request->input('eip_gauche_loin');
                $hauteur_gauche_loin=$request->input('hauteur_gauche_loin');
                // --------------------- //
                $sphere_droite_loin=$request->input('sphere_droite_loin');
                $cylindre_droite_loin=$request->input('cylindre_droite_loin');
                $axe_droite_loin=$request->input('axe_droite_loin');
                $lentille_droite_loin=$request->input('lentille_droite_loin');
                $eip_droite_loin=$request->input('eip_droite_loin');
                $hauteur_droite_loin=$request->input('hauteur_droite_loin');
                // --------------------- //
                $sphere_gauche_pres=$request->input('sphere_gauche_pres');
                $cylindre_gauche_pres=$request->input('cylindre_gauche_pres');
                $axe_gauche_pres=$request->input('axe_gauche_pres');
                $lentille_gauche_pres=$request->input('lentille_gauche_pres');
                $eip_gauche_pres=$request->input('eip_gauche_pres');
                $hauteur_gauche_pres=$request->input('hauteur_gauche_pres');
                // --------------------- //
                $sphere_droite_pres=$request->input('sphere_droite_pres');
                $cylindre_droite_pres=$request->input('cylindre_droite_pres');
                $axe_droite_pres=$request->input('axe_droite_pres');
                $lentille_droite_pres=$request->input('lentille_droite_pres');
                $eip_droite_pres=$request->input('eip_droite_pres');
                $hauteur_droite_pres=$request->input('hauteur_droite_pres');
                // --------------------- //
                // $droite = $request->input('droite');
                $total = $request->input('total');
                $mode = $request->input('mode');
                $avance = $request->input('avance');
                $reste = $request->input('reste');
                $status = $request->input('status');
                //  #################################################//
                //  ----- Vérifier Les lignes de la commande ------ //
                //  #################################################//
                if(!empty($lignes)){
                    // -----------------------------------------------------
                    $time = strtotime($date);
                    $year = date('y',$time);
                    $month = date('m',$time);
                    // -----------------------------------------------------
                    $commandes = Commande::where('user_id',$user_id)->get();
                    (count($commandes)>0) ? $lastcode = $commandes->last()->code : $lastcode = null;
                    $str = 1;
                    if(isset($lastcode)){
                        // ----- BON-2108-0001 ----- //
                        $list = explode("-",$lastcode);
                        $y = substr($list[1],0,2);
                        $n = $list[2];
                        ($y == $year) ? $str = $n+1 : $str = 1;
                    } 
                    $pad = str_pad($str,4,"0",STR_PAD_LEFT);
                    $code_commande = 'BON-'.$year.''.$month.'-'.$pad;
                    //  ##########################################//
                    //  ----- Vérifier la date && client - ------ //
                    //  ##########################################//
                    if(!empty($date) && !empty($client) && $client != "0"){
                        if($avance>0 && $mode == ""){
                            return ['status'=>"error",'message'=>"Veuillez saisir le mode de règlement !"];
                        }
                        // ------------ Begin Commande -------- //
                        $commande = new Commande();
                        $commande->date = $date;
                        $commande->client_id = $client;
                        // $commande->oeil_gauche = $gauche;
                        $json_loin = [
                            'sphere_gauche_loin'=>$sphere_gauche_loin,
                            'cylindre_gauche_loin'=>$cylindre_gauche_loin,
                            'axe_gauche_loin'=>$axe_gauche_loin,
                            'lentille_gauche_loin'=>$lentille_gauche_loin,
                            'eip_gauche_loin'=>$eip_gauche_loin,
                            'hauteur_gauche_loin'=>$hauteur_gauche_loin,
                            // --------------------- //
                            'sphere_droite_loin'=>$sphere_droite_loin,
                            'cylindre_droite_loin'=>$cylindre_droite_loin,
                            'axe_droite_loin'=>$axe_droite_loin,
                            'lentille_droite_loin'=>$lentille_droite_loin,
                            'eip_droite_loin'=>$eip_droite_loin,
                            'hauteur_droite_loin'=>$hauteur_droite_loin,
                        ];
                        $obj_loin = json_encode($json_loin);
                        $commande->vision_loin = $obj_loin; 
                        $json_pres = [
                            'sphere_gauche_pres'=>$sphere_gauche_pres,
                            'cylindre_gauche_pres'=>$cylindre_gauche_pres,
                            'axe_gauche_pres'=>$axe_gauche_pres,
                            'lentille_gauche_pres'=>$lentille_gauche_pres,
                            'eip_gauche_pres'=>$eip_gauche_pres,
                            'hauteur_gauche_pres'=>$hauteur_gauche_pres,
                            // --------------------- //
                            'sphere_droite_pres'=>$sphere_droite_pres,
                            'cylindre_droite_pres'=>$cylindre_droite_pres,
                            'axe_droite_pres'=>$axe_droite_pres,
                            'lentille_droite_pres'=>$lentille_droite_pres,
                            'eip_droite_pres'=>$eip_droite_pres,
                            'hauteur_droite_pres'=>$hauteur_droite_pres,
                        ];
                        $obj_pres = json_encode($json_pres);
                        $commande->vision_pres = $obj_pres; 
                        // $commande->oeil_droite = $droite;
                        $commande->facture = "f"; 
                        $commande->avance = $avance;
                        $commande->reste = $reste;
                        $commande->total = $total;
                        $commande->code = $code_commande;
                        $commande->user_id = $user_id;
                        $commande->save();
                        // ------------ End Commande -------- //
                        //  ##########################################################//
                        //  ----- Vérifier si la commande est bien enregistrée ------ //
                        //  ##########################################################//
                        if($commande->id){
                            // ------------ Begin LigneCommande -------- //
                            foreach ($lignes as $ligne) {
                                $lignecommande = new Lignecommande();
                                $lignecommande->commande_id = $commande->id;
                                $lignecommande->produit_id = $ligne['prod_id'];
                                $lignecommande->prix = $ligne['prix'];
                                $lignecommande->quantite = $ligne['qte'];
                                $lignecommande->total_produit = $ligne['total'];
                                $lignecommande->user_id = $user_id;
                                $lignecommande->save();
                                ##################################################
                                ##################################################
                                $produit = Produit::where('user_id',$user_id)->findOrFail($ligne['prod_id']);
                                $qte = $this->updateQuantite($ligne['prod_id']);
                                $produit->quantite = $qte;
                                $produit->save();
                                ##################################################
                                ##################################################
                            }
                            // ------------ End LigneCommande -------- //
                            // -----------------------------------------------------
                            $time = strtotime($date);
                            $year = date('y',$time);
                            $month = date('m',$time);
                            // -----------------------------------------------------
                            $getReg = Reglement::where('user_id',$user_id)->get();
                            (count($getReg)>0) ? $lastcode = $getReg->last()->code : $lastcode = null;
                            $str = 1;
                            if(isset($lastcode)){
                                // ----- REG-2108-0001 ----- //
                                $list = explode("-",$lastcode);
                                $y = substr($list[1],0,2);
                                $n = $list[2];
                                ($y == $year) ? $str = $n+1 : $str = 1;
                            } 
                            $pad = str_pad($str,4,"0",STR_PAD_LEFT);
                            $code_reglement = 'REG-'.$year.''.$month.'-'.$pad;
                            // -----------------------------------------------------
                            // ------------ Begin Reglement -------- //
                            if($avance>0){
                                $reglement= new Reglement();
                                $reglement->commande_id = $commande->id;
                                $reglement->date = $date;
                                $reglement->mode_reglement = $mode;
                                $reglement->avance = $avance;
                                $reglement->reste = $reste;
                                $reglement->status = $status;
                                $reglement->code = $code_reglement;
                                $reglement->user_id = $user_id;
                                $reglement->save();
                            }
                            // ------------ End Reglement -------- //
                            // ################################# //
                            // ################################# //
                            $totalht = $request->input('totalht');
                            $totaltva = $request->input('totaltva');
                            // ################################# //
                            $facture = new Facture();
                            $facture->code = $code_facture;
                            $facture->commande_id = $commande->id;
                            $facture->date = $date;
                            $facture->total_HT = $totalht;
                            $facture->total_TVA = $totaltva;
                            $facture->total_TTC = $total;
                            $facture->reglement = 'à réception';
                            $facture->user_id = $user_id;
                            $facture->save();
                            
                            // ################################# //
                            // ################################# //
                            return ['status'=>"success",'message'=>"La facture a été bien enregistrée !!","facture_id"=>$facture->id];
                            // ################################# //
                            // ################################# //
                        }
                        else{
                            return ['status'=>"error",'message'=>"Problème d'enregistrement de la facture !"];
                        }
                    } 
                    else{
                        return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
                    }
                }
                else {
                    return ['status'=>"error",'message'=>"Veuillez d'ajouter des lignes de la facture"];
                }
            }
            else{
                return ['status'=>"error",'message'=>"Le code de la facture existe déja ! "];
            }
        }
        else{
            return ['status'=>"error",'message'=>"Le code de la facture est invalide !"];
        }
    }
        
    public function show(Request $request, Facture $facture){
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $adresse = $this->getAdresse($company);
        $facture = Facture::where('user_id',$user_id)->findOrFail($facture->id);
        $commande = Commande::with('client')->where('id', "=", $facture->commande_id)->first();
        $lignecommandes =  Lignecommande::with('produit')->where('commande_id', '=', $commande->id)->get();
        $prix_HT = 0;
        foreach($lignecommandes as $q){
           $prix_HT = $prix_HT +  ($q->produit->prix_produit_HT * $q->quantite);
        }
        $TVA = 0;
        foreach($lignecommandes as $q){
           $TVA = $TVA +  ($q->produit->prix_produit_HT * $q->quantite * $q->produit->TVA) ;
        }
        $priceTotal = 0;
        foreach($lignecommandes as $p){
            $priceTotal = floatval($priceTotal  + $p->total_produit) ;
        }
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('show6') == 'yes')
        return view('managements.factures.show', [
            'lignecommandes' =>  $lignecommandes,
            'priceTotal'  => $priceTotal,
            'prix_HT' => $prix_HT,
            'TVA' => $TVA,
            'commande' => $commande,
            'facture' => $facture,
            'company' => $company,
            'count' => $count,
            'adresse' => $adresse,
            'permission' => $permission
        ]);
        else
        return redirect()->back();
    }

    public function edit(Facture $facture){
        $commande = Commande::with('client')->where('id', "=", $facture->commande_id)->first();
        $lignecommandes =  Lignecommande::with('produit')->where('commande_id', '=', $commande->id)->get();
        
        $prix_HT = 0;
        foreach($lignecommandes as $q){
           $prix_HT = $prix_HT +  ($q->produit->prix_produit_HT * $q->quantite);
        }

    
        $TVA = 0;
        foreach($lignecommandes as $q){
           $TVA = $TVA +  ($q->produit->prix_produit_HT * $q->quantite * $q->produit->TVA) ;
        }

        $priceTotal = 0;
        foreach($lignecommandes as $p){
            $priceTotal = floatval($priceTotal  + $p->total_produit) ;
            $p->nom_produit = $p->produit->nom_produit;
        }

        return view('managements.factures.edit')->with([
            "facture" => $facture,
            'lignecommandes' =>  $lignecommandes,
            'priceTotal'  => $priceTotal,
            'prix_HT' => $prix_HT,
            'TVA' => $TVA,
            'commande' => $commande
        ]);
    }
    
    public function update(Request $request, Facture $facture){
        $user_id = Auth::user()->id;
        $facture->total_HT = $request->input('total_HT');
        $facture->total_TVA = $request->input('total_TVA');
        $facture->total_TTC = $request->input('total_TTC');
        $facture->commande_id = $request->input('commande_id');
        $facture->reglement = $request->input('reglement');
        $facture->user_id = $user_id;
        $facture->save();


        return redirect()->route('reglement.index')->with([
            "status" => "la facture a été bien modifier !! veuillez modifier le reglement de la commande N°" .$facture->commande_id
        ]); 
    }
    
    public function destroy(Facture $facture){
        $facture->commande->facture = 'nf';
        $facture->commande->save();
        $facture->delete();
        return redirect()->route('facture.index')->with(["status" => "La facture a été supprimée avec succès !"]) ; 
    }
    /** 
    *--------------------------------------------------------------------------
    * Autres fonctions
    *--------------------------------------------------------------------------
    **/ 
// ----------------------------------------------
}
