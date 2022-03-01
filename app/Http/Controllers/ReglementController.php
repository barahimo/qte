<?php

namespace App\Http\Controllers;

use App\Client;
use App\Commande;
use App\Reglement;
use App\Company;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReglementController extends Controller
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
    * --------------------------------------------------------------------------
    *  store2
    * --------------------------------------------------------------------------
    **/
    // Enregistrer plusieurs reglements
    public function store2(Request $request){ 
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lignes = $request->input('lignes');
        if(!empty($lignes)){
            $date = $request->input('date');
            $client = $request->input('client');
            $mode = $request->input('mode');
            // -----------------------------------------------------
            $time = strtotime($date);
            $year = date('y',$time);
            $month = date('m',$time);
            // -----------------------------------------------------
            if(!empty($date) && !empty($client) && $mode != ""){
                // ------------ Begin reglement -------- //
                foreach ($lignes as $ligne) {
                    $reglement = new Reglement();
                    $reglement->date = $date;
                    $reglement->mode_reglement = $mode;
                    $reglement->avance = $ligne['avance'];
                    $reglement->reste = $ligne['reste'];
                    $reglement->status = $ligne['status'];
                    // -----------------------------------------------------
                    $reglements = Reglement::where('user_id',$user_id)->get();
                    (count($reglements)>0) ? $lastcode = $reglements->last()->code : $lastcode = null;
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
                    $reglement->code = $code;
                    $reglement->commande_id = $ligne['cmd_id'];
                    $reglement->user_id = $user_id;
                    $reglement->save();
                    
                    $commande = Commande::find($ligne['cmd_id']);
                    $commande->avance = $commande->avance+$ligne['avance'];
                    $commande->reste = $ligne['reste'];
                    $commande->user_id = $user_id;
                    $commande->save();
                }
                // ------------ End Reglement -------- //
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'ajouter des règlements"];
        }

        return ['status'=>"success",'message'=>"Le règlement a été bien enregistré !!"];
    }
    /**
    * --------------------------------------------------------------------------
    *  store3
    * --------------------------------------------------------------------------
    **/
    // Enregistrer une seule règlement
    public function store3(Request $request){ 
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $lignes = $request->input('lignes');
        if(!empty($lignes)){
            $date = $request->input('date');
            $mode = $request->input('mode');
            // -----------------------------------------------------
            $time = strtotime($date);
            $year = date('y',$time);
            $month = date('m',$time);
            // -----------------------------------------------------
            $reglements = Reglement::where('user_id',$user_id)->get();
            (count($reglements)>0) ? $lastcode = $reglements->last()->code : $lastcode = null;
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
            if(!empty($date) && $mode != ""){
                // ------------ Begin reglement -------- //
                foreach ($lignes as $ligne) {
                    $reglement = new Reglement();
                    $reglement->date = $date;
                    $reglement->mode_reglement = $mode;
                    $reglement->avance = $ligne['avance'];
                    $reglement->reste = $ligne['reste'];
                    $reglement->status = $ligne['status'];
                    $reglement->code = $code;
                    $reglement->commande_id = $ligne['cmd_id'];
                    $reglement->user_id = $user_id;
                    $reglement->save();
                    
                    $commande = Commande::find($ligne['cmd_id']);
                    $commande->avance = $commande->avance+$ligne['avance'];
                    $commande->reste = $ligne['reste'];
                    $commande->user_id = $user_id;
                    $commande->save();
                }
                // ------------ End Reglement -------- //
            } 
            else{
                return ['status'=>"error",'message'=>"Veuillez remplir les champs vides !"];
            }
        }
        else {
            return ['status'=>"error",'message'=>"Veuillez d'effectuer le règlement"];
        }
    
        return ['status'=>"success",'message'=>"Le règlement a été bien enregistré !!"];
    }
    /**
    * --------------------------------------------------------------------------
    *  avoir
    * --------------------------------------------------------------------------
    **/
    public function avoir(Request $request){ 

        $data = $request->input('obj');

        $reg_id = $data['reg_id']; 
        $reg_avance = $data['reg_avance'];
        $reg_reste = $data['reg_reste'];
        $cmd_id = $data['cmd_id'];
        $cmd_avance = $data['cmd_avance'];
        $cmd_reste = $data['cmd_reste'];

        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;

        // $reglement = Reglement::where('user_id',$user_id)->find($reg_id);
        // $reglement->avance = $reg_avance + $reg_reste;
        // $reglement->reste = $reg_reste - $reg_reste;
        // $reglement->status = 'R';
        // $reglement->save();
        // -------------------- //
        // $reglement = Reglement::where('user_id',$user_id)->find($reg_id);
        $reglementsAvoir = Reglement::where('commande_id',$cmd_id)->where('user_id',$user_id)->where('reste','<',0)->get();
        $reglement = $reglementsAvoir->last();
        
        // -------------------- //
        $new_reglement = new Reglement();
        // -----------------------------------------------------
        $date = Carbon::now();
        // -----------------------------------------------------
        $time = strtotime($date);
        $year = date('y',$time);
        $month = date('m',$time);
        // -----------------------------------------------------
        $reglements = Reglement::where('code','like','AVC%')->where('user_id',$user_id)->get();
        (count($reglements)>0) ? $lastcode = $reglements->last()->code : $lastcode = null;
        $str = 1;
        if(isset($lastcode)){
            // ----- REG-2108-0001 ----- //
            $list = explode("-",$lastcode);
            $y = substr($list[1],0,2);
            $n = $list[2];
            ($y == $year) ? $str = $n+1 : $str = 1;
        } 
        $pad = str_pad($str,4,"0",STR_PAD_LEFT);
        $code = 'AVC-'.$year.''.$month.'-'.$pad;
        // -----------------------------------------------------
        $new_reglement->code= $code;
        $new_reglement->date= $date;
        $new_reglement->mode_reglement= $reglement->code;
        $new_reglement->avance= $reglement->reste;
        // $new_reglement->reste= $reg_reste - $reg_reste;
        $new_reglement->reste= 0;
        $new_reglement->status= "R";
        $new_reglement->commande_id= $reglement->commande_id;
        $new_reglement->user_id= $reglement->user_id;
        // -------------------- //
        $new_reglement->save();
        // -------------------- //
        // $reglement->status = 'R';
        // $reglement->save();
        foreach ($reglementsAvoir as $reglement) {
            $reglement->status = 'R';
            $reglement->save();
        }
        // -------------------- //
        
        $commandeReglements = Reglement::where('commande_id',$cmd_id)->where('user_id',$user_id)->get();
        $compAvance = 0;
        foreach ($commandeReglements as $reglement) {
            $compAvance = $compAvance + $reglement->avance;
        }
        $commande = Commande::where('user_id',$user_id)->find($cmd_id);
        // $commande->avance = $cmd_avance + $reg_reste;
        // $commande->reste = $cmd_reste - $reg_reste;
        $commande->avance = $compAvance;
        $commande->reste = $commande->total - $compAvance;
        $commande->save();

        return ['status'=>"success",'message'=>"Opération effectuée avec succés !!!"];
    }
    /**
    * --------------------------------------------------------------------------
    *  getReglements3
    * --------------------------------------------------------------------------
    **/
    public function getReglements3(Request $request){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $client = $request->client;
        if($client){
            $nom_client = Client::find($client)->nom_client;
            $commandes = Commande::with(['client','reglements'])
                        ->whereHas('client',function($query) use ($nom_client){
                            $query->where('nom_client',$nom_client);
                        })
                        ->where('reste', '>', 0)
                        ->where('user_id',$user_id)
                        ->orderBy('id','desc')
                        ->get();
        }
        else{
            $commandes = Commande::with(['client','reglements'])
                        ->where('reste', '>', 0)
                        ->where('user_id',$user_id)
                        ->orderBy('id','desc')
                        ->get();
        }
        return response()->json($commandes);
    }
    /**
    * --------------------------------------------------------------------------
    *  create2
    * --------------------------------------------------------------------------
    **/
    //regler plusieurs commandes 
    public function create2(Request $request){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $clients = Client::where('user_id',$user_id)->get();
        $client = $request->client;
        $date = Carbon::now();
        if($client){
            $nom_client = Client::find($client)->nom_client;
            
            $commandes = Commande::with('client')->whereHas('client',function($query) use ($nom_client){
                        $query->where('nom_client',$nom_client);
                    })->where('reste', '>', 0)
                    ->where('user_id',$user_id)
                    ->orderBy('id','desc')
                    ->get();
        }
        else{
            $commandes = Commande::with('client')
            ->where('reste', '>', 0)
            ->where('user_id',$user_id)
            ->orderBy('id','desc')
            ->get();
        }
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('create5') == 'yes')
        return view('managements.reglements.create2',compact('clients','client','commandes','date'));
        else
        return redirect()->back();
    }
    /**
    * --------------------------------------------------------------------------
    *  create3
    * --------------------------------------------------------------------------
    **/
    //Regler une seule commande
    public function create3(Request $request){
        $commande_id = $request->commande;
        $date = Carbon::now();
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $commande = Commande::with('client')->where('user_id',$user_id)->findOrFail($commande_id);
        $permission = $this->getPermssion(Auth::user()->permission);
        if($this->hasPermssion('create5') == 'yes')
        return view('managements.reglements.create3',compact('commande','date'));
        else
        return redirect()->back();
    }
    /**
    * --------------------------------------------------------------------------
    *  delete
    * --------------------------------------------------------------------------
    **/
    public function delete($id){ 
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $reglement = Reglement::find($id);
        $avance = $reglement->avance;
        $cmd_id = $reglement->commande_id;
        $commande = Commande::find($cmd_id);
        $count = $commande->total;

        $list = explode("-",$reglement->code);
        $avc = $list[0];
        if($avc == 'AVC'){
            $reglementAvoir = Reglement::where('code',$reglement->mode_reglement)->first();
            $reglementAvoir->status = "AV";
            $reglementAvoir->save();
            $reglement->delete();
        }
        else{
            $avoir = Reglement::where('mode_reglement',$reglement->code)->where('user_id',$user_id)->get();
            if(count($avoir)>0){
                return redirect()->route('commande.index')->with([
                    "status" => "La commande contient une AVOIR !"
                ]);
            }
            else{
                // $commande = Commande::find($reglement->commande_id);
                // $commande->avance = $commande->avance - $reglement->avance;
                // $commande->reste = $commande->total - $commande->avance;
                // $commande->save();
                $reglement->delete(); 
                $reglements = Reglement::where('commande_id',$cmd_id)->get();
                foreach ($reglements as $reglement) {
                    $reglement->reste = $count - $reglement->avance;
                    // ($reglement->reste > 0) ? $reglement->status = 'NR' : $reglement->status = ' R' ;
                    if($reglement->reste > 0) 
                        $reglement->status = 'NR' ;
                    elseif($reglement->reste == 0) 
                        $reglement->status = 'R' ;
                    else
                    $reglement->status = 'AV';
                    
                    $count = $reglement->reste;
                    $reglement->save();
                }
            }
        }
        // $commandeReglements = Reglement::where('commande_id',$cmd_id)->where('user_id',$user_id)->get();
        // $compAvance = 0;
        // foreach ($commandeReglements as $reglement) {
        //     $compAvance = $compAvance + $reglement->avance;
        // }
        // $commande = Commande::where('user_id',$user_id)->find($cmd_id);
        // $commande->avance = $compAvance;
        // $commande->reste = $commande->total - $compAvance;
        // $commande->save();
        
        $commande->avance = $commande->avance - $avance;
        $commande->reste = $commande->total - $commande->avance;
        $commande->save();

        return redirect()->route('commande.index')->with([
            "status" => "Le règlement a été supprimé avec succès !"
        ]); 
    }
    /**
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $reglements = Reglement::orderBy('id','desc')->paginate(3);
        
        
        if($this->hasPermssion('list5') == 'yes')
        return view('managements.reglements.index', compact(['reglements','permission']));
        else
        return view('application');
    }

    public function create(){
        //
    }

    public function store(Request $request){
        //
    }

    public function show(Reglement $reglement){
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $companies = Company::where('user_id',$user_id)->get();
        $count = count($companies);
        ($count>0)  ? $company = Company::where('user_id',$user_id)->first(): $company = null;
        $adresse = $this->getAdresse($company);

        $reglement = Reglement::with(['commande' => function($query){$query->with('client');}])
        ->where('user_id',$user_id)->findOrFail($reglement->id);
        
        if($this->hasPermssion('show5') == 'yes')
        return view('managements.reglements.show', [
            'reglement' => $reglement,
            'company' => $company,
            'adresse' => $adresse,
            'permission' => $permission,
        ]);
        else
        return redirect()->back();
    }
    
    public function edit(Reglement $reglement){
        return view('managements.reglements.edit')->with([
            "reglement" => $reglement,
            'clients' => Client::all()
        ]);
    }

    public function update(Request $request, Reglement $reglement){
        $reglement->mode_reglement = $request->input('mode_reglement');
        $reglement->avance = $request->input('avance');
        $reglement->reste = $request->input('reste');
        $reglement->date = $request->input('date');
        $reglement->status = $request->input('reglement');
        $reglement->commande_id = $request->input('commande_id');
        

        $reglement->save();
        $request->session()->flash('status','le règlement a été bien modifié !');

        return redirect()->route('reglement.index');
    }

    public function destroy(Reglement $reglement){
        
        $reglement->delete();

        return redirect()->route('reglement.index')->with([
            "success" => "le réglement a été supprimer avec succès!"
        ]); 
    }
    /** 
    *--------------------------------------------------------------------------
    * Autres fonctions
    *--------------------------------------------------------------------------
    **/
    // ----------------------------
    
    // ----------------------------
}


