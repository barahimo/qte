@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
<!-- ##################################################################### -->
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Panneau des achats</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Achats</li>
  </ol>
</div>
{{-- ################## --}}
<div class="container">
  <br>
  <div class="row">
    <div class="col text-left">
      @if(hasPermssion('create5_2') == 'yes') 
      <a id="create" href="{{route('payement.create2')}}" class="btn btn-primary m-b-10 ">
        <i class="fa fa-plus"></i>&nbsp;Règlements
      </a>
      @endif
    </div>
    <div class="col text-right">
      @if(hasPermssion('create4_2') == 'yes') 
      <a href="{{route('demande.create')}}" class="btn btn-primary m-b-10 ">
        <i class="fa fa-plus"></i>&nbsp;Achat
      </a>
      @endif
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-4">
      <select class="form-control" name="fournisseur" id="fournisseur">
      <option value="">--La liste des fournisseurs--</option>
        @foreach($fournisseurs as $fournisseur)
        <option value="{{$fournisseur->id }}">{{ $fournisseur->nom_fournisseur}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-4">
      <div class="row">
        <div class="col-6">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="f" id="f" value="f" checked>
            <label for="f">Facturée</label>
          </div>
        </div>
        <div class="col-6">
          <div class="form-check">
              <input type="checkbox" class="form-check-input" name="nf" id="nf" value="nf" checked>
              <label for="nf">Non facturée</label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-6">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="r" id="r" value="r" checked>
            <label for="r">Réglée</label>
          </div>
        </div>
        <div class="col-6">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="nr" id="nr" value="nr" checked>
            <label for="nr">Non réglée</label>
          </div>
        </div>
      </div>
    </div>
    <div class="col-4">
      <input type="text" class="form-control" name="search" id="search" placeholder="search ..">
    </div>
  </div>
</div>
<!-- ####################### -->
<!-- Main content -->
<div class="content">
  <!-- Main card -->
  <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table" id="table">
            <thead class="bg-primary text-white">
              <tr>
                <th>#</th>
                <th>Date</th>
                <th>Fournisseur</th>
                <th>Facture</th>
                <th>Montant total</th>
                <th>Montant payer</th>
                <th>Reste à payer</th>
                <th style="display : none">Status</th>
                <th style="display : none">Facture</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
  </div>
</div>
<!-- ####################### -->
<!-- ---------  BEGIN SCRIPT --------- -->
@include('managements.demandes.script_demande')
{{-- {!! Html::script( asset('js/cmd.js')) !!}  --}}
{{-- <script src="{{asset('js/index_commande.js')}}"></script> --}}
<!-- ##################################################################### -->
@endsection