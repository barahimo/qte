@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Ajout d'un nouvel achat</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Achats</li>
  </ol>
</div>
{{-- ################## --}}
<!-- ##################################################################### -->
{{-- ################## --}}
{{ Html::style(asset('css/loadingstyle.css')) }}
{{-- ################## --}}
<div style="display:none;" id="loading" class="text-center">
  <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width:200px">
</div>
{{-- ################## --}}
<div class="container">
  <br>
  <!-- Begin Achat_Fournisseur  -->
  <div class="card text-left">
    <div class="card-body">
      {{-- <h4 class="card-title">Ajout d'un nouvel achat :</h4> --}}
      <div class="card-text">
          <div class="form-row">
            <div class="col-4">
              <label for="date">Date :</label>
              <input type="date" 
              class="form-control" 
              name="date" 
              id="date" 
              value={{$date}}
              placeholder="date">
            </div>
            <div class="col-4"> 
              <label for="fournisseur">Fournisseur :</label>      
              <select class="form-control" name="fournisseur" id="fournisseur">
                <option value="0">- Les fournisseurs -</option>
                @foreach($fournisseurs as $fournisseur)
                <option value="{{$fournisseur->id }}">{{ $fournisseur->nom_fournisseur}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-4"> 
              <label for="facture">Facture :</label>      
              <input type="text" class="form-control" name="facture" id="facture" placeholder="code facture">
            </div>
          </div>
      </div>
    </div>
  </div>
  <!-- End Achat_Fournisseur  -->
  <br>
  <!-- Begin Category_Product  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Choisir un produit :</h5>
      <div class="card-text">
        <div class="form-row">
          <div class="col-6">
            <select class="form-control" id="category">
              <option value="0" disabled="true" selected="true">-Catégories-</option>
              @foreach($categories as $cat)
                <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <select class="form-control" id="product">
              <option value="0" disabled="true" selected="true">-Produits-</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Category_Product  -->
  <br>
  <!-- Begin Infos_product  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Les informations de produit :</h5>
      <input type="hidden" name="prod_id" id="prod_id" value="" disabled>
      <input type="hidden" name="stock_qte" id="stock_qte" value="" disabled>
      <div class="card-text">
        <div class="form-row">
          <div class="col-3">
            <label for="nom">Libelle :</label>
            <input type="text" class="form-control" name="libelle" id="libelle" value="libelle" disabled>
          </div>
          <div class="col-3">
            <label for="prix">Prix :</label>
            <input type="number" class="form-control" name="prix" id="prix" value="0.00" min="0" step="0.01">
          </div>
          <div class="col-3">
            <label for="qte">Qté : <span class="badge badge-info" id="badge_qte"></span></label>
            <!-- <input type="number" class="form-control" name="qte" id="qte" value="1" min="1" max="1"> -->
            <input type="number" class="form-control" name="qte" id="qte" value="1" min="1">
          </div>
          <div class="col-3">
            <label for="total">Total :</label>
            <input type="text" class="form-control" name="total" id="total" value="0.00" disabled>
          </div>
        </div>
        <br>
        <button class='btn btn-success' id="addLigne"><i class="fas fa-plus-circle"></i>&nbsp;Ajouter&nbsp;<i class="fas fa-arrow-down"></i></button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class='btn btn-warning text-white' id="updateLigne"><i class="fas fa-retweet"></i>&nbsp;Modifier&nbsp;<i class="fas fa-arrow-down"></i></button>
      </div>
    </div>
  </div>
  <!-- End Infos_product  -->
  <br>
  <!-- Begin LigneDemande  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Les Lignes des achats :</h5>
      <div class="card-text">
        <div class="table-responsive">
          <table class="table" id="lignes">
            <thead class="bg-primary text-white">
              <tr>
                <th style="display : none;">#</th>
                <th>Libelle</th>
                <th>Prix</th>
                <th>Qté</th>
                <th>Total</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              <tr>
                <th style="display: none;"></th>
                <th></th>
                <th></th>
                <th>Total à payer</th>
                <th id="somme">0.00</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- End LigneDemande  -->
  <br>
  <!-- Begin Reglement  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Règlement :</h5>
      <input type="hidden" name="prod_id" id="prod_id">
      <div class="card-text">
        <div class="form-row">
          <div class="col-3">
            <label for="mode">Mode de règlement :</label>
            <select class="form-control" id="mode" name='mode' placeholder="mode reglement">
              <option value="">- Mode -</option>
              <option value="Espèce">Espèce</option>
              <option value="Chèque">Chèque</option>
              <option value="Carte banquaire">Carte banquaire</option>
              <option value="Virement bancaire">Virement bancaire</option>
              <option value="Prélèvement">Prélèvement</option>
              <option value="Effet">Effet</option>
              <option value="Autre">Autre</option>
            </select>
          </div>
          <div class="col-3">
            <label for="nom">Avance :</label>
            <input type="number" id="avance" name="avance" min="0" step="0.01" class="form-control" placeholder="0.00" value="0">
          </div>
          <div class="col-3">
            <label for="reste">Reste :</label>
            <input type="number" id="reste" name="reste" class="form-control" placeholder="reste" min="0" value="0.00" disabled>

          </div>
          <div class="col-3">
            <label for="status">Status :</label>
            <input type="text" name="status" id="status"  class="form-control" placeholder="status" disabled>
          </div>
        </div>
      </div>
    </div>
  </div>  
  <!-- End Reglement  -->
  <br>
  <div class="text-right">
    <button class="btn btn-secondary" id="valider">Valider l'achat</button>
  </div>
  <br>
</div>

<!-- ---------  BEGIN SCRIPT --------- -->
<script type="text/javascript">
  $(document).ready(function(){
    // -----------Change Category--------------//
    $(document).on('change','#category',function(){
      var cat_id=$(this).val();
      var product=$('#product');
      $.ajax({
        type:'get',
        url:"{!!Route('demande.productsCategoryDemande')!!}",
        data:{'id':cat_id},
        success:function(data){
          var options = '<option value="0" disabled="true" selected="true">-Produits-</option>';
          if(data.length>0){
            for(var i=0;i<data.length;i++){
              options+=`<option value="${data[i].id}">${data[i].code_produit} | ${data[i].nom_produit.substring(0,15)}... | ${parseFloat(data[i].prix_TTC).toFixed(2)} | ${parseFloat(data[i].quantite)}</option>`;
            }  
          }
          product.html("");
          product.append(options);
        },
        error:function(){
        }
      });
    });
    // -----------End Change Category--------------//
    // -----------Change Product--------------//
    $(document).on('change','#product',function(){
      var id=$(this).val();
      var prod_id=$('#prod_id');
      var libelle=$('#libelle');
      var qte=$('#qte');
      var prix=$('#prix');
      var total=$('#total');
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');
      $.ajax({
        type:'get',
        url:"{!!Route('demande.infosProductsDemande')!!}",
        data:{'id':id},
        success:function(data){
          if(Object.keys(data).length>0){
            stock_qte.val(data.quantite);
            prod_id.val(data.id);

            libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...');
            // (data.nom_produit) ? libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...') : libelle.val('');
            // libelle.val(data.code_produit+' | '+data.nom_produit);
            prix.val(parseFloat(data.prix_TTC).toFixed(2));                
            total.val(parseFloat(data.prix_TTC).toFixed(2));   
            qte.val("1");
            // qte.attr("max",parseFloat(data.quantite));
            badge_qte.html(parseFloat(data.quantite) + parseFloat(quantite_stock(prod_id.val())));
          }
        },
        error:function(){
        }
      });
    });
    // -----------End Change Product--------------//
    // -----------Change Qte--------------//
    // $(document).on('change','#qte',function(){
    //   var qte=$(this).val();
    //   var prix=$('#prix').val();
    //   var total=$('#total');
    //   var NQte = parseFloat(qte);
    //   var NPrix = parseFloat(prix);
    //   var NTotal = NQte * NPrix;
    //   total.val(NTotal.toFixed(2)) ;
    // });
    $(document).on('keyup','#qte',function(){
      var qte=$(this).val();
      var prix=$('#prix').val();
      var total=$('#total');
      var NQte = parseFloat(qte);
      var NPrix = parseFloat(prix);
      var NTotal = NQte * NPrix;
      total.val(NTotal.toFixed(2)) ;
    });
    $(document).on('click','#qte',function(){
      var qte=$(this).val();
      var prix=$('#prix').val();
      var total=$('#total');
      var NQte = parseFloat(qte);
      var NPrix = parseFloat(prix);
      var NTotal = NQte * NPrix;
      total.val(NTotal.toFixed(2)) ;
    });
    // -----------End Change Qte--------------//
    // -----------Begin AddLigne--------------//
    $(document).on('click','#addLigne',function(){
      var prod_id=$('#prod_id');
      var libelle=$('#libelle');
      var prix=$('#prix');
      var qte=$('#qte');
      var nqte = parseFloat(qte.val());
      var nmin = parseFloat(qte.attr('min'));
      var nmax = parseFloat(qte.attr('max'));
      var table=$('#lignes');
      var total=$('#total');
      var somme=$('#somme');
      
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');

      vnstock_qte = parseFloat(stock_qte.val());

      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin)){
        if(libelle.val() == "" || prix.val() == "")
        message('warning','','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
        message('warning','','Les champs sont invalides !');
        if(nqte < nmin)
        message('warning','Quantité','La quantité est invalide !');
        return;
      }
      if(check(prod_id.val())){
        var q = parseFloat(quantite_stock(prod_id.val())) + parseFloat(qte.val());
        if(q < nmin){
          message('warning','Quantité','La quantité est invalide !');
          return;
        }
        changeQte(qte.val(),prod_id.val());
      }
      else{
        var ligne=`<tr>
                    <td style="display : none;">${prod_id.val()}</td>
                    <td>${libelle.val()}</td>
                    <td>${parseFloat(prix.val()).toFixed(2)}</td>
                    <td>${qte.val()}</td>
                    <td>${parseFloat(total.val()).toFixed(2)}</td>
                    <td style="display : none;">${vnstock_qte}</td>
                    <td>
                      <button class="btn btn-outline-success btn-sm" onclick="edit(${prod_id.val()})"><i class="fas fa-edit"></i></button>
                      &nbsp;&nbsp;&nbsp;
                      <button class="btn btn-outline-danger btn-sm" onclick="remove(${prod_id.val()})"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>`;
        table.find('tbody').append(ligne);
      }
        qte.val("1");
        total.val(prix.val());
        somme.html(calculSomme());
        calculReste();
        // badge_qte.html(parseFloat(nmax + parseFloat(quantite_stock(prod_id.val()))));
        badge_qte.html(parseFloat(stock_qte.val()) + parseFloat(quantite_stock(prod_id.val())));
    });
    // -----------End AddLigne--------------//
    // -----------Begin UpdateLigne--------------//
    $(document).on('click','#updateLigne',function(){
      var prod_id=$('#prod_id');
      var libelle=$('#libelle');
      var prix=$('#prix');
      var qte=$('#qte');
      var nqte = parseFloat(qte.val());
      var nmin = parseFloat(qte.attr('min'));
      var nmax = parseFloat(qte.attr('max'));
      var total=$('#total');
      var table=$('#lignes');
      var somme=$('#somme');
      var badge_qte=$('#badge_qte');
      var stock_qte=$('#stock_qte');
      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin)){
        if(libelle.val() == "" || prix.val() == "")
          message('warning','','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
          message('warning','','Les champs sont invalides !');
        if(nqte < nmin)
          message('warning','Quantité','La quantité est invalide !');
        return;
      }
      if(check(prod_id.val())){
        var index = checkIndex(prod_id.val());
        if(index != -1){
          var list = table.find('tbody').find('tr'); 
          list.eq(index).find('td').eq(0).html(prod_id.val());
          list.eq(index).find('td').eq(1).html(libelle.val());
          list.eq(index).find('td').eq(2).html(parseFloat(prix.val()).toFixed(2));
          list.eq(index).find('td').eq(3).html(qte.val());
          list.eq(index).find('td').eq(4).html(parseFloat(total.val()).toFixed(2));
          badge_qte.html(parseFloat(stock_qte.val()) + parseFloat(nqte));
        }
      }
      qte.val("1");
      total.val(prix.val());
      somme.html(calculSomme());
      calculReste();
      // badge_qte.html(parseFloat(nmax + nqte));
    });
    // -----------End UpdateLigne--------------//
    // -----------keyup Prix--------------//
    $(document).on('keyup','#prix',function(){
      var prix=$(this);
      var qte=$('#qte');
      var total=$('#total');
      NTotal = parseFloat(prix.val())*parseFloat(qte.val());
      total.val(NTotal.toFixed(2));
    });
    $(document).on('click','#prix',function(){
      var prix=$(this);
      var qte=$('#qte');
      var total=$('#total');
      NTotal = parseFloat(prix.val())*parseFloat(qte.val());
      total.val(NTotal.toFixed(2));
    });
    // -----------End keyup Prix--------------//
    // -----------keyup Avance--------------//
    $(document).on('keyup','#avance',function(){
      var avance=$(this);
      var NAvance = parseFloat(avance.val());
      if(NAvance > calculSomme())
        avance.val(calculSomme());
      calculReste();
    });
    $(document).on('click','#avance',function(){
      var avance=$(this);
      var NAvance = parseFloat(avance.val());
      if(NAvance > calculSomme())
        avance.val(calculSomme());
      calculReste();
    });
    // -----------End keyup Avance--------------//
    // -----------Begin valider--------------//
    $(document).on('click','#valider',function(e){
      $('#valider').prop('disabled',true);
      $('#loading').prop('style','display : block');
      // e.preventDefault(); //Pour ne peut refresh la page en cas de bouton submit 
      var _token=$('input[name=_token]'); //Envoi des information via method POST
      // ***** BEGIN variables demande ******** //
      var date=$('#date');
      var fournisseur=$('#fournisseur');
      var facture=$('#facture');
      // ***** END variables demande ******** //

      // ***** BEGIN variables lignes ******** //
      var table=$('#lignes');
      var list = table.find('tbody').find('tr');
      var array = [];
      for (let i = 0; i < list.length; i++) {
        var prod_id = list.eq(i).find('td').eq(0).html();
        var libelle = list.eq(i).find('td').eq(1).html();
        var prix = list.eq(i).find('td').eq(2).html();
        var qte = list.eq(i).find('td').eq(3).html();
        var total = list.eq(i).find('td').eq(4).html();
        var obj = {
              "prod_id":parseInt(prod_id),
              // "libelle":libelle,
              "prix":parseFloat(prix),
              "qte":parseFloat(qte),
              "total":parseFloat(total)
            };

        array = [...array,obj];
      }
      // ***** END variables lignes ******** //
      // ***** BEGIN variables reglements ******** //
      var mode =$('#mode');
      var avance= $('#avance');
      var reste =$('#reste');
      // var status = $('#status');
      var status = "R";
      if(parseFloat(reste.val()) > 0) 
        status = "NR" ;
      // ***** END variables reglements ******** //
      $.ajax({
        type:'post',
        url:"{{Route('demande.store')}}",
        data:{
          _token : _token.val(),
          date : date.val(),
          fournisseur : parseInt(fournisseur.val()),
          facture : facture.val(),
          lignes : array,
          mode:mode.val(),
          avance:parseFloat(avance.val()),
          reste:parseFloat(reste.val()),
          status:status,
          total:calculSomme(),
        },
        success: function(data){
          // Swal.fire(data.message);
          message(data.status,'',data.message);
          if(data.status == "success"){
            setTimeout(() => {
              window.location.assign("{{route('demande.index')}}")
            }, 2000);
          }
          else{
            $('#valider').prop('disabled',false);
            $('#loading').prop('style','display : none');
          }
        } ,
        error:function(err){
          // Swal.fire(err.message);
          message('error','',err.message);
          if(err.status === 500){
            // Swal.fire(err.statusText);
            message('error','',err.statusText);
          }
          else{
            // Swal.fire("Erreur d'enregistrement de la demande !");
            message('error','',"Erreur d'enregistrement de la demande !");
          }
        },
      });
    });
    // -----------End valider--------------//
  });
  // -----------My function--------------//
  function remove(id){
    var i = checkIndex(id);
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var td = list.eq(i).find('td');
    // ################################# //
    var prod_id=$('#prod_id');
    var libelle=$('#libelle');
    var prix=$('#prix');
    var qte=$('#qte');
    var total=$('#total');
    var badge_qte=$('#badge_qte');
    // ################################# //
    prod_id.val(td.eq(0).html());
    libelle.val(td.eq(1).html());
    prix.val(td.eq(2).html());
    qte.val(1);
    total.val(td.eq(2).html());
    // ################################# //
    var vStock_qte = td.eq(5).html();
    badge_qte.html(vStock_qte);
    // ################################# //
    list.eq(i).remove();
    var somme=$('#somme');
    somme.html(calculSomme());
    calculReste();
  }
  function edit(id){
    var i = checkIndex(id);
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var td = list.eq(i).find('td');

    var prod_id = $('#prod_id');
    var libelle = $('#libelle');
    var prix = $('#prix');
    var qte = $('#qte');
    var total = $('#total');
    var badge_qte=$('#badge_qte');

    var vProd_id = td.eq(0).html();
    var vLibelle = td.eq(1).html();
    var vPrix = td.eq(2).html();
    var vQte = td.eq(3).html();
    var vTotal = td.eq(4).html();
    var vStock_qte = td.eq(5).html(); 

    prod_id.val(vProd_id);
    libelle.val(vLibelle);
    prix.val(vPrix);
    qte.val(vQte);
    total.val(vTotal);
    
    badge_qte.html(parseFloat(vQte) + parseFloat(vStock_qte));
  }
  function check(id){
    var existe = false;
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    for (let i = 0; i < list.length; i++) {
      var prod_id = list.eq(i).find('td').eq(0).html();
      if(prod_id == id){
        existe = true;
        break;
      }
    }
    return existe;
  }
  
  function checkIndex(id){
    var index = -1;
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    for (let i = 0; i < list.length; i++) {
      var prod_id = list.eq(i).find('td').eq(0).html();
      if(prod_id == id){
        index = i;
        break;
      }
    }
    return index;
  }
  function changeQte(qteNew,id){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var i = checkIndex(id);
    var prod_id = list.eq(i).find('td').eq(0).html();
    var libelle = list.eq(i).find('td').eq(1).html();
    var prix = list.eq(i).find('td').eq(2).html();
    var qte = list.eq(i).find('td').eq(3).html();
    var total = list.eq(i).find('td').eq(4).html();
    var NPrix = parseFloat(prix);
    var NQte = parseFloat(qte) + parseFloat(qteNew);
    var NTotal = NQte * NPrix;
    list.eq(i).find('td').eq(3).html(NQte);
    list.eq(i).find('td').eq(4).html(parseFloat(NTotal).toFixed(2));
  }
  function quantite_stock(id){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var i = checkIndex(id);
    var qte = 0;
    if(i != -1)
    qte = list.eq(i).find('td').eq(3).html();
    // var NQte = parseFloat(qte) + parseFloat(qteNew);
    return parseFloat(qte).toFixed(2);
  }
  function calculSomme(){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var somme = 0.0;
    for (let i = 0; i < list.length; i++) {
      var total = list.eq(i).find('td').eq(4).html();
      var NTotal = parseFloat(total);
      somme+=NTotal;
    }
    return somme.toFixed(2);
  }
  function calculReste(){
    var avance=$("#avance");
    var reste=$('#reste');
    var status=$("#status");
    var NReste = 0;
    if(avance.val()){
      NReste = calculSomme()-parseFloat(avance.val());
      (NReste > 0) ? status.val("non réglée"): status.val("réglée");    
    }
    else{
      status.val("");
    }
    reste.val(NReste.toFixed(2));
  }
  function message(icon,title,text){
    Swal.fire({
      title: title,
      text: text,
      icon: icon,
      showCancelButton: false,
      showConfirmButton : true,
      confirmButtonColor: '#007BFF',
    });
  }
</script>
<!-- ##################################################################### -->
@endsection