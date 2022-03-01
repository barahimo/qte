@extends('layout.dashboard')
@section('contenu')
{{-- BEGIN PHP --}}
<?php
    use Illuminate\Support\Facades\Auth;
    $user_id = Auth::user()->id;
    if(Auth::user()->is_admin == 0)
        $user_id = Auth::user()->user_id;

    $nbclients = App\Client::where('user_id',$user_id)->get()->count();
    $nbfournisseurs = App\Fournisseur::where('user_id',$user_id)->get()->count();
    $nbcategories = App\Categorie::where('user_id',$user_id)->get()->count();
    $nbproduits = App\Produit::where('user_id',$user_id)->get()->count();
    $nbcommandes = App\Commande::where('user_id',$user_id)->get()->count();
    $nbdemandes = App\Demande::where('user_id',$user_id)->get()->count();
    $nbreglements = App\Reglement::where('user_id',$user_id)->get()->count();
    $nbpayements = App\Payement::where('user_id',$user_id)->get()->count();
    $nbfactures = App\Facture::where('user_id',$user_id)->get()->count();
    
    $clients = App\Client::where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $fournisseurs = App\Fournisseur::where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $categories = App\Categorie::where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $produits = App\Produit::where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $commandes = App\Commande::with('client')->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $demandes = App\Demande::with('fournisseur')->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $reglements = App\Reglement::with(['commande'=>function($query){
        $query->with('client');
    }])->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $payements = App\Payement::with(['demande'=>function($query){
        $query->with('fournisseur');
    }])->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    $factures = App\Facture::with(['commande'=>function($query){
        $query->with('client');
    }])->where('user_id',$user_id)->orderBy('id','desc')->limit(3)->get();
    // echo count($factures);
    // return;
    ?>
{{-- END PHP --}}

{{-- ################################################## --}}
<!-- Main content -->
<div class="content">
    @if(
        (Auth::user()->is_admin == 2) ||
        (Auth::user()->is_admin == 1 && Auth::user()->status == 1) ||
        (Auth::user()->is_admin == 0 && Auth::user()->status == 1 && App\User::find(Auth::user()->user_id)->status == 1)
    )
    <!-- Main row -->
    <div class="row">
        {{-- begin Clients --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-people f-30 text-blue"></i> </div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Fournisseurs</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbfournisseurs}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Clients --}}
        {{-- begin Clients --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-people f-30 text-blue"></i> </div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Clients</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbclients}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Clients --}}
        {{-- begin Catégories --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-grid f-30 text-green"></i></div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Catégories</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbcategories}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Catégories --}}
        {{-- begin Produits --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-eyeglass f-30 text-red"></i></div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Produits</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbproduits}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Produits --}}
        {{-- begin Demandes --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-basket f-30 text-dark"></i> </div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Achats</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbcommandes}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Demandes --}}
        {{-- begin Commandes --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-basket-loaded f-30 text-dark"></i> </div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Ventes</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbdemandes}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Commandes --}}
        {{-- begin Factures --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-docs f-30 text-orange"></i></div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Factures</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbfactures}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Factures --}}
        {{-- begin Règlements Fournisseurs --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-paypal f-30 text-purple"></i></div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Règ. Fournisseurs</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbpayements}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Règlements Fournisseurs --}}
        {{-- begin Règlements clients --}}
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <div class="card-body">
                <div class="m-b-1"> <i class="icon-paypal f-30 text-purple"></i></div>
                <div class="info-widget-text row">
                <div class="col-sm-7 pull-left"><span>Règ. Clients</span></div>
                <div class="col-sm-5 pull-right text-right text-blue f-25">{{$nbreglements}}</div>
                </div>
            </div>
            </div>
        </div>
        {{-- end Règlements clients --}}
    </div>
    <!-- Main card -->
    <div class="card">
        <div class="card-body">
            <h4 class="text-black">Activités récentes</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Fournisseurs</th>
                            <th>Clients</th>
                            <th>Catégories</th>
                            <th>Produits</th>
                            <th>Achats</th>
                            <th>Ventes</th>
                            <th>Règ. Four.</th>
                            <th>Règ. Clts</th>
                            <th>Factures</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                @if(count($fournisseurs)>0) 
                                    {{$fournisseurs[0]->code}}<br>
                                    <span class="badge badge-light">{{$fournisseurs[0]->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($clients)>0) 
                                    {{$clients[0]->code}}<br>
                                    <span class="badge badge-light">{{$clients[0]->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($categories)>0) 
                                    {{$categories[0]->nom_categorie}}
                                @endif
                            </td>
                            <td>
                                @if(count($produits)>0) 
                                    {{$produits[0]->code_produit}}
                                @endif
                            </td>
                            <td>
                                @if(count($demandes)>0) 
                                    {{$demandes[0]->code}}<br>
                                    <span class="badge badge-light">{{$demandes[0]->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($commandes)>0) 
                                    {{$commandes[0]->code}}<br>
                                    <span class="badge badge-light">{{$commandes[0]->client->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($payements)>0) 
                                    {{$payements[0]->code}}<br>
                                    <span class="badge badge-light">{{$payements[0]->demande->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($reglements)>0) 
                                    {{$reglements[0]->code}}<br>
                                    <span class="badge badge-light">{{$reglements[0]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($factures)>0) 
                                    {{$factures[0]->code}}<br>
                                    <span class="badge badge-light">{{$factures[0]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if(count($fournisseurs)>1) 
                                    {{$fournisseurs[1]->code}}<br>
                                    <span class="badge badge-light">{{$fournisseurs[1]->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($clients)>1) 
                                    {{$clients[1]->code}} <br> 
                                    <span class="badge badge-light">{{$clients[1]->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($categories)>1) 
                                    {{$categories[1]->nom_categorie}}
                                @endif
                            </td>
                            <td>
                                @if(count($produits)>1) 
                                    {{$produits[1]->code_produit}}
                                @endif
                            </td>
                            <td>
                                @if(count($demandes)>1) 
                                    {{$demandes[1]->code}}<br>
                                    <span class="badge badge-light">{{$demandes[1]->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($commandes)>1) 
                                    {{$commandes[1]->code}}<br>
                                    <span class="badge badge-light">{{$commandes[1]->client->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($payements)>1) 
                                    {{$payements[1]->code}}<br>
                                    <span class="badge badge-light">{{$payements[1]->demande->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($reglements)>1) 
                                    {{$reglements[1]->code}}<br>
                                    <span class="badge badge-light">{{$reglements[1]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($factures)>1) 
                                    {{$factures[1]->code}}<br>
                                    <span class="badge badge-light">{{$factures[1]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if(count($fournisseurs)>2) 
                                    {{$fournisseurs[2]->code}}<br>
                                    <span class="badge badge-light">{{$fournisseurs[2]->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($clients)>2) 
                                    {{$clients[2]->code}} <br> 
                                    <span class="badge badge-light">{{$clients[2]->nom_client}}</span> 
                                @endif
                            </td>
                            <td>
                                @if(count($categories)>2) 
                                    {{$categories[2]->nom_categorie}}
                                @endif
                            </td>
                            <td>
                                @if(count($produits)>2) 
                                    {{$produits[2]->code_produit}}
                                @endif
                            </td>
                            <td>
                                @if(count($demandes)>2) 
                                    {{$demandes[2]->code}}<br>
                                    <span class="badge badge-light">{{$demandes[2]->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($commandes)>2) 
                                    {{$commandes[2]->code}} <br> 
                                    <span class="badge badge-light">{{$commandes[2]->client->nom_client}}</span> 
                                @endif
                            </td>
                            <td>
                                @if(count($payements)>2) 
                                    {{$payements[2]->code}}<br>
                                    <span class="badge badge-light">{{$payements[2]->demande->fournisseur->nom_fournisseur}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($reglements)>2) 
                                    {{$reglements[2]->code}}<br>
                                    <span class="badge badge-light">{{$reglements[2]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                            <td>
                                @if(count($factures)>2) 
                                    {{$factures[2]->code}}<br>
                                    <span class="badge badge-light">{{$factures[2]->commande->client->nom_client}}</span>
                                @endif
                            </td>
                        </tr>  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- @elseif((Auth::user()->status == 0) || (Auth::user()->is_admin == 0 && User::find(Auth::user()->user_id)->status == 0)) --}}
    @else
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="row">
        <div class="col"></div>
        <div class="col-6 alert text-center" role="alert" style="color:red; border : 1px dashed black;">
            <img src="{{asset('images/interdit.png')}}" alt="interdit" style="width:120px">
            <h4 class="alert-heading">Accès interdit !</h4>
            <p>Vous n'êtes pas autorisé à accéder à cette application</p>
            <hr>
            <p class="mb-0">Merci de contacter votre administrateur.</p>
        </div>
        <div class="col"></div>
    </div>
    @endif
</div>
<!-- /.content --> 
@endsection