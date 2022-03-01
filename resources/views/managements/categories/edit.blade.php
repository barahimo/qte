@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Modification d'une catégorie</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Catégorie</li>
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
                    <h5 class="text-white m-b-0">Catégorie</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('categorie.update',['categorie'=> $categorie])}}" method="POST">
                        @csrf 
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Nom catégorie</label>
                                <input class="form-control" placeholder="Nom catégorie" type="text" id="nom_categorie" name="nom_categorie"  value="{{ old('nom_categorie', $categorie->nom_categorie?? null) }}">
                                <span class="fa fa-user form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label class="control-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $categorie->description?? null) }}</textarea>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-warning text-white" name="updateCategorie">Modifier</button>
                                &nbsp;
                                <a href="{{action('CategorieController@index')}}" class="btn btn-info">Retour</a>
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
    $(document).on('keyup','input[name=nom_categorie]',function(){
        var nom_client = $('input[name=nom_categorie]').val();
        var btn = $('button[name=updateCategorie]');
        (!nom_client && nom_client=='') ? btn.prop('disabled',true):btn.prop('disabled',false) ;
    })
</script>
{{-- ################## --}}
@endsection