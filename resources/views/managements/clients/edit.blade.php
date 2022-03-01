@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Modification de client</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Client</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    {{-- ---------------- --}}
    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire de client</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('client.update',['client'=> $client->id])}}" method="POST">
                        @csrf 
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Nom complet</label>
                                <input class="form-control" placeholder="Nom complet" type="text" name="nom_client" value="{{ old('name', $client->nom_client ?? null) }}">
                                <span class="fa fa-user form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Adresse</label>
                                <input class="form-control" placeholder="Adresse" type="text" name="adresse"  value="{{ old('adresse', $client->adresse ?? null) }}">
                                <span class="fa fa-map-marker form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Téléphone</label>
                                <input class="form-control" placeholder="Téléphone" type="text" name="telephone" value="{{ old('telephone', $client->telephone ?? null) }}">
                                <span class="fa fa-phone form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Solde</label>
                                <input class="form-control" placeholder="Solde" type="number" step="0.01" min="0" name="solde" value="{{ old('solde', $client->solde ?? null) }}">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-warning text-white" name="updateClient">Modifier</button>
                                &nbsp;
                                <a href="{{action('ClientController@index')}}" class="btn btn-info">Retour</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- ---------------- --}}
</div>
<!-- /.content --> 
{{-- ################## --}}
<script type="text/javascript">
    $(document).on('keyup','input[name=nom_client]',function(){
        var nom_client = $('input[name=nom_client]').val();
        // var btn = $('button[type=submit]');
        var btn = $('button[name=updateClient]');
        (!nom_client && nom_client=='') ? btn.prop('disabled',true):btn.prop('disabled',false) ;
    })
</script>
{{-- ################## --}}
@endsection