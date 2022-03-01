<?php

namespace App\Http\Controllers;

use Throwable;
use App\Client;
use App\Commande;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
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
    /** 
    *--------------------------------------------------------------------------
    * searchClient
    *--------------------------------------------------------------------------
    **/
    public function searchClient(Request $request){
        $search = $request->search;
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
        ->get();
        return $clients;
    }
    /** 
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $clients = Client::getClients();
        if($this->hasPermssion('list1') == 'yes')
        return view('managements.clients.index', compact(['clients','permission']));
        else
        return view('application');
    }

    function fetch_client(Request $request){
        if($request->ajax()){
            $search = $request->search;
            $clients = Client::searchClient($search);
            return view('managements.clients.index_data',compact('clients'))->render();
        }
    }

    public function index_save(Request $request){
        $permission = $this->getPermssion(Auth::user()->permission);
        #################################
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $clients = Client::orderBy('id','desc')->where('user_id',$user_id)->get();
        #################################
        if($this->hasPermssion('list1') == 'yes')
        return view('managements.clients.index', compact(['clients','permission']));
        else
        return view('application');
    }

    public function create(){
        if($this->hasPermssion('create1') == 'yes')
        return view('managements.clients.create');
        else
        return redirect()->back();
    }
    
    public function store(Request $request){  
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        // --------------------------------------------------
        // $clients = Client::withTrashed()->where('user_id',$user_id)->get();
        $clients = Client::where('user_id',$user_id)->get();
        (count($clients)>0) ? $lastcode = $clients->last()->code : $lastcode = null;
        $str = 1;
        // if(isset($lastcode))
        //     $str = $lastcode+1 ;
        // $code = 'C-'.str_pad($str,4,"0",STR_PAD_LEFT);
        if(isset($lastcode)){
            // ----- C-0001 ----- //
            $list = explode("-",$lastcode);
            $n = $list[1];
            $str = $n+1;
        } 
        $pad = str_pad($str,4,"0",STR_PAD_LEFT);
        $code = 'C-'.$pad;
        // --------------------------------------------------
        $client = new Client();
        $client->nom_client = $request->input('nom_client');
        $client->adresse = $request->input('adresse');
        $client->telephone = $request->input('telephone');
        $client->solde = $request->input('solde');
        $client->code = $code;
        $client->ICE = Str::slug($client->nom_client, '-');
        $client->user_id = $user_id;
        $client->save();
        $request->session()->flash('status','Le client a été bien enregistré !');
        return redirect()->route('client.index');
    }
    
    public function show(Client $client){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $client = Client::where('user_id',$user_id)->findOrFail($client->id);
        $commandes = Commande::where([['client_id', '=', $client->id],['user_id',$user_id]])->get();
        $cmd = Commande::where('client_id', '=', $client->id)->get();
        $count = $cmd->count();
        $reste = 0;
        if($count>0){
            foreach ($cmd as $key => $commande) {
                $reste += $commande->reste;
            }
        }
        if($this->hasPermssion('show1') == 'yes')
        return view('managements.clients.show')->with([
            'commandes' => $commandes,
            'client' => $client,
            'count' => $count,
            'reste' => $reste
        ]);
        else
        return redirect()->back();
    }
    
    public function edit(Client $client){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $client = Client::where('user_id',$user_id)->findOrFail($client->id);
        if($this->hasPermssion('edit1') == 'yes')
        return view('managements.clients.edit')->with([
            "client" => $client
        ]);
        else
        return redirect()->back();
    }
    
    public function update(Request $request, Client $client){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $client->nom_client = $request->input('nom_client');
        $client->adresse = $request->input('adresse');
        $client->telephone = $request->input('telephone');
        $client->solde = $request->input('solde');
        $client->ICE = Str::slug($client->nom_client, '-');
        $client->user_id = $user_id;

        $client->save();

        $request->session()->flash('status','Le client a été bien modifié !');
        return redirect()->route('client.index');
    }
    
    public function destroy(Client $client){
        $commandes = Commande::where('client_id','=',$client->id)->get();
        if($commandes->count() != 0){
            $msg = "Erreur, Le client déja passer une commande !";
        }
        elseif($commandes->count() == 0){
            $client->delete();
            $msg = "Le client a été supprimé avec succès";
        }
        return redirect()->route('client.index')->with(["status" => $msg]); 
    }
    // ---------------------
}
