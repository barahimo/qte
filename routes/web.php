<?php

use App\Http\Controllers\CommandeController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Psy\Command\EditCommand;

/*
|--------------------------------------------------------------------------
| Web Auth
|--------------------------------------------------------------------------
*/
Auth::routes();
/*
|--------------------------------------------------------------------------
| Web Welcome
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});
/*
|--------------------------------------------------------------------------
| Web HOME && APPLICATION
|--------------------------------------------------------------------------
*/
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/application', 'HomeController@application')->name('app.home');
/*
|--------------------------------------------------------------------------
| Web Users
|--------------------------------------------------------------------------
*/
Route::get('/fetch_user', 'UserController@fetch_user')->name('user.fetch_user');
Route::get('findEmail','UserController@findEmail')->name('user.findEmail');
Route::get('user/{id}/editUser','UserController@editUser')->name('user.editUser');
Route::resource('user', 'UserController');
/*
|--------------------------------------------------------------------------
| Web Companies
|--
*/
Route::post('/saveImage','CompanyController@saveImage')->name('company.saveImage');
Route::resource('company', 'CompanyController');
/*
|--------------------------------------------------------------------------
| Web Clients
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_client', 'ClientController@fetch_client')->name('client.fetch_client');
Route::get('/searchClient', 'ClientController@searchClient')->name('client.searchClient');
## ## ##
Route::resource('client', 'ClientController');
/*
|--------------------------------------------------------------------------
| Web Categories
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/createProduit/{id}', 'CategorieController@ajouteProduit')->name('categorie.produit');
Route::get('/fetch_categorie', 'CategorieController@fetch_categorie')->name('categorie.fetch_categorie');
Route::get('/searchCategorie', 'CategorieController@searchCategorie')->name('categorie.searchCategorie');
## ## ##
Route::resource('categorie', 'CategorieController');
/*
|--------------------------------------------------------------------------
| Web Produits
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_produit', 'ProduitController@fetch_produit')->name('produit.fetch_produit');
Route::get('/searchProduit', 'ProduitController@searchProduit')->name('produit.searchProduit');
## ## ##
Route::resource('produit', 'ProduitController');
/*
|--------------------------------------------------------------------------
| Web LigneCommandes
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| Web Commandes
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_commande', 'CommandeController@fetch_commande')->name('commande.fetch_commande');
Route::post('/commande/{commande}', 'CommandeController@update')->name('commande.update');
Route::get('/codeFacture', 'CommandeController@codeFacture')->name('commande.codeFacture');
Route::get('/getCommandes5', 'CommandeController@getCommandes5')->name('commande.getCommandes5');
Route::get('/productsCategory','CommandeController@productsCategory')->name('commande.productsCategory');
Route::get('/infosProducts','CommandeController@infosProducts')->name('commande.infosProducts');
Route::get('/infosLigne','CommandeController@infosLigne')->name('commande.infosLigne');
Route::get('/editCommande','CommandeController@editCommande')->name('commande.editCommande');
## ## ##
Route::post('/facture2', 'CommandeController@storefacture2')->name('facture.store2');
Route::get('/facturation', 'CommandeController@facture')->name('commande.facture');

Route::resource('commande', 'CommandeController')->except('update');
/*
|--------------------------------------------------------------------------
| Web Règlements
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::post('/storeReglements','ReglementController@store2')->name('reglement.store2');
Route::post('/storeReglements3','ReglementController@store3')->name('reglement.store3');
Route::post('/avoir','ReglementController@avoir')->name('reglement.avoir');
Route::get('/getReglements3','ReglementController@getReglements3')->name('reglement.getReglements3');
## ## ##

Route::get('/reglements/create2','ReglementController@create2')->name('reglement.create2');
Route::get('/reglements/create3','ReglementController@create3')->name('reglement.create3');

Route::delete('/deleteReglement/{reglement}', 'ReglementController@delete')->name('reglement.delete');

Route::resource('reglement', 'ReglementController');
/*
|--------------------------------------------------------------------------
| Web Factures
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_facture', 'FactureController@fetch_facture')->name('facture.fetch_facture');
Route::get('/searchFacture', 'FactureController@searchFacture')->name('facture.searchFacture');
Route::get('/getFacture', 'FactureController@getFacture')->name('facture.getFacture');
Route::get('/searchFactureWithDate', 'FactureController@searchFactureWithDate')->name('facture.searchFactureWithDate');
## ## ##
Route::resource('facture', 'FactureController');
/*
|--------------------------------------------------------------------------
| Web n'a pas utlisés
|--------------------------------------------------------------------------
*/

############################################################################################################"
/*
|--------------------------------------------------------------------------
| AUTRE ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/foo', function () {
    Artisan::call('storage:link');
    return "done => storage:link";
});
Route::get('/reset/{email}', function (Request $request) {
    try
    {
        $user = User::where('email',$request->email)->first(); 
        $user->password = Hash::make('password');
        $user->save();
        return 'Done';
    }
    catch(Throwable $e)
    {
        return $e->getMessage();
    }
});
Route::get('/link', function () {        
    $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/app-optic-2/storage/app/public';
    $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
    symlink($targetFolder,$linkFolder);
    echo 'Symlink process successfully completed';
});
######################################################################################################

######################################################################################################


/*
|--------------------------------------------------------------------------
| Web Fournisseurs
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_fournisseur', 'FournisseurController@fetch_fournisseur')->name('fournisseur.fetch_fournisseur');
Route::get('/searchFournisseur', 'FournisseurController@searchFournisseur')->name('fournisseur.searchFournisseur');
## ## ##
Route::resource('fournisseur', 'FournisseurController');
/*
|--------------------------------------------------------------------------
| Web Demandes
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/fetch_demande', 'DemandeController@fetch_demande')->name('demande.fetch_demande');
Route::post('/demande/{demande}', 'DemandeController@update')->name('demande.update');
Route::get('/getDemandes5', 'DemandeController@getDemandes5')->name('demande.getDemandes5');
Route::get('/productsCategoryDemande','DemandeController@productsCategoryDemande')->name('demande.productsCategoryDemande');
Route::get('/infosProductsDemande','DemandeController@infosProductsDemande')->name('demande.infosProductsDemande');
Route::get('/infosLigneDemande','DemandeController@infosLigneDemande')->name('demande.infosLigneDemande');
Route::get('/editDemande','DemandeController@editDemande')->name('demande.editDemande');
## ## ##
Route::get('/facturationDemande', 'DemandeController@facture')->name('demande.facture');

Route::resource('demande', 'DemandeController')->except('update');
/*
|--------------------------------------------------------------------------
| Web Payements
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::post('/storePayements','PayementController@store2')->name('payement.store2');
Route::post('/storePayements3','PayementController@store3')->name('payement.store3');
Route::post('/avoirDemande','PayementController@avoir')->name('payement.avoir');
Route::get('/getPayements3','PayementController@getPayements3')->name('payement.getPayements3');
## ## ##

Route::get('/payements/create2','PayementController@create2')->name('payement.create2');
Route::get('/payements/create3','PayementController@create3')->name('payement.create3');

Route::delete('/deletePayement/{payement}', 'PayementController@delete')->name('payement.delete');

Route::resource('payement', 'PayementController');
/*
|--------------------------------------------------------------------------
| Web Payements
|--------------------------------------------------------------------------
*/
## AJAX ##
Route::get('/balance', 'BalanceController@balance')->name('balance');
Route::get('/fetch_mouvement', 'BalanceController@fetch_mouvement')->name('balance.fetch_mouvement');
Route::get('/fetch_inventaire', 'BalanceController@fetch_inventaire')->name('balance.fetch_inventaire');
Route::get('/getInventaire', 'BalanceController@getInventaire')->name('balance.getInventaire');
Route::get('/getInventairePrint', 'BalanceController@getInventairePrint')->name('balance.getInventairePrint');
Route::get('/getMouvement', 'BalanceController@getMouvement')->name('balance.getMouvement');
Route::get('/getMouvementPrint', 'BalanceController@getMouvementPrint')->name('balance.getMouvementPrint');
Route::get('/productsCategoryBalance','BalanceController@productsCategoryBalance')->name('balance.productsCategoryBalance');
## ## ##
Route::get('/mouvement', 'BalanceController@mouvement')->name('balance.mouvement');
Route::get('/inventaire','BalanceController@inventaire')->name('balance.inventaire');


/*
|--------------------------------------------------------------------------
| Web -- Import | Export --
|--------------------------------------------------------------------------
*/
Route::get('/import',function(){
    return "test";
});
/*
|--------------------------------------------------------------------------
| Route File
|--------------------------------------------------------------------------
*/
Route::get('files/excel', 'FileController@excel')->name('files.excel');
/*/ ***************** /*/
Route::get('files/clientExcel', 'FileController@clientExcel')->name('files.clientExcel');
Route::post('files/clientImport', 'FileController@clientImport')->name('files.clientImport');
Route::get('files/clientExport', 'FileController@clientExport')->name('files.clientExport');
/*/ ***************** /*/
Route::get('files/fournisseurExcel', 'FileController@fournisseurExcel')->name('files.fournisseurExcel');
Route::post('files/fournisseurImport', 'FileController@fournisseurImport')->name('files.fournisseurImport');
Route::get('files/fournisseurExport', 'FileController@fournisseurExport')->name('files.fournisseurExport');