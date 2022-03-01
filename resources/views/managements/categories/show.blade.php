@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Fiche de catégorie</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Catégorie</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <div class="card text-left">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Nom catégorie :</h5>
                    <div>
                        <span class="badge badge-primary">{{$categorie->nom_categorie}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Crée le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$categorie->created_at}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Modifié le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$categorie->updated_at}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 ">
                    <h5>Description : </h5>
                    <div>
                        <p class="bg-primary text-white"  style="border-radius:5px; ">{{$categorie->description}}</p>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <br>
    <!-- Main card -->
    <div class="card">
        <div class="card-body">
            {{-- ---------------- --}}
            <h3 class="card-title text-center" id="title">Les produits de la catégorie :
                <span class="badge badge-dark">{{count($produits)}}</span>
            </h3>
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Libelle</th>
                            <th>TVA</th>
                            <th>prix HT</th>
                            <th>prix TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produits as $key => $produit)
                        <tr>
                            <td>{{$key + 1 }}</td>
                            <td>{{$produit->code_produit}}</td>
                            <td>{{$produit->nom_produit}}</td>
                            <td>{{$produit->TVA}}</td>
                            <td>{{number_format($produit->prix_produit_HT,2)}}</td>
                            <td>{{number_format($produit->prix_produit_TTC,2)}}</td>
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