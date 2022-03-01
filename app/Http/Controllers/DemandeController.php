<?php

namespace App\Http\Controllers;

use App\Categorie;
use App\Demande;
use App\Fournisseur;
use App\Lignecommande;
use App\Lignedemande;
use App\Payement;
use App\Produit;
use App\Stock;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\Providers\get_limit_pagination;

class DemandeController extends Controller
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
    public function updateQuantite($produit_id){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $entree = Lignedemande::selectRaw('count(quantite) as qte_count_entree,sum(quantite) as qte_sum_entree')->where('produit_id','=',$produit_id)->where('user_id',$user_id)->first();
        $sortie = Lignecommande::selectRaw('count(quantite) as qte_count_sortie,sum(quantite) as qte_sum_sortie')->where('produit_id','=',$produit_id)->where('user_id',$user_id)->first();
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
        // $txt = $nentree.' - '.$nsortie.' = '.$quantite;
        // return $txt;
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
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permission = $this->getPermssion(Auth::user()->permission);
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $demandes = Demande::getDemandes();
        $fournisseurs = Fournisseur::where('user_id',$user_id )->get();
        if($this->hasPermssion('list4_2') == 'yes')
        return view('managements.demandes.index', [
            'demandes'=>$demandes,
            'fournisseurs' =>$fournisseurs,
            'permission' =>$permission,
            'date' =>$date,
            'dateFrom' =>$dateFrom,
            'dateTo' =>$dateTo,
        ]);
        else
        return view('application');
    }

    function fetch_demande(Request $request)
    {
        if($request->ajax()){
            // ------------------------------------
            $facture = $request->facture;//f - nf - all - null
            $status = $request->status;//r - nr - all - null
            $fournisseur = $request->fournisseur;
            $search = $request->search;//f - nf - all - null
            $from = $request->from;
            $to = $request->to;
            // ------------------------------------
            $demandes = Demande::searchDemande($facture,$status,$fournisseur,$search,$from,$to);
            $permission = $this->getPermssion(Auth::user()->permission);
            return view('managements.demandes.index_data',compact(['demandes','permission']))->render();
        }
    }

    public function index_save()
    {
        // $demandes = Demande::where('facture','')->get();
        // $demandes = Demande::where('facture','not null')->get();
        // $demandes = Demande::whereNull('facture')->get();
        // $demandes = Demande::whereNotNull('facture')->get();
        // return $demandes;
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $demandes = Demande::with(['fournisseur','payements'])->where('user_id',$user_id)->get();
        $lignedemandes = Lignedemande::where('user_id',$user_id )->get();
        $payements = Payement::where('user_id',$user_id )->get();
        $fournisseurs = Fournisseur::where('user_id',$user_id )->get();
        if($this->hasPermssion('list4_2') == 'yes')
        return view('managements.demandes.index', [
            'demandes'=>$demandes,
            'lignedemandes'=>$lignedemandes,
            'payements'=>$payements,
            'fournisseurs' =>$fournisseurs,
            'permission' =>$permission,
        ]);
        else
        return view('application');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $date = Carbon::now();
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories=Categorie::where('user_id',$user_id)->get();//get data from table
        $fournisseurs = Fournisseur::where('user_id',$user_id)->get();
        if($this->hasPermssion('create4_2') == 'yes')
        return view('managements.demandes.create', [
            'fournisseurs' =>$fournisseurs,
            'categories' => $categories,
            'date' => $date,
        ]);
        else
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lignes = $request->input('lignes');
        $date = $request->input('date');
        $fournisseur = $request->input('fournisseur');
        $facture = $request->input('facture');
        $total = $request->input('total');
        $mode = $request->input('mode');
        $avance = $request->input('avance');
        $reste = $request->input('reste');
        $status = $request->input('status');
        // -----------------------------------------------------
        if(!empty($lignes)){
            // -----------------------------------------------------
            $time = strtotime($date);
            $year = date('y',$time);
            $month = date('m',$time);
            // -----------------------------------------------------
            $demandes = Demande::where('user_id',$user_id)->get();
            (count($demandes)>0) ? $lastcode = $demandes->last()->code : $lastcode = null;
            $str = 1;
            if(isset($lastcode)){
                // ----- BA-2108-0001 ----- //
                $list = explode("-",$lastcode);
                $y = substr($list[1],0,2);
                $n = $list[2];
                ($y == $year) ? $str = $n+1 : $str = 1;
            } 
            $pad = str_pad($str,4,"0",STR_PAD_LEFT);
            $code = 'BA-'.$year.''.$month.'-'.$pad;
            // -----------------------------------------------------
            if(!empty($date) && !empty($fournisseur) && $fournisseur != "0"){
                if($avance>0 && $mode == ""){
                    return ['status'=>"error",'message'=>"Veuillez saisir le mode de règlement !"];
                }
                // ------------ Begin Demande -------- //
                $demande = new Demande();
                $demande->date = $date;
                $demande->fournisseur_id = $fournisseur;
                $demande->facture = $facture;
                $demande->avance = $avance;
                $demande->reste = $reste;
                $demande->total = $total;
                $demande->code = $code;
                $demande->user_id = $user_id;
                $demande->save();
                // ------------ End Demande -------- //
                if($demande->id){
                    // ------------ Begin LigneDemande -------- //
                    foreach ($lignes as $ligne) {
                        $lignedemande = new Lignedemande();
                        $lignedemande->demande_id = $demande->id;
                        $lignedemande->produit_id = $ligne['prod_id'];
                        $lignedemande->prix = $ligne['prix'];
                        $lignedemande->quantite = $ligne['qte'];
                        $lignedemande->total_produit = $ligne['total'];
                        $lignedemande->user_id = $user_id;
                        $lignedemande->save();
                        ##################################################
                        ##################################################
                        $produit = Produit::where('user_id',$user_id)->findOrFail($ligne['prod_id']);
                        $qte = $this->updateQuantite($ligne['prod_id']);
                        $produit->quantite = $qte;
                        $produit->save();
                        ##################################################
                        ##################################################
                    }
                    // ------------ End LigneDemande -------- //
                    // -----------------------------------------------------
                    $time = strtotime($date);
                    $year = date('y',$time);
                    $month = date('m',$time);
                    // -----------------------------------------------------
                    $getReg = Payement::where('user_id',$user_id)->get();
                    (count($getReg)>0) ? $lastcode = $getReg->last()->code : $lastcode = null;
                    $str = 1;
                    if(isset($lastcode)){
                        // ----- RF-2108-0001 ----- //
                        $list = explode("-",$lastcode);
                        $y = substr($list[1],0,2);
                        $n = $list[2];
                        ($y == $year) ? $str = $n+1 : $str = 1;
                    } 
                    $pad = str_pad($str,4,"0",STR_PAD_LEFT);
                    $code = 'RF-'.$year.''.$month.'-'.$pad;
                    // -----------------------------------------------------
                    // ------------ Begin Payement -------- //
                    if($avance>0){
                        $payement= new Payement();
                        $payement->demande_id = $demande->id;
                        $payement->date = $date;
                        $payement->mode_payement = $mode;
                        $payement->avance = $avance;
                        $payement->reste = $reste;
                        $payement->status = $status;
                        $payement->code = $code;
                        $payement->user_id = $user_id;
                        $payement->save();
                    }
                    // ------------ End Payement -------- //
                }
                else{
                    return ['status'=>"error",'message'=>"Problème d'enregistrement de l'achat !"];
                }
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'ajouter des lignes des achats"];
        }
    
        return ['status'=>"success",'message'=>"L'achat a été bien enregistré !!"];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $demande = Demande::with(['fournisseur','payements'])->where('user_id',$user_id)->findOrFail($id);
        $date = Carbon::now();
        $fournisseurs = Fournisseur::where('user_id',$user_id)->get();
        $categories=Categorie::where('user_id',$user_id)->get();
        if($this->hasPermssion('edit4_2') == 'yes')
        return view('managements.demandes.edit', [
            'demande' =>$demande,
            'fournisseurs' =>$fournisseurs,
            'date' =>$date,
            'categories' =>$categories,
        ]);
        else
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lignes = $request->input('lignes');
        $lists = $request->input('lists');
        if(!empty($lignes)){
            $id = $request->input('id');
            $date = $request->input('date');
            $fournisseur = $request->input('fournisseur');
            $facture = $request->input('facture');

            $payements = $request->input('payements');
            $count_payements = $request->input('count_payements');
            $cmd_avance = $request->input('cmd_avance');
            $cmd_total = $request->input('cmd_total');
            $cmd_reste = $request->input('cmd_reste');

            if(!empty($date) && !empty($fournisseur)){
                // ------------ Begin demande -------- //
                $demande = Demande::find($id);
                $demande->date = $date;
                $demande->fournisseur_id = $fournisseur;

                $demande->facture = $facture;

                $demande->avance = $cmd_avance;
                $demande->reste = $cmd_reste;
                $demande->total = $cmd_total;
                $demande->user_id = $user_id;
                $demande->save();
                // ------------ End demande -------- //
                if($demande->id){
                    // ------------ Begin Lignedemande -------- //
                    $lignedemandes = Lignedemande::where('demande_id',$id)->get();
                    foreach ($lignedemandes as $ligne) {
                        $ligne->delete();
                    }
                    foreach ($lignes as $ligne) {
                        $lignedemande = new Lignedemande();
                        $lignedemande->demande_id = $id;
                        $lignedemande->produit_id = $ligne['prod_id'];
                        // $lignedemande->nom_produit = $ligne['libelle'];
                        $lignedemande->prix = $ligne['prix'];
                        $lignedemande->quantite = $ligne['qte'];
                        $lignedemande->total_produit = $ligne['total'];
                        $lignedemande->user_id = $user_id;
                        $lignedemande->save();
                        ##################################################
                        $produit = Produit::where('user_id',$user_id)->findOrFail($ligne['prod_id']);
                        $qte = $this->updateQuantite($ligne['prod_id']);
                        $produit->quantite = $qte;
                        $produit->save();
                        ##################################################
                    }
                    foreach ($lists as $ligne) {
                        ##################################################
                        $produit = Produit::where('user_id',$user_id)->findOrFail($ligne['prod_id']);
                        $qte = $this->updateQuantite($ligne['prod_id']);
                        $produit->quantite = $qte;
                        $produit->save();
                        ##################################################
                    }
                    // ------------ End Lignedemande -------- //
                    // ------------ Begin payement -------- //
                    if($count_payements>0){
                        foreach ($payements as $reg) {
                            $payement = Payement::find($reg['reg_id']);
                            $payement->reste = $reg['reste'];
                            $payement->status = $reg['status'];
                            $payement->user_id = $user_id;
                            $payement->save();
                        }
                    }
                    // ------------ End payement -------- //
                }
                else{
                    return ['status'=>"error",'message'=>"Problème d'enregistrement de l'achat !"];
                }
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'ajouter des lignes des achats"];
        }
        return ['status'=>"success",'message'=>"L'achat a été bien modifié !!"];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $demande = Demande::find($id);
        $payements = Payement::where('demande_id','=',$demande->id)->get();
        $lignedemandes = Lignedemande::with('produit')->where('demande_id','=',$demande->id)->get();
        $message = 'La demande et paiements ont été supprimés avec succès !';
        $validation = "valide";
        if($lignedemandes->count() != 0){
            foreach ($lignedemandes as $lignedemande) {
                $p = $lignedemande->quantite;
                $nqte = 0;
                $r = $p - $nqte;
                $stock = $lignedemande->produit->quantite;
                $stockFinal = $stock - $r;
                if($stockFinal<0){
                    $validation = "non_valide";
                    break;
                }
            }
        }
        if($validation == "valide"){
            if($lignedemandes->count() != 0){
                foreach ($lignedemandes as $lignedemande) {
                    $prod_id = $lignedemande->produit_id;
                    $lignedemande->delete();
                    ##################################################
                    $produit = Produit::where('user_id',$user_id)->findOrFail($prod_id);
                    $qte = $this->updateQuantite($prod_id);
                    $produit->quantite = $qte;
                    $produit->save();
                    ##################################################
                }
            }
            if($payements->count() != 0){
                foreach ($payements as $payement) {
                    $payement->delete();
                }
            }
            $demande->delete();
        }
        else{
            $message = 'Erreur...\n\nMerci de vérifier la quantité en stock !';
        }
        return redirect()->route('demande.index')->with([
            "status" => $message
        ]);
    }

    /** 
    *--------------------------------------------------------------------------
    * getDemandes5
    *--------------------------------------------------------------------------
    **/
    //Get demandes v2 pour la page demande
    public function getDemandes5(Request $request){
        // ------------------------------------
        $facture = $request->facture;//f - nf - all - null
        $status = $request->status;//r - nr - all - null
        $fournisseur = $request->fournisseur;
        $search = $request->search;//f - nf - all - null
        // ------------------------------------
        // ------------------------------------
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $r = Demande::with(['fournisseur','payements'])->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nr = Demande::with(['fournisseur','payements'])->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        // $f = Demande::with(['fournisseur','payements'])->where('facture','f')->orderBy('id','desc')->where('user_id',$user_id);
        $f = Demande::with(['fournisseur','payements'])->where('facture','!=','')->orderBy('id','desc')->where('user_id',$user_id);
        // $nf = Demande::with(['fournisseur','payements'])->where('facture','nf')->orderBy('id','desc')->where('user_id',$user_id);
        $nf = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->orderBy('id','desc')->where('user_id',$user_id);
        // $fr = Demande::with(['fournisseur','payements'])->where('facture','f')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        $fr = Demande::with(['fournisseur','payements'])->where('facture','!=','')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        // $fnr = Demande::with(['fournisseur','payements'])->where('facture','f')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        $fnr = Demande::with(['fournisseur','payements'])->where('facture','!=','')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        // $fnr = Demande::with(['fournisseur','payements'])->where('facture','f')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nfr = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        // $nfnr = Demande::with(['fournisseur','payements'])->where('facture','nf')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nfnr = Demande::with(['fournisseur','payements'])->where(function ($query) {$query->where('facture',null)->orWhere('facture','');})->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        // ------------------------------------
        if($search){
            $demandes = Demande::with(['fournisseur','payements'])
                ->where([
                    [function ($query) use ($search) {
                        $query->where('code','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%')
                        ->orWhere('facture','like','%'.$search.'%')
                        ->orWhere('total','like','%'.$search.'%')
                        ->orWhere('avance','like','%'.$search.'%')
                        ->orWhere('reste','like','%'.$search.'%');
                    }],
                    ['user_id',$user_id]
                ])
            ->orWhereHas('fournisseur', function($query) use ($search,$user_id)  {
                $query->where([['nom_fournisseur','like','%'.$search.'%'],['user_id',$user_id]]);
            })
            ->orderBy('id','desc')
            ->get();
        }
        else{
            if($fournisseur){
                if(!$facture && !$status)  //echo '[]';
                    $demandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $demandes = $r->where('fournisseur_id',$fournisseur)->get();
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $demandes = $nr->where('fournisseur_id',$fournisseur)->get();
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $demandes = Demande::with(['fournisseur','payements'])->where('fournisseur_id',$fournisseur)->orderBy('id','desc')->where('user_id',$user_id)->get();
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $demandes = $f->where('fournisseur_id',$fournisseur)->get();
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $demandes = $fr->where('fournisseur_id',$fournisseur)->get();
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $demandes = $fnr->where('fournisseur_id',$fournisseur)->get();
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $demandes = $nf->where('fournisseur_id',$fournisseur)->get();
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $demandes = $nfr->where('fournisseur_id',$fournisseur)->get();
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $demandes = $nfnr->where('fournisseur_id',$fournisseur)->get();
                else //echo '[]';
                    $demandes = [];
            }
            else{
                if(!$facture && !$status)  //echo '[]';
                    $demandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $demandes = $r->get();
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $demandes = $nr->get();
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $demandes = Demande::with(['fournisseur','payements'])->orderBy('id','desc')->where('user_id',$user_id)->get();
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $demandes = $f->get();
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $demandes = $fr->get();
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $demandes = $fnr->get();
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $demandes = $nf->get();
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $demandes = $nfr->get();
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $demandes = $nfnr->get();
                else //echo '[]';
                    $demandes = [];
            }
        } 
        // ------------------------------------
        return response()->json($demandes);
    }
    /** 
    *--------------------------------------------------------------------------
    * productsCategoryDemande
    *--------------------------------------------------------------------------
    **/
    public function productsCategoryDemande(Request $request){
        // $data=Produit::select('id','code_produit','nom_produit','prix_TTC')->where('categorie_id',$request->id)->get();
        $data=Produit::where('categorie_id',$request->id)->get();
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * infosProductsDemande
    *--------------------------------------------------------------------------
    **/
    public function infosProductsDemande(Request $request){
        $data=Produit::find($request->id);
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * infosLigneDemande
    *--------------------------------------------------------------------------
    **/
    public function infosLigneDemande(Request $request){
        $data=Lignedemande::with('produit')->find($request->id);
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * editCommand
    *--------------------------------------------------------------------------
    **/
    public function editDemande(Request $request){
        $lignedemandes = Lignedemande::with('produit')->where('demande_id',$request->id)->get();
        $payement = Payement::where('demande_id',$request->id)->first();

        return [
            'lignedemandes'=>$lignedemandes,
            'payement'=>$payement,
        ];
    }
}
