@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Fiche de client</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Client</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <div class="card text-left">
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Identifiant :</h5>
                    <div>
                        <span class="badge badge-primary">{{$client->code}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Nom Complet : </h5>
                    <div>
                        <span class="badge badge-primary">{{$client->nom_client}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Nb des commandes : </h5>
                    <div>
                        <span class="badge badge-primary">{{$count}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Télèphone : </h5>
                    <div>
                        <span class="badge badge-primary">{{$client->telephone}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Solde : </h5>
                    <div>
                        <span class="badge badge-primary">{{number_format($client->solde,2, '.', '')}} DH</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Reste à payer : </h5>
                    <div>
                        <span class="badge badge-primary">{{number_format($reste,2, '.', '')}} DH</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Adresse : </h5>
                    <div>
                        <span class="badge badge-primary">{{$client->adresse}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Crée le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$client->created_at}}</span>
                    </div>
                    <hr>
                </div>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ">
                    <h5>Modifié le : </h5>
                    <div>
                        <span class="badge badge-primary">{{$client->updated_at}}</span>
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
            <h3 class="card-title text-center" id="title">Les commandes de client :
                <span class="badge badge-dark">{{count($commandes)}}</span>
            </h3>
            <div class="table-responsive">
                <table class="table">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Rèf</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Avance</th>
                            <th>Reste</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                            <tr>
                                <td>{{$commande->code }}</td>
                                <td>{{$commande->date}}</td>
                                <td>{{number_format($commande->total,2, '.', '')}}</td>
                                <td>{{number_format($commande->avance,2, '.', '')}}</td>
                                <td>{{number_format($commande->reste,2, '.', '')}}</td>
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

