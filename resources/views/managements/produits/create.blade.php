@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Ajout d'un produit</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Produit</li>
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
                    <h5 class="text-white m-b-0">Nouveau produit</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('produit.store')}}" method="POST">
                        @csrf 
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="code_produit"">Code produit</label>
                                <input class="form-control" placeholder="Code produit" type="text" id="code_produit" name="code_produit">
                                <span class="fa fa-barcode form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="nom_produit">Nom produit</label>
                                <input class="form-control" placeholder="Nom produit" type="text" id="nom_produit" name="nom_produit">
                                <span class="fa fa-bookmark form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="nom_categorie">Catégorie</label>
                                <select class="form-control" id="nom_categorie" name="nom_categorie">
                                    <option value="">-- Catégorie --</option>
                                    @foreach($categories as $categorie)
                                        <option value="{{$categorie->id }}" @if ($categorie->id == old('nom_categorie',$produit->categorie_id ?? null)) selected="selected" @endif> {{ $categorie->nom_categorie}}</option>
                                    @endforeach
                                </select>
                                <span class="fa fa-tags form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="tva">TVA</label>
                                <select  class="form-control" id="tva" name="tva" class="form-control">
                                    <option value="">-- TVA --</option>
                                    <option value="20">20%</option>
                                    <option value="14">14%</option>
                                    <option value="10">10%</option>
                                    <option value="7">7%</option>
                                    <option value="0">0%</option>
                                </select>
                                <span class="fa fa-wrench form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="prix_HT">Prix d'achat HT</label>
                                <input class="form-control" placeholder="" type="number" step="0.01" min="0" value="0" id="prix_HT" name="prix_HT">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label">Prix d'achat TTC</label>
                                <input class="form-control" placeholder="" type="number" step="0.01" min="0" value="0" id="prix_TTC" name="prix_TTC">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="prix_produit_HT">Prix de vente HT</label>
                                <input class="form-control" placeholder="" type="number" step="0.01" min="0" value="0" id="prix_produit_HT" name="prix_produit_HT">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                <label class="control-label" for="prix_produit_TTC">Prix de vente TTC</label>
                                <input class="form-control" placeholder="" type="number" step="0.01" min="0" value="0" id="prix_produit_TTC" name="prix_produit_TTC">
                                <span class="fa fa-money form-control-feedback" aria-hidden="true"></span> </div>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="form-group">
                                    <label class="control-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </fieldset>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success" name="sendProduit" disabled>Valider</button>
                                &nbsp;
                                <a href="{{action('ProduitController@index')}}" class="btn btn-info">Retour</a>
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
    $(document).on('keyup','#code_produit',function(){
        myFunction();
    })
    $(document).on('keyup','#nom_produit',function(){
        myFunction();
    })
    $(document).on('click','#tva',function(){
        myFunction();
        calculTTC();
        calculAchatTTC();
    })
    $(document).on('keyup','#prix_TTC',function(){
        myFunction();
        calculAchatHT();
    })
    $(document).on('click','#prix_TTC',function(){
        myFunction();
        calculAchatHT();
    })
    $(document).on('keyup','#prix_HT',function(){
        myFunction();
        calculAchatTTC();
    })
    $(document).on('click','#prix_HT',function(){
        myFunction();
        calculAchatTTC();
    })
    $(document).on('keyup','#prix_produit_TTC',function(){
        myFunction();
        calculHT();
    })
    $(document).on('click','#prix_produit_TTC',function(){
        myFunction();
        calculHT();
    })
    $(document).on('keyup','#prix_produit_HT',function(){
        myFunction();
        calculTTC();
    })
    $(document).on('click','#prix_produit_HT',function(){
        myFunction();
        calculTTC();
    })
    $(document).on('click','#nom_categorie',function(){
        myFunction();
    })
    function calculAchatHT() {
        var tva = $('#tva').val();
        var prix_HT = $('#prix_HT');
        var prix_TTC = $('#prix_TTC');
        var ttc = parseFloat(prix_TTC.val());
        prix_HT.val((ttc / (1 + tva/100)).toFixed(2));
    }
    function calculAchatTTC() {
        var tva = $('#tva').val();
        var prix_HT = $('#prix_HT');
        var prix_TTC = $('#prix_TTC');
        var ht = parseFloat(prix_HT.val());
        prix_TTC.val((ht + (ht * tva/100)).toFixed(2));
    }
    function calculHT() {
        var tva = $('#tva').val();
        var prix_produit_HT = $('#prix_produit_HT');
        var prix_produit_TTC = $('#prix_produit_TTC');
        var ttc = parseFloat(prix_produit_TTC.val());
        prix_produit_HT.val((ttc / (1 + tva/100)).toFixed(2));
    }
    function calculTTC() {
        var tva = $('#tva').val();
        var prix_produit_HT = $('#prix_produit_HT');
        var prix_produit_TTC = $('#prix_produit_TTC');
        var ht = parseFloat(prix_produit_HT.val());
        prix_produit_TTC.val((ht + (ht * tva/100)).toFixed(2));
    }
    function myFunction() {
        var code_produit = $('#code_produit').val();
        var nom_produit = $('#nom_produit').val();
        var tva = $('#tva').val();
        var prix_produit_HT = $('#prix_produit_HT').val();
        var prix_produit_TTC = $('#prix_produit_TTC').val();
        var nom_categorie = $('#nom_categorie').val();
        // -----    -----       ---- //
        var btn = $('button[name=sendProduit]');
        if(
            (!code_produit && code_produit=='') || 
            (!nom_produit && nom_produit=='') || 
            (!tva && tva=='') || 
            (!prix_produit_TTC && prix_produit_TTC===0) || 
            (!prix_produit_HT && prix_produit_HT===0) || 
            (!nom_categorie && nom_categorie=='')
        ) {
            btn.prop('disabled',true);
        }
        else{
            btn.prop('disabled',false);
        }
    }
</script>
{{-- ################## --}}
@endsection