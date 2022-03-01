@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Ajout d'un client</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Client</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <div class="row m-t-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg-blue">
                    <h5 class="text-white m-b-0">Formulaire de client</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('client.store')}}" method="POST">
                        @csrf 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="nom_client">Nom complet</label>
                                <input class="form-control" placeholder="Nom complet" type="text" name="nom_client" id="nom_client">
                                <span class="fa fa-user form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="adresse">Adresse</label>
                                <input class="form-control" placeholder="Adresse" type="text" name="adresse" id="adresse">
                                <span class="fa fa-map-marker form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="telephone">Téléphone</label>
                                <input class="form-control" placeholder="Téléphone" type="text" name="telephone" id="telephone">
                                <span class="fa fa-phone form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="solde">Solde</label>
                                <input class="form-control" placeholder="Solde" type="number" step="0.01" min="0" value="0" name="solde" id="solde">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success" name="sendClient" disabled>Valider</button>
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
        var btn = $('button[name=sendClient]');
        (!nom_client && nom_client=='') ? btn.prop('disabled',true):btn.prop('disabled',false) ;
    })
</script>
{{-- ################## --}}
@endsection