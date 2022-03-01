@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
<!-- ##################################################################### -->
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Panneau des commandes</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Commandes</li>
  </ol>
</div>
{{-- ################## --}}
{{ Html::style(asset('css/loadingstyle.css')) }}
{{-- ################## --}}
<div style="display:none;" id="loading" class="text-center">
  <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width:200px">
</div>
{{-- ################## --}}
<div class="container">
  <br>
  <div class="row">
    <div class="col text-left">
      @if(hasPermssion('create5') == 'yes') 
      <a id="create" href="{{route('reglement.create2')}}" class="btn btn-primary m-b-10 ">
        <i class="fa fa-plus"></i>&nbsp;Règlements
      </a>
      @endif
    </div>
    <div class="col text-right">
      @if(hasPermssion('create4') == 'yes') 
      <a href="{{route('commande.create')}}" class="btn btn-primary m-b-10 ">
        <i class="fa fa-plus"></i>&nbsp;Commande
      </a>
      @endif
    </div>
  </div>
  <br>
  <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-4">
      <label for="date1">Date de début :</label>
      <input type="date" class="form-control" name="date1" id="date1" placeholder="date1" value={{$dateFrom}}>
      </div> 
      <div class="col-sm-4">
      <label for="date2">Date de fin : </label>
      <input type="date" class="form-control" name="date2" id="date2" placeholder="date2" value={{$date}}>
      </div> 
      <div class="col-sm-2"></div>
    </div>
    <br>
  <div class="row">
    <div class="col-4">
      <select class="form-control" name="client" id="client">
      <option value="">--La liste des clients--</option>
        @foreach($clients as $client)
        <option value="{{$client->id }}">{{ $client->nom_client}}</option>
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
          <div id="commandes_data">
              @include('managements.commandes.index_data')
          </div>
      </div>
  </div>
</div>
<!-- ####################### -->
<!-- ---------  BEGIN SCRIPT --------- -->
@include('managements.commandes.script_cmd')
{{-- {!! Html::script( asset('js/cmd.js')) !!}  --}}
{{-- <script src="{{asset('js/index_commande.js')}}"></script> --}}
<!-- ##################################################################### -->
@endsection