<?php

namespace App\Http\Controllers;

use App\Demande;
use App\Fournisseur;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
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
    public function form(Request $request,$fournisseur,$user_id){
        // $fournisseur->code = $code;
        $fournisseur->nom_fournisseur = $request->nom_fournisseur;
        $fournisseur->adresse = $request->adresse;
        $fournisseur->code_postal = $request->code_postal;
        $fournisseur->ville = $request->ville;
        $fournisseur->pays = $request->pays;
        $fournisseur->tel = $request->tel;
        $fournisseur->site = $request->site;
        $fournisseur->email = $request->email;
        $fournisseur->note = $request->note;
        $fournisseur->iff = $request->iff;
        $fournisseur->ice = $request->ice;
        $fournisseur->capital = $request->capital;
        $fournisseur->rc = $request->rc;
        $fournisseur->patente = $request->patente;
        $fournisseur->cnss = $request->cnss;
        $fournisseur->banque = $request->banque;
        $fournisseur->rib = $request->rib;
        // $user_id = Auth::user()->id;
        // if(Auth::user()->is_admin == 0)
        //     $user_id = Auth::user()->user_id;
        $fournisseur->user_id = $user_id;
    }
    /** 
    *--------------------------------------------------------------------------
    * searchFournisseur
    *--------------------------------------------------------------------------
    **/
    public function searchFournisseur(Request $request){
        $search = $request->search;
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $fournisseurs = Fournisseur::where([
            [function ($query) use ($search) {
                    $query->where('nom_fournisseur','like',"%$search%")
                    ->orWhere('code','like',"%$search%")
                    ->orWhere('adresse','like',"%$search%")
                    ->orWhere('tel','like',"%$search%");
            }],
            ['user_id',$user_id]
        ])
        ->orderBy('id','desc')
        ->get();
        return $fournisseurs;
    }
    /** 
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        #################################
        $fournisseurs = Fournisseur::getFournisseurs();
        #################################
        if($this->hasPermssion('list1_2') == 'yes')
            return view('managements.fournisseurs.index', compact(['fournisseurs','permission']));
        else
        return view('application');
    }

    function fetch_fournisseur(Request $request){
        if($request->ajax()){
            $search = $request->search;
            $fournisseurs = Fournisseur::searchFournisseur($search);
            return view('managements.fournisseurs.index_data',compact('fournisseurs'))->render();
        }
    }
    public function index_save()
    {
        $permission = $this->getPermssion(Auth::user()->permission);
        #################################
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $fournisseurs = Fournisseur::orderBy('id','desc')->where('user_id',$user_id)->get();
        #################################
        if($this->hasPermssion('list1_2') == 'yes')
            return view('managements.fournisseurs.index', compact(['fournisseurs','permission']));
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
        if($this->hasPermssion('create1_2') == 'yes')
        return view('managements.fournisseurs.create');
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
        // --------------------------------------------------
        // $fournisseurs = Fournisseur::withTrashed()->where('user_id',$user_id)->get();
        $fournisseurs = Fournisseur::where('user_id',$user_id)->get();
        (count($fournisseurs)>0) ? $lastcode = $fournisseurs->last()->code : $lastcode = null;
        $str = 1;
        // if(isset($lastcode))
        //     $str = $lastcode+1 ;
        // $code = 'F-'.str_pad($str,4,"0",STR_PAD_LEFT);
        if(isset($lastcode)){
            // ----- F-0001 ----- //
            $list = explode("-",$lastcode);
            $n = $list[1];
            $str = $n+1;
        } 
        $pad = str_pad($str,4,"0",STR_PAD_LEFT);
        $code = 'F-'.$pad;
        // --------------------------------------------------
        $fournisseur = new Fournisseur();
        $this->form($request,$fournisseur,$user_id);
        $fournisseur->code = $code;
        // $fournisseur->nom_fournisseur = $request->input('nom_fournisseur');
        // $fournisseur->adresse = $request->input('adresse');
        // $fournisseur->telephone = $request->input('telephone');
        // $fournisseur->solde = $request->input('solde');
        // $fournisseur->code = $code;
        // $fournisseur->ICE = Str::slug($fournisseur->nom_fournisseur, '-');
        // $fournisseur->user_id = $user_id;
        $fournisseur->save();
        $request->session()->flash('status','Le fournisseur a été bien enregistré !');
        return redirect()->route('fournisseur.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $fournisseur = Fournisseur::where('user_id',$user_id)->findOrFail($id);
        $demandes = Demande::where([['fournisseur_id', '=', $fournisseur->id],['user_id',$user_id]])->get();
        $cmd = Demande::where('fournisseur_id', '=', $fournisseur->id)->get();
        $count = $cmd->count();
        $reste = 0;
        if($count>0){
            foreach ($cmd as $key => $demande) {
                $reste += $demande->reste;
            }
        }
        if($this->hasPermssion('show1_2') == 'yes')
        return view('managements.fournisseurs.show')->with([
            'demandes' => $demandes,
            'fournisseur' => $fournisseur,
            'count' => $count,
            'reste' => $reste
        ]);
        else
        return redirect()->back();
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
        $fournisseur = Fournisseur::where('user_id',$user_id)->findOrFail($id);
        if($this->hasPermssion('edit1_2') == 'yes')
        return view('managements.fournisseurs.edit')->with([
            "fournisseur" => $fournisseur
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
        $fournisseur = Fournisseur::where('user_id',$user_id)->findOrFail($id);
        $this->form($request,$fournisseur,$user_id);
        // $fournisseur->nom_fournisseur = $request->input('nom_fournisseur');
        // $fournisseur->adresse = $request->input('adresse');
        // $fournisseur->telephone = $request->input('telephone');
        // $fournisseur->solde = $request->input('solde');
        // $fournisseur->ICE = Str::slug($fournisseur->nom_fournisseur, '-');
        // $fournisseur->user_id = $user_id;
        $fournisseur->save();

        $request->session()->flash('status','Le fournisseur a été bien modifié !');
        return redirect()->route('fournisseur.index');
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
        $fournisseur = Fournisseur::where('user_id',$user_id)->findOrFail($id);
        $demandes = Fournisseur::where('fournisseur_id','=',$fournisseur->id)->get();
        if($demandes->count() != 0){
            $msg = "Erreur,  déja passer une demande chez le fournisseur !";
        }
        elseif($demandes->count() == 0){
            $fournisseur->delete();
            $msg = "Le fournisseur a été supprimé avec succès";
        }
        return redirect()->route('fournisseur.index')->with(["status" => $msg]); 
    }
}
