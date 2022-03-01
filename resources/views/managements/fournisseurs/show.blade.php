@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Fiche de fournisseur</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Fournisseur</li>
    </ol>
</div>
{{-- ################## --}}
{{-- ################## --}}
<?php
    $code = 'vide';
    if($fournisseur->code) $code = $fournisseur->code;
    $nom_fournisseur = 'vide';
    if($fournisseur->nom_fournisseur) $nom_fournisseur = $fournisseur->nom_fournisseur;
    $tel = 'vide';
    if($fournisseur->tel) $tel = $fournisseur->tel;
    $adresse = 'vide';
    if($fournisseur->adresse) $adresse = $fournisseur->adresse;
    $ville = 'vide';
    if($fournisseur->ville) $ville = $fournisseur->ville;
    $code_postal = 'vide';
    if($fournisseur->code_postal) $code_postal = $fournisseur->code_postal;
    $pays = 'vide';
    if($fournisseur->pays) $pays = $fournisseur->pays;
    $site = 'vide';
    if($fournisseur->site) $site = $fournisseur->site;
    $email = 'vide';
    if($fournisseur->email) $email = $fournisseur->email;
    $iff = 'vide';
    if($fournisseur->iff) $iff = $fournisseur->iff;
    $capital = 'vide';
    if($fournisseur->capital) $capital = $fournisseur->capital;
    $rc = 'vide';
    if($fournisseur->rc) $rc = $fournisseur->rc;
    $patente = 'vide';
    if($fournisseur->patente) $patente = $fournisseur->patente;
    $cnss = 'vide';
    if($fournisseur->cnss) $cnss = $fournisseur->cnss;
    $banque = 'vide';
    if($fournisseur->banque) $banque = $fournisseur->banque;
    $rib = 'vide';
    if($fournisseur->rib) $rib = $fournisseur->rib;
    $created_at = 'vide';
    if($fournisseur->created_at) $created_at = $fournisseur->created_at;
    $updated_at = 'vide';
    if($fournisseur->updated_at) $updated_at = $fournisseur->updated_at;
?>   
{{-- ################## --}}
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <div class="card text-left">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Identifiant :</h5>
                    <div>
                        <span class="badge badge-primary">{{$code}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Nom/Raison sociale : </h5>
                    <div>
                        <span class="badge badge-primary">{{$nom_fournisseur}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Nb des achats : </h5>
                    <div>
                        <span class="badge badge-primary">{{$count}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Reste à payer : </h5>
                    <div>
                        <span class="badge badge-primary">{{number_format($reste,2, '.', '')}} DH</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Télèphone : </h5>
                    <div>
                        <span class="badge badge-primary">{{$tel}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Adresse : </h5>
                    <div>
                        <span class="badge badge-primary">{{$adresse}}</span>
                    </div>
                    <hr>
                </div>
                {{-- ###################################### --}}
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Ville : </h5>
                    <div>
                        <span class="badge badge-primary">{{$ville}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Code Postal : </h5>
                    <div>
                        <span class="badge badge-primary">{{$code_postal}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Pays: </h5>
                    <div>
                        <span class="badge badge-primary">{{$pays}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Site web : </h5>
                    <div>
                        <span class="badge badge-primary">{{$site}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Email : </h5>
                    <div>
                        <span class="badge badge-primary">{{$email}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Identifiant fiscal : </h5>
                    <div>
                        <span class="badge badge-primary">{{$iff}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Capital :</h5>
                    <div>
                        <span class="badge badge-primary">{{$capital}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Registre du commerce :</h5>
                    <div>
                        <span class="badge badge-primary">{{$rc}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Patente :</h5>
                    <div>
                        <span class="badge badge-primary">{{$patente}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>CNSS :</h5>
                    <div>
                        <span class="badge badge-primary">{{$cnss}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Banque :</h5>
                    <div>
                        <span class="badge badge-primary">{{$banque}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>RIB :</h5>
                    <div>
                        <span class="badge badge-primary">{{$rib}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Crée le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$created_at}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Modifié le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$updated_at}}</span>
                    </div>
                    <hr>
                </div>
                {{-- ###################################### --}}
            </div>
        </div>
    </div>
    <br>
    <!-- Main card -->
    {{-- ---------------- --}}
    <div class="card">
        <div class="card-body">
            <h3 class="card-title text-center" id="title">Les achats chez le fournisseur :
                <span class="badge badge-dark">{{count($demandes)}}</span>
            </h3>
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Rèf</th>
                            <th>Date</th>
                            <th>Facture</th>
                            <th>Total</th>
                            <th>Avance</th>
                            <th>Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($demandes as $demande)
                            @php
                            $facture = '';
                            if($demande->facture) $facture = $demande->facture;
                            @endphp
                            <tr>
                                <td>{{$demande->code }}</td>
                                <td>{{$demande->date}}</td>
                                <td>{{$facture}}</td>
                                <td>{{number_format($demande->total,2, '.', '')}}</td>
                                <td>{{number_format($demande->avance,2, '.', '')}}</td>
                                <td>{{number_format($demande->reste,2, '.', '')}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.content --> 

@endsection

