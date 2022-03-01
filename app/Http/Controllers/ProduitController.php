<?php

namespace App\Http\Controllers;

use App\Produit;
use App\Categorie;
use App\Lignecommande;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
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
    * searchProduit
    *--------------------------------------------------------------------------
    **/
    public function searchProduit(Request $request){
        $search = $request->search;
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $produits = Produit::with('categorie')
            ->where([
                [function ($query) use ($search) {
                    $query->where('nom_produit','like',"%$search%")
                    ->orWhere('code_produit','like',"%$search%")
                    ->orWhere('TVA','like',"%$search%")
                    ->orWhere('quantite','like',"%$search%")
                    ->orWhere('prix_HT','like',"%$search%")
                    ->orWhere('prix_TTC','like',"%$search%")
                    ->orWhere('prix_produit_HT','like',"%$search%")
                    ->orWhere('prix_produit_TTC','like',"%$search%");
                }],
                ['user_id',$user_id]
            ])
            ->orWhereHas('categorie',function($query) use($search,$user_id){
                $query->where([['nom_categorie','like',"%$search%"],['user_id',$user_id]]);
            })
            ->orderBy('id','desc')
            ->get();
        return $produits;
    }
    /** 
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $produits = Produit::getProduits();
        if($this->hasPermssion('list3') == 'yes')
        return view('managements.produits.index', compact(['produits','permission']));
        else
        return view('application');
    }

    function fetch_produit(Request $request){
        if($request->ajax()){
            $search = $request->search;
            $produits = Produit::searchProduit($search);
            return view('managements.produits.index_data',compact('produits'))->render();
        }
    }

    public function index_save(){
        $permission = $this->getPermssion(Auth::user()->permission);
        
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $produits = Produit::with('categorie')->orderBy('id', 'desc')->where('user_id',$user_id)->get();
        
        if($this->hasPermssion('list3') == 'yes')
        return view('managements.produits.index', compact(['produits','permission']));
        else
        return view('application');
    }

    public function create(){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories = Categorie::orderBy('id', 'desc')->where('user_id',$user_id)->get();
        
        if($this->hasPermssion('create3') == 'yes')
        return view('managements.produits.create',[
            'categories' => $categories
        ]);
        else
        return redirect()->back();
    }

    public function store(Request $request){  
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $tva = $request->input('tva');

        $ttc = $request->input('prix_produit_TTC');
        $ttcAchat = $request->input('prix_TTC');

        $produit = new Produit();
        $produit->nom_produit = $request->input('nom_produit');
        $produit->code_produit = $request->input('code_produit');
        $produit->TVA = $tva;

        $produit->prix_HT = $ttcAchat / (1 + $tva/100); 
        $produit->prix_TTC = $ttcAchat ;

        $produit->prix_produit_HT = $ttc / (1 + $tva/100); 
        $produit->prix_produit_TTC = $ttc ;

        $produit->description = $request->input('description');

        $produit->categorie_id =  $request->input('nom_categorie');
        $produit->user_id = $user_id;

        $produit->save();
        $request->session()->flash('status','Le produit a été bien enregistré !');
        return redirect()->route('produit.index');
    }
    
    public function show(Produit $produit){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $produit = Produit::with('categorie')->where('user_id',$user_id)->findOrFail($produit->id);
        if($this->hasPermssion('show3') == 'yes')
        return view('managements.produits.show', [
            "produit" => $produit
        ]);
        else
        return redirect()->back();
    }

    public function edit(Produit $produit){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $produit = Produit::with('categorie')->where('user_id',$user_id)->findOrFail($produit->id);
        $categories = Categorie::orderBy('id', 'desc')->where('user_id',Auth::user()->id)->get();
        if($this->hasPermssion('edit3') == 'yes')
        return view('managements.produits.edit')->with([
            "produit" => $produit,
            'categories' => $categories
        ]);
        else
        return redirect()->back();
    }
    
    public function update(Request $request, Produit $produit){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $tva = $request->input('tva');

        $ttc = $request->input('prix_produit_TTC');
        $ttcAchat = $request->input('prix_TTC');
        
        $produit->nom_produit = $request->input('nom_produit');
        $produit->code_produit = $request->input('code_produit');

        $produit->TVA = $tva;

        $produit->prix_HT = round($ttcAchat / (1 + $tva/100), 2); 
        $produit->prix_TTC = $ttcAchat ;

        $produit->prix_produit_HT = $ttc / (1 + $tva/100); 
        $produit->prix_produit_TTC = $ttc ;

        $produit->description = $request->input('description');

        $produit->categorie_id =  $request->input('nom_categorie');
        $produit->user_id = $user_id;
        // --------------------------------------------------------
        $produit->save();
        $request->session()->flash('status','Le produit a été bien modifié !');
        return redirect()->route('produit.index');
    }

    public function destroy(Produit $produit){
        $existe = false;
            
        $lgcommande = Lignecommande::where('produit_id', '=', $produit->id )->first();
        
        if($lgcommande){
            $existe = true;
        }

        if($existe){
            $msg = " Attention !! le produit ne peut pas être supprimée car déja appartient à une commande";
        }
    
        else{
            $produit->delete();
            $msg = "Le produit a été supprimé avec succès !";
        }

        return redirect()->route('produit.index')->with([
            "status" => $msg
        ]); 
    }
    // ---------------------
}
