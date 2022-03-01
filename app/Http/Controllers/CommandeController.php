<?php

namespace App\Http\Controllers;

use App\Client;
use App\Facture;
use App\Produit;
use App\Commande;
use App\Categorie;
use App\Company;
use App\Demande;
use App\Reglement;
use App\Lignecommande;
use App\Lignedemande;
use App\Payement;
use App\Stock;
use App\User;
use Faker\Core\Number;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;



class CommandeController extends Controller
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

    public function get_siege_tel($company){
        // ############################################################### //
        $siege = '';
        // ############################################################### //
        ($company && ($company->nom || $company->nom != null)) ? $siege .= 'Siège social : '.$company->nom.' - ' : $siege .= 'Siège social : nom_societé';
        // -------------------------------------//
        ($company && ($company->adresse || $company->adresse != null)) ? $siege .= $company->adresse.' , ' : $siege .= '';
        // -------------------------------------//
        ($company && ($company->code_postal || $company->code_postal != null)) ? $siege .= $company->code_postal.' , ' : $siege .= '';
        // -------------------------------------//
        ($company && ($company->ville || $company->ville != null)) ?  $siege .= $company->ville.' , ' : $siege .= '';
        // -------------------------------------//
        ($company && ($company->pays || $company->pays != null)) ? $siege .= $company->pays : $siege .= '';
        // ############################################################### //
        $tel = '';
        ($company && ($company->tel || $company->tel != null)) ? $tel .= 'Tél : '.$company->tel : $tel .= '';
        // -------------------------------------//
        // ############################################################### //
        $adresse = '';
        if($siege != '')
            $adresse .= $siege.'<br>';
        if($tel != '')
            $adresse .= $tel.'<br>';
        return $adresse;
    }
    
    /** 
    *--------------------------------------------------------------------------
    * update
    *--------------------------------------------------------------------------
    **/
    // ------------ BEGIN UPDATE COMMANDE ---------------------------
    public function update(Request $request){ 
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lignes = $request->input('lignes');
        $lists = $request->input('lists');
        if(!empty($lignes)){
            $id = $request->input('id');
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

            $reglements = $request->input('reglements');
            $count_reglements = $request->input('count_reglements');
            $cmd_avance = $request->input('cmd_avance');
            $cmd_total = $request->input('cmd_total');
            $cmd_reste = $request->input('cmd_reste');

            if(!empty($date) && !empty($client)){
                // ------------ Begin Commande -------- //
                $commande = Commande::find($id);
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
                
                $commande->facture = "nf"; 

                $commande->avance = $cmd_avance;
                $commande->reste = $cmd_reste;
                $commande->total = $cmd_total;
                $commande->user_id = $user_id;
                $commande->save();
                // ------------ End Commande -------- //
                if($commande->id){
                    // ------------ Begin LigneCommande -------- //
                    $lignecommandes = Lignecommande::where('commande_id',$id)->get();
                    foreach ($lignecommandes as $ligne) {
                        $ligne->delete();
                    }
                    foreach ($lignes as $ligne) {
                        $lignecommande = new Lignecommande();
                        $lignecommande->commande_id = $id;
                        $lignecommande->produit_id = $ligne['prod_id'];
                        // $lignecommande->nom_produit = $ligne['libelle'];
                        $lignecommande->prix = $ligne['prix'];
                        $lignecommande->quantite = $ligne['qte'];
                        $lignecommande->total_produit = $ligne['total'];
                        $lignecommande->user_id = $user_id;
                        $lignecommande->save();
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
                    // ------------ End LigneCommande -------- //
                    // ------------ Begin Reglement -------- //
                    if($count_reglements>0){
                        foreach ($reglements as $reg) {
                            $reglement = reglement::find($reg['reg_id']);
                            $reglement->reste = $reg['reste'];
                            $reglement->status = $reg['status'];
                            $reglement->user_id = $user_id;
                            $reglement->save();
                        }
                    }
                    // ------------ End Reglement -------- //
                }
                else{
                    return ['status'=>"error",'message'=>"Problème d'enregistrement de la commande !"];
                }
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'ajouter des lignes des commandes"];
        }
        return ['status'=>"success",'message'=>"La commande a été bien modifiée !!"];
    }
    // ------------ END UPDATE COMMANDE ------------------------------
    /** 
    *--------------------------------------------------------------------------
    * codeFacture
    *--------------------------------------------------------------------------
    **/
    public function codeFacture(Request $request){
        // $datetime = Carbon::now();
        // $date = $datetime->isoFormat('YYYY-MM-DD');
        // $year = $datetime->isoFormat('YY');
        // $month = $datetime->isoFormat('MM');
        // -------- test la date -------- //
        // $time = strtotime('02/16/2023');
        $time = strtotime($request->date);
        $date = date('Y-m-d',$time);
        $year = date('y',$time);
        $month = date('m',$time);
        // ---------------------  
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

        return $code;
    }
    /** 
    *--------------------------------------------------------------------------
    * getCommandes5
    *--------------------------------------------------------------------------
    **/
    //Get commandes v2 pour la page commande
    public function getCommandes5(Request $request){
        // ------------------------------------
        $facture = $request->facture;//f - nf - all - null
        $status = $request->status;//r - nr - all - null
        $client = $request->client;
        $search = $request->search;//f - nf - all - null
        // ------------------------------------
        // ------------------------------------
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $r = Commande::with(['client','reglements'])->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nr = Commande::with(['client','reglements'])->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        $f = Commande::with(['client','reglements'])->where('facture','f')->orderBy('id','desc')->where('user_id',$user_id);
        $nf = Commande::with(['client','reglements'])->where('facture','nf')->orderBy('id','desc')->where('user_id',$user_id);
        $fr = Commande::with(['client','reglements'])->where('facture','f')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        $fnr = Commande::with(['client','reglements'])->where('facture','f')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nfr = Commande::with(['client','reglements'])->where('facture','nf')->where('reste','<=',0)->orderBy('id','desc')->where('user_id',$user_id);
        $nfnr = Commande::with(['client','reglements'])->where('facture','nf')->where('reste','>',0)->orderBy('id','desc')->where('user_id',$user_id);
        if($search){
            $commandes = Commande::with(['client','reglements'])
                ->where([
                    [function ($query) use ($search) {
                        $query->where('code','like','%'.$search.'%')
                        ->orWhere('date','like','%'.$search.'%')
                        ->orWhere('total','like','%'.$search.'%')
                        ->orWhere('avance','like','%'.$search.'%')
                        ->orWhere('reste','like','%'.$search.'%');
                    }],
                    ['user_id',$user_id]
                ])
            ->orWhereHas('client', function($query) use ($search,$user_id)  {
                $query->where([['nom_client','like','%'.$search.'%'],['user_id',$user_id]]);
            })
            ->orderBy('id','desc')
            ->get();
        }
        else{
            if($client){
                if(!$facture && !$status)  //echo '[]';
                    $commandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $commandes = $r->where('client_id',$client)->get();
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $commandes = $nr->where('client_id',$client)->get();
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $commandes = Commande::with(['client','reglements'])->where('client_id',$client)->orderBy('id','desc')->where('user_id',$user_id)->get();
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $commandes = $f->where('client_id',$client)->get();
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $commandes = $fr->where('client_id',$client)->get();
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $commandes = $fnr->where('client_id',$client)->get();
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $commandes = $nf->where('client_id',$client)->get();
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $commandes = $nfr->where('client_id',$client)->get();
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $commandes = $nfnr->where('client_id',$client)->get();
                else //echo '[]';
                    $commandes = [];
            }
            else{
                if(!$facture && !$status)  //echo '[]';
                    $commandes = [];
                else if((!$facture && $status=='r') || ($facture=='all' && $status=='r'))  //echo 'r';
                    $commandes = $r->get();
                else if((!$facture && $status=='nr') || ($facture=='all' && $status=='nr'))  //echo 'nr';
                    $commandes = $nr->get();
                else if((!$facture && $status=='all') || ($facture=='all' && !$status) || ($facture=='all' && $status=='all')) //echo 'all';
                    $commandes = Commande::with(['client','reglements'])->orderBy('id','desc')->where('user_id',$user_id)->get();
                else if(($facture=='f' && !$status) || ($facture=='f' && $status=='all')) //echo 'f';
                    $commandes = $f->get();
                else if($facture=='f' && $status=='r') //echo 'fr';
                    $commandes = $fr->get();
                else if($facture=='f' && $status=='nr') //echo 'fnr';
                    $commandes = $fnr->get();
                else if(($facture=='nf' && !$status) || ($facture=='nf' && $status=='all')) //echo 'nf';
                    $commandes = $nf->get();
                else if($facture=='nf' && $status=='r') //echo 'nfr';
                    $commandes = $nfr->get();
                else if($facture=='nf' && $status=='nr') //echo 'nfnr';
                    $commandes = $nfnr->get();
                else //echo '[]';
                    $commandes = [];
            }
        }
        
        // ------------------------------------
        return response()->json($commandes);
    }
    /** 
    *--------------------------------------------------------------------------
    * productsCategory
    *--------------------------------------------------------------------------
    **/
    public function productsCategory(Request $request){
        // $data=Produit::select('id','code_produit','nom_produit','prix_produit_TTC')->where('categorie_id',$request->id)->get();
        $data=Produit::where('categorie_id',$request->id)->get();
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * infosProducts
    *--------------------------------------------------------------------------
    **/
    public function infosProducts(Request $request){
        $data=Produit::find($request->id);
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * infosLigne
    *--------------------------------------------------------------------------
    **/
    public function infosLigne(Request $request){
        $data=Lignecommande::with('produit')->find($request->id);
        return response()->json($data);
	}
    /** 
    *--------------------------------------------------------------------------
    * editCommand
    *--------------------------------------------------------------------------
    **/
    public function editCommande(Request $request){
        $lignecommandes = Lignecommande::with('produit')->where('commande_id',$request->id)->get();
        $reglement = Reglement::where('commande_id',$request->id)->first();

        return [
            'lignecommandes'=>$lignecommandes,
            'reglement'=>$reglement,
        ];
    }
    /** 
    *--------------------------------------------------------------------------
    * storeFacture2
    *--------------------------------------------------------------------------
    **/
    public function storefacture2( Request $request)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $cmd_id = $request->input('commande_id');
        $factures = Facture::where('commande_id','=',$cmd_id)->get();
        if($factures->count()>0){
            $msg= "La commande a été déja facturée! ";
        }
        else{
            $code = $request->input('code');

            $getFactures = Facture::where('user_id',$user_id)->get();
            // --------------------------------------
            $list = explode("-",$code);
            if($code && count($list) == 3 && $list[0] == 'FA' && is_numeric($list[1]) && is_numeric($list[2])){
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
                // --------------------------------------
                if($existe){
                    $msg= "Le code de la facture existe déja ! ";
                }
                else{
                    $facture = new Facture();
                    $facture->total_HT = $request->input('total_HT');
                    $facture->total_TVA = $request->input('total_TVA');
                    $facture->total_TTC = $request->input('total_TTC');
                    $facture->commande_id = $request->input('commande_id');
                    $facture->reglement = $request->input('reglement');
                    $facture->date = $request->input('date');
                    $facture->code = $code;
                    $facture->user_id = $user_id;
                    $facture->save();
                    $msg= "La facture a été bien enregistrée";
                    if($facture->id){
                        $commande = Commande::find($facture->commande_id);
                        $commande->facture = "f";
                        $commande->user_id = $user_id;
                        $commande->save();
                    }
                }
            }
            else{
                $msg= "Le code de la facture est invalide ! ";
            }
        }
        return redirect()->route('commande.index')->with([
            "status" => $msg
        ]); 
    }
    /** 
    *--------------------------------------------------------------------------
    * facture
    *--------------------------------------------------------------------------
    **/
    public function facture(Request $request){
        $datetime = Carbon::now();
        $date = $datetime->isoFormat('YYYY-MM-DD');
        $year = $datetime->isoFormat('YY');
        $month = $datetime->isoFormat('MM');
        // -------- test la date -------- //
        // $time = strtotime('02/16/2023');
        // $date = date('Y-m-d',$time);
        // $year = date('y',$time);
        // $month = date('m',$time);
        // ---------------------        
        // $factures = Facture::where('code', 'like', "FA-$year%")->get();
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

        $cmd_id = $request->commande;
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $commande = Commande::with('client')->where('user_id',$user_id)->findOrFail($cmd_id);
        $lignecommandes = Lignecommande::with('produit')->where('commande_id', '=', $cmd_id)->get();
        $HT = 0;
        $TTC = 0;
        
        foreach($lignecommandes as $ligne){
            $HT += $ligne->total_produit / (1 + $ligne->produit->TVA/100);
            $TTC += $ligne->total_produit;
        }

        $TVA = $TTC - $HT;
        
        if($this->hasPermssion('create6') == 'yes')
        return view('managements.commandes.facture', [
            'cmd_id' =>  $cmd_id, 
            'date' =>  $date, 
            'year' =>  $year, 
            'month' =>  $month, 
            'code' =>  $code, 
            'lignecommandes' =>  $lignecommandes,
            'TTC'  => $TTC,
            'HT' => $HT,
            'TVA' => $TVA,
            'commande' => $commande,
            // 'factures' => $factures,
        ]);
        else
        return redirect()->back();
    }
    /** 
    *--------------------------------------------------------------------------
    * Resources
    *--------------------------------------------------------------------------
    **/
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
        $commandes = Commande::getCommandes();
        $clients = Client::where('user_id',$user_id )->get();
        if($this->hasPermssion('list4') == 'yes')
        return view('managements.commandes.index', [
            'commandes'=>$commandes,
            'clients' =>$clients,
            'permission' =>$permission,
            'date' =>$date,
            'dateFrom' =>$dateFrom,
            'dateTo' =>$dateTo,
        ]);
        else
        return view('application');
    }

    function fetch_commande(Request $request)
    {
        if($request->ajax()){
            // ------------------------------------
            $facture = $request->facture;//f - nf - all - null
            $status = $request->status;//r - nr - all - null
            $client = $request->client;
            $search = $request->search;//f - nf - all - null
            $from = $request->from;
            $to = $request->to;
            // ------------------------------------
            $commandes = commande::searchCommande($facture,$status,$client,$search,$from,$to);
            $permission = $this->getPermssion(Auth::user()->permission);
            return view('managements.commandes.index_data',compact(['commandes','permission']))->render();
        }
    }
    // ------------ BEGIN INDEX COMMANDE ------------------------------
    public function index_save(Request $request){
        $permission = $this->getPermssion(Auth::user()->permission);
        $date = Carbon::now();
        $year = $date->isoFormat('YYYY');
        $dateFrom = $year.'-01-01';
        $dateTo = $year.'-12-31';
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commandes = Commande::with(['client','reglements'])->where('user_id',$user_id)->get();
        $lignecommandes = Lignecommande::where('user_id',$user_id )->get();
        $reglements = reglement::where('user_id',$user_id )->get();
        $clients = Client::where('user_id',$user_id )->get();
        if($this->hasPermssion('list4') == 'yes')
        return view('managements.commandes.index', [
            'commandes'=>$commandes,
            'lignecommandes'=>$lignecommandes,
            'reglements'=>$reglements,
            'clients' =>$clients,
            'permission' =>$permission,
            'date' =>$date,
            'dateFrom' =>$dateFrom,
            'dateTo' =>$dateTo,
        ]);
        else
        return view('application');
    }
    // ------------ END INDEX COMMANDE ------------------------------
    // ------------ BEGIN CREATE COMMANDE ---------------------------
    public function create(Request $request)
    {
        $date = Carbon::now();
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories=Categorie::where('user_id',$user_id)->get();//get data from table
        $clients = Client::where('user_id',$user_id)->get();
        if($this->hasPermssion('create4') == 'yes')
        return view('managements.commandes.create', [
            'clients' =>$clients,
            'categories' => $categories,
            'date' => $date,
        ]);
        else
        return redirect()->back();
    }
    // ------------ END CREATE COMMANDE ------------------------------
    // ------------ BEGIN STORE COMMANDE ---------------------------
    public function store(Request $request)
    { 
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
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
        // -----------------------------------------------------
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
            $code = 'BON-'.$year.''.$month.'-'.$pad;
            // -----------------------------------------------------
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
                $commande->facture = "nf"; 
                $commande->avance = $avance;
                $commande->reste = $reste;
                $commande->total = $total;
                $commande->code = $code;
                $commande->user_id = $user_id;
                $commande->save();
                // ------------ End Commande -------- //
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
                    $code = 'REG-'.$year.''.$month.'-'.$pad;
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
                        $reglement->code = $code;
                        $reglement->user_id = $user_id;
                        $reglement->save();
                    }
                    // ------------ End Reglement -------- //
                }
                else{
                    return ['status'=>"error",'message'=>"Problème d'enregistrement de la commande !"];
                }
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'ajouter des lignes des commandes"];
        }
    
        return ['status'=>"success",'message'=>"La commande a été bien enregistrée !!"];
    }
    // ------------ END STORE COMMANDE ------------------------------
    // ------------ BEGIN SHOW COMMANDE ---------------------------
    public function show($cmd_id){
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $adresse = $this->getAdresse($company);

        $commande = Commande::with('client')->where('user_id',$user_id)->findOrFail($cmd_id);
        
        $lignecommandes = Lignecommande::with('produit')->where('commande_id', '=', $cmd_id)->get();

        $prix_HT = 0;
        $TVA = 0;
        $priceTotal = 0;
        foreach($lignecommandes as $ligne){
            $prix_HT = $prix_HT +  ($ligne->produit->prix_produit_HT * $ligne->quantite);
            $TVA = $TVA +  ($ligne->produit->prix_produit_HT * $ligne->quantite * $ligne->produit->TVA) ;
            $priceTotal =  floatval($priceTotal  + $ligne->total_produit) ;
        }
        if($this->hasPermssion('show4') == 'yes')
        return view('managements.commandes.show', [
            'cmd_id' =>  $cmd_id, 
            'commande' =>  $commande, 
            'lignecommandes' =>  $lignecommandes,
            'priceTotal'  => $priceTotal,
            'prix_HT' => $prix_HT,
            'TVA' => $TVA,
            'company' => $company,
            'adresse' => $adresse,
            'permission' => $permission,
        ]);
        else
        return redirect()->back();
    }
    // ------------ END SHOW COMMANDE ------------------------------
    // ------------ BEGIN EDIT COMMANDE ---------------------------
    public function edit($id){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commande = Commande::with(['client','reglements'])->where('user_id',$user_id)->findOrFail($id);
        $date = Carbon::now();
        $clients = Client::where('user_id',$user_id)->get();
        $categories=Categorie::where('user_id',$user_id)->get();
        if($this->hasPermssion('edit4') == 'yes')
        return view('managements.commandes.edit', [
            'commande' =>$commande,
            'clients' =>$clients,
            'date' =>$date,
            'categories' =>$categories,
        ]);
        else
        return redirect()->back();
    }
    // ------------ END EDIT COMMANDE ------------------------------   
    // ------------ BEGIN DESTROY COMMANDE ---------------------------
    public function destroy($id){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commande = Commande::find($id);
        $facture = Facture::where('commande_id','=',$commande->id)->first();
        $reglements = Reglement::where('commande_id','=',$commande->id)->get();
        $lignecommandes = LigneCommande::where('commande_id','=',$commande->id)->get();
        $message = 'La commande, facture et règlement ont été supprimés avec succès!';
        $validation = "valide";
        if($lignecommandes->count() != 0){
            foreach ($lignecommandes as $lignedemande) {
                $p = $lignedemande->quantite;
                $nqte = 0;
                // $r = $p - $nqte;
                $r = $nqte - $p;
                $stock = $lignedemande->produit->quantite;
                $stockFinal = $stock - $r;
                if($stockFinal<0){
                    $validation = "non_valide";
                    break;
                }
            }
        }
        if($validation == "valide"){
            if($lignecommandes->count() != 0){
                foreach ($lignecommandes as $lignecommande) {
                    $prod_id = $lignecommande->produit_id;
                    $lignecommande->delete();
                    ##################################################
                    $produit = Produit::where('user_id',$user_id)->findOrFail($prod_id);
                    $qte = $this->updateQuantite($prod_id);
                    $produit->quantite = $qte;
                    $produit->save();
                    ##################################################
                }
            }
            if($reglements->count() != 0){
                foreach ($reglements as $reglement) {
                    $reglement->delete();
                }
            }
            if($facture)
                $facture->delete();
            $commande->delete();
        }
        else{
            $message = 'Erreur...\n\nMerci de vérifier la quantité en stock !';
        }
        return redirect()->route('commande.index')->with([
            "status" => $message
        ]);
    }
    // ------------ END DESTROY COMMANDE ------------------------------
    
    /** 
    *--------------------------------------------------------------------------
    * Autres fonctions
    *--------------------------------------------------------------------------
    **/
//-------------------
}
