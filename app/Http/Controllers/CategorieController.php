<?php

namespace App\Http\Controllers;

use App\Produit;
use App\Categorie;
use App\Lignecommande;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategorieController extends Controller
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
    * ajouteProduit
    *--------------------------------------------------------------------------
    **/
    public function ajouteProduit(Request $request,$id){
        $categorie=Categorie::find($id);
        return view('managements.categories.createProduit', [
            'categorie' => $categorie
        ]);
    }
    /** 
    *--------------------------------------------------------------------------
    * searchCategorie
    *--------------------------------------------------------------------------
    **/
    public function searchCategorie(Request $request){
        $search = $request->search;
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories = Categorie::where([
            [function ($query) use ($search) {
                    $query->where('nom_categorie','like',"%$search%")
                    ->orWhere('description','like',"%$search%");
            }],
            ['user_id',$user_id]
        ])
        ->orderBy('id','desc')
        ->get();
        return $categories;
    }
    /** 
    *--------------------------------------------------------------------------
    * Ressources
    *--------------------------------------------------------------------------
    **/
    public function index(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $categories = Categorie::getCategories();
        if($this->hasPermssion('list2') == 'yes')
            return view('managements.categories.index', compact(['categories','permission']));
        else
            return view('application');
    }

    function fetch_categorie(Request $request){
        if($request->ajax()){
            $search = $request->search;
            $categories = Categorie::searchCategorie($search);
            return view('managements.categories.index_data',compact('categories'))->render();
        }
    }

    public function index_save(){
        $permission = $this->getPermssion(Auth::user()->permission);
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
            $user_id = Auth::user()->user_id;
        $categories = Categorie::orderBy('id', 'desc')->where('user_id',$user_id)->get();
        if($this->hasPermssion('list2') == 'yes')
            return view('managements.categories.index', compact(['categories','permission']));
        else
            return view('application');
    }

    public function create(){
        $permission = $this->getPermssion(Auth::user()->permission);
        // if(in_array('create2',$permission) || Auth::user()->is_admin == 2)
        if($this->hasPermssion('create2') == 'yes')
        return view('managements.categories.create');
        else
        return redirect()->back();
    }

    public function store(Request $request){  
        $user_id = Auth::user()->id;
            if(Auth::user()->is_admin == 0)
                $user_id = Auth::user()->user_id;
        $categorie = new Categorie();
        $categorie ->nom_categorie = $request->input('nom_categorie');
        $categorie ->description = $request->input('description');
        $categorie->user_id = $user_id;

        $categorie->save();

        $request->session()->flash('status','La catégorie a été bien enregistrée !');
        return redirect()->route('categorie.index');
    }
    
    public function show(Categorie $categorie){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $categorie = categorie::where('user_id',$user_id)->findOrFail($categorie->id);
        $produits =  Produit::where('categorie_id', '=', $categorie->id)->get();
    
        if($this->hasPermssion('show2') == 'yes')
        return view('managements.categories.show', [
            "categorie" => $categorie,
            "produits" => $produits 
        ]);
        else
        return redirect()->back();
    }
    
    public function edit(Categorie $categorie){
        $user_id = Auth::user()->id;
        if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;
        $categorie = categorie::where('user_id',$user_id)->findOrFail($categorie->id);
        
        if($this->hasPermssion('edit2') == 'yes')
        return view('managements.categories.edit')->with([
            "categorie" => $categorie
        ]);
        else
        return redirect()->back();
    }
    
    public function update(Request $request, Categorie $categorie){ 
        $user_id = Auth::user()->id;
            if(Auth::user()->is_admin == 0)
                $user_id = Auth::user()->user_id;
        $categorie ->nom_categorie = $request->input('nom_categorie');
        $categorie ->description = $request->input('description');
        $categorie->user_id = $user_id;

        $categorie->save();


        $request->session()->flash('status','La catégorie a été bien modifiée !');


        return redirect()->route('categorie.index');

    }

    public function destroy(Categorie $categorie){
        $produits = Produit::where('categorie_id','=',$categorie->id)->get();
        if($produits->count()){
            $existe = false;
            foreach ($produits as $produit) {
                $lgcommande = Lignecommande::where('produit_id', '=', $produit->id )->first();
                if($lgcommande){
                    $existe = true;
                    break;
                }
            }
            if($existe){
                $msg = "La catégorie ne peut pas être supprimée car ses produits sont déja appartient à une commande";
            }
            else{
                $msg = "La catègorie et ses produits sont supprimées avec succès !";
                foreach ($produits as $produit) {
                    $produit->delete();
                }
                $categorie->delete();
            }
        }
        else{
            $categorie->delete();
            $msg = "La catégorie a été supprimée avec succès !";
        }

        return redirect()->route('categorie.index')->with([
            "status" => $msg
        ]); 
    }

    /** 
    *--------------------------------------------------------------------------
    * Autres fonctions
    *--------------------------------------------------------------------------
    **/
}
