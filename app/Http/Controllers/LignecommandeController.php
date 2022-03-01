<?php

namespace App\Http\Controllers;

use App\Client;
use App\Produit;
use App\Commande;
use App\Categorie;
use App\Lignecommande;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LignecommandeController extends Controller
{
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
    public function index() {
        
    }

    public function create(Request $request, Commande $commande){

    }
    
    
    public function store(Request $request){     
    }

    public function show(Lignecommande $lignecommande){
    }
    
    public function edit(Lignecommande $lignecommande){
        
    }
    
    public function update(Request $request, Lignecommande $lignecommande){
    }
    
    public function destroy(Lignecommande $lignecommande){
        
    }

    public function search(Request $request){
    }
}
