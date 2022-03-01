<?php

use App\Categorie;
use App\Produit;
use App\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

/*
|--------------------------------------------------------------------------
| API categories
|--------------------------------------------------------------------------
*/
// $user_id = Auth::user()->id;
// if(Auth::user()->is_admin == 0)
//     $user_id = Auth::user()->user_id;
$user_id = 1;
Route::get('/reglements', function (Request $request) use($user_id){
    $reglements = Reglement::where('user_id',$user_id)->get();
    return response()->json($reglements);
});

Route::get('/categories', function (Request $request) use($user_id){
    $categories = Categorie::where('user_id',$user_id)->get();
    return response()->json($categories);
});

Route::get('/categories/{id}', function (Request $request,$id)  use($user_id){
    $categorie = Categorie::where('user_id',$user_id)->find($id);
    return response()->json($categorie);
});
/*
|--------------------------------------------------------------------------
| API Products
|--------------------------------------------------------------------------
*/
Route::get('/products', function (Request $request)  use($user_id){
    $produits = Produit::where('user_id',$user_id)->get();
    return response()->json($produits);
});

Route::get('/products/{id}', function (Request $request,$id) use($user_id) {
    $produit = Produit::where('user_id',$user_id)->find($id);
    return response()->json($produit);
});

Route::get('/productsByCategory/{id}', function (Request $request,$id) use($user_id){
    $produits = Produit::where('user_id',$user_id)->where('categorie_id',$id)->get();
    return response()->json($produits);
});

/*
Produits : get show bycategory search
Categories : get show search
// ajouter image pour produits et categories
Clients : get show search
Commandes : create delete
Commandes reçus //ajouter une nouvette attribute status encours   
Table commercents (users) : get show
*/
