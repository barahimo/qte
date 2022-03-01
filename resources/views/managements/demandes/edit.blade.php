@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Modification de l'achat</h1>
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
  <!-- Begin emande_Fournisseur  -->
  <div class="card text-left">
    <div class="card-body">
      {{-- <h4 class="card-title">Modification de l'achat :</h4> --}}
      <div class="card-text">
        <div class="form-row">
          <div class="col-4">
            <label for="date">Date</label>
            <input type="date" 
            class="form-control" 
            name="date" 
            id="date" 
            value="{{old('date',$demande->date)}}"
            placeholder="date">
          </div>
          <div class="col-4">     
            <label for="fournisseur">Fournisseur</label>
            <select class="form-control" name="fournisseur" id="fournisseur">
            @foreach($fournisseurs as $fournisseur)
            <option value="{{$fournisseur->id}}" @if ($fournisseur->id == old('fournisseur_id',$demande->fournisseur_id)) selected="selected" @endif>{{ $fournisseur->nom_fournisseur}}</option>
            @endforeach
            </select>
          </div>
          <div class="col-4"> 
            <label for="facture">Facture :</label>      
            <input type="text" class="form-control" name="facture" id="facture" value="{{old('facture',$demande->facture)}}" placeholder="code facture">
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Demande_Fournisseur  -->
  <br>
  <!-- Begin Category_Product  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Choisir un produit :</h5>
      <div class="card-text">
        <div class="form-row">
          <div class="col-6">
            <select class="form-control" id="category">
              <option value="0" disabled="true" selected="true">-Select-</option>
              @foreach($categories as $cat)
                <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
              @endforeach
            </select>
          </div>
          <div class="col-6">
            <select class="form-control" id="product">
              <option value="0" disabled="true" selected="true">-Product-</option>
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
      <input type="hidden" name="ligne_id" id="ligne_id" value="" disabled>
      <input type="hidden" name="prod_id" id="prod_id" value="" disabled>
      <input type="hidden" name="ligne_qte" id="ligne_qte" value="" disabled>
      <input type="hidden" name="stock_qte" id="stock_qte" value="" disabled>
      <div class="card-text">
        <div class="form-row">
          <div class="col-3">
            <label for="nom">Libelle :</label>
            <input type="text" class="form-control" name="libelle" id="libelle" placeholder="libelle" disabled>
          </div>
          <div class="col-3">
            <label for="prix">Prix :</label>
            <input type="number" class="form-control" name="prix" id="prix" value="0.00" min="0" step="0.01">
          </div>
          <div class="col-3">
            <label for="qte">Qté : <span class="badge badge-info" id="badge_qte"></span></label>
            <input type="number" class="form-control" name="qte" id="qte" value="1" min="1">
          </div>
          <div class="col-3">
            <label for="total">Total :</label>
            <input type="text" class="form-control" name="total" id="total" value="0.00" disabled>
          </div>
        </div>
        <br>
        <button class='btn btn-success' id="addLigne"><i class="fas fa-plus-circle"></i>&nbsp;Ligne&nbsp;<i class="fas fa-arrow-down"></i></button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class='btn btn-warning text-white' id="updateLigne"><i class="fas fa-retweet"></i>&nbsp;à jour&nbsp;<i class="fas fa-arrow-down"></i></button>
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
                {{-- <th>##</th>
                <th>##</th>
                <th>##</th> --}}
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
  <!-- Begin Payement  -->
  <div class="card text-left">
      <div class="card-body">
        <h5 class="card-title">Les paiements :</h5>
        <div class="card-text">
          <div class="form-row">
            <div class="col-3">
                <label for="mode">Date de paiement :</label>
            </div>
            <div class="col-3">
              <label for="mode">Mode de paiement :</label>
            </div>
            <div class="col-2">
              <label for="nom">Montant payer :</label>
            </div>
            <div class="col-2">
              <label for="reste">Reste à payer :</label>
            </div>
            <div class="col-2">
              <label for="status">Status :</label>
            </div>
          </div>
          <div id="payements">
          @foreach($demande->payements as $payement)
            @php
            ($payement->avance>0) ? $style="" : $style = "display: none;"; 
            @endphp
            <div style="@php echo $style; @endphp">
              <div class="form-row">
                <input type="hidden" value="{{$payement->id}}">
                <input type="hidden" value="{{$payement->reste}}">
                <div class="col-3">
                  <input type="text" class="form-control" name="reg_date" placeholder="reg_date" value="{{$payement->date}}" disabled>
                </div>
                <div class="col-3">
                  <input type="text" class="form-control" name="mode" placeholder="mode" value="{{$payement->mode_payement}}" disabled>
                </div>
                <div class="col-2">
                  {{-- <input type="text" class="form-control" name="avance" placeholder="avance" value="{{number_format($payement->avance,2)}}" disabled> --}}
                  <input type="text" class="form-control" name="avance" placeholder="avance" value="{{$payement->avance}}" disabled>
                </div>
                <div class="col-2">
                  {{-- <input type="text" class="form-control" name="reste"  placeholder="reste" value="{{number_format($payement->reste,2)}}" disabled> --}}
                  <input type="text" class="form-control" name="reste"  placeholder="reste" value="{{$payement->reste}}" disabled>
                </div>
                <div class="col-2">
                  <input type="text" class="form-control" name="status"  placeholder="status" value="{{$payement->status}}" disabled>
                </div>
              </div>
              <br>
            </div>
          @endforeach
          </div>
        </div>
      </div>
  </div>  
  <!-- End Payement  -->
  <br>
  <div class="text-right">
    <button class="btn btn-secondary" id="valider">Valider les modifications</button>
  </div>
  <br>
</div>

<!-- ---------  BEGIN SCRIPT --------- -->
<script type="text/javascript">
  $(document).ready(function(){
    getLignes();
    // -----------Change Category--------------//
    $(document).on('change','#category',function(){
      var cat_id=$(this).val();
      var product=$('#product');
      $.ajax({
        type:'get',
        url:'{!!Route('demande.productsCategoryDemande')!!}',
        data:{'id':cat_id},
        success:function(data){
          var options = '<option value="0" disabled="true" selected="true">-Product-</option>';
          if(data.length>0){
            for(var i=0;i<data.length;i++){
              options+=`<option value="${data[i].id}">${data[i].code_produit} | ${data[i].nom_produit.substring(0, 15)}... | ${parseFloat(data[i].prix_TTC).toFixed(2)} | ${parseFloat(data[i].quantite)}</option>`;
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
      
      var ligne_id=$('#ligne_id');
      var ligne_qte=$('#ligne_qte');
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');
      var nqte = parseFloat(quantite_stock(id));
      var p = 0;
      var r = 0;
      var stock = 0;
      var stockFinal = 0;
      $.ajax({
        type:'get',
        url:'{!!Route('demande.infosProductsDemande')!!}',
        data:{'id':id},
        success:function(data){
          if(Object.keys(data).length>0) {     
            ligne_id.val(checkLigne(data.id).ligne_id);
            ligne_qte.val(checkLigne(data.id).ligne_qte);
            stock_qte.val(data.quantite);

            prod_id.val(data.id);
            libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...');   
            // (data.nom_produit) ? libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...') : libelle.val('');   
            prix.val(parseFloat(data.prix_TTC).toFixed(2));           
            total.val(parseFloat(data.prix_TTC).toFixed(2)); 
            qte.val("1");

            stock = parseFloat(data.quantite);
            p = parseFloat(ligne_qte.val());
            r = p - nqte;
            stockFinal = parseFloat(stock) - parseFloat(r);
            console.log(`p : ${p} | qte : ${nqte} | r : ${r} | stock : ${stock} | stockFinal : ${stockFinal}`);
            if(stockFinal<0){
              message('warning','Stock','Merci de vérifier la quantité en stock !');
              return;
            }
            badge_qte.html(parseFloat(stockFinal));
          }
        },
        error:function(){
        }
      });
    });
    // -----------End Change Product--------------//
    // -----------Change Qte--------------//
    $(document).on('change','#qte',function(){
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
      
      var ligne_id=$('#ligne_id');
      var ligne_qte=$('#ligne_qte');
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');

      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin)){
        if(libelle.val() == "" || prix.val() == "")
        message('warning','','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
        message('warning','','Les champs sont invalides !');
        if(nqte < nmin)
        message('warning','Quantité','La quantité est invalide !');
        return;
      }
      // ####################################### //
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
          <td style="display : none;">${ligne_id.val()}</td>
          <td style="display : none;">${ligne_qte.val()}</td>
          <td style="display : none;">${stock_qte.val()}</td>
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
      // calculReste();
      calculPayement();
      // ####################################### //
      var id = prod_id.val();
      var stock = 0;
      var stockFinal = 0;
      var r = 0;
      var new_qte = 0;
      // ####################################### //
      new_qte = parseFloat(quantite_stock(id));
      p = parseFloat(ligne_qte.val());
      r = p - new_qte;
      // r = p - nqte;
      stock = parseFloat(stock_qte.val());
      stockFinal = parseFloat(stock) - parseFloat(r);
      console.log(`p : ${p} | qte : ${new_qte} | r : ${r} | stock : ${stock} | stockFinal : ${stockFinal}`);
      if(stockFinal<0){
        message('warning','Stock','Merci de vérifier la quantité en stock !');
        return;
      }
      badge_qte.html(parseFloat(stockFinal));
      // ####################################### //
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

      var ligne_id=$('#ligne_id');
      var ligne_qte=$('#ligne_qte');
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');

      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin)){
        if(libelle.val() == "" || prix.val() == "")
        message('warning','','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
        message('warning','','Les champs sont invalides !');
        if(nqte < nmin)
        message('warning','Quantité','La quantité est invalide !');
        return;
      }
      // ####################################### //
      p = parseFloat(ligne_qte.val());
      r = p - nqte;
      stock = parseFloat(stock_qte.val());
      stockFinal = parseFloat(stock) - parseFloat(r);
      console.log(`p : ${p} | qte : ${nqte} | r : ${r} | stock : ${stock} | stockFinal : ${stockFinal}`);
      if(stockFinal<0){
        message('warning','Stock','Merci de vérifier la quantité en stock !');
        return;
      }
      // ####################################### //
      if(check(prod_id.val())){
        var index = checkIndex(prod_id.val());
        if(index != -1){
          var list = table.find('tbody').find('tr'); 
          list.eq(index).find('td').eq(0).html(prod_id.val());
          list.eq(index).find('td').eq(1).html(libelle.val());
          list.eq(index).find('td').eq(2).html(parseFloat(prix.val()).toFixed(2));
          list.eq(index).find('td').eq(3).html(qte.val());
          list.eq(index).find('td').eq(4).html(parseFloat(total.val()).toFixed(2));
          badge_qte.html(parseFloat(stockFinal));
        }
      }
      qte.val("1");
      total.val(prix.val());
      somme.html(calculSomme());
      // calculReste();
      calculPayement();
      // ####################################### //
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
      // calculReste();
    });
    $(document).on('click','#avance',function(){
      var avance=$(this);
      var NAvance = parseFloat(avance.val());
      if(NAvance > calculSomme())
        avance.val(calculSomme());
      // calculReste();
    });
    // -----------End keyup Avance--------------//
    // -----------Begin valider--------------//
    $(document).on('click','#valider',function(e){
      $('#valider').prop('disabled',true);
      $('#loading').prop('style','display : block');
      // e.preventDefault(); //Pour ne peut refresh la page en cas de bouton submit 
      // var cmd_id = <?php echo $demande->id;?>;
      var cmd_id = '{{$demande->id}}';
      var _token=$('input[name=_token]'); //Envoi des information via method POST
      // ***** BEGIN variables demande ******** //
      var date=$('#date');
      var fournisseur=$('#fournisseur');
      var facture=$('#facture');
      // ***** END variables demande ******** //

      // ***** BEGIN variables lignes ******** //
      var table=$('#lignes');
      var list = table.find('tbody').find('tr');
      var array1 = [];
      for (let i = 0; i < list.length; i++) {
        var prod_id = list.eq(i).find('td').eq(0).html();
        var libelle = list.eq(i).find('td').eq(1).html();
        var prix = list.eq(i).find('td').eq(2).html();
        var qte = list.eq(i).find('td').eq(3).html();
        var total = list.eq(i).find('td').eq(4).html();
        var obj = {
              "prod_id":parseInt(prod_id),
              // "libelle":libelle,
              "prix": parseFloat(prix),
              "qte":parseFloat(qte),
              "total":parseFloat(total)
            };

        array1 = [...array1,obj];
      }
      // ***** END variables lignes ******** //
      // ***** BEGIN variables payements ******** //
      // var mode =$('#mode');
      // var avance= $('#avance');
      // var reste =$('#reste');
      // var status =$('#status');
      // ***** BEGIN variables lignes ******** //
      var payements=$('#payements');
      var list = payements.find('.form-row');
      var array2 = [];
      for (let i = 0; i < list.length; i++) {
        var hidden = list.eq(i).find('input:hidden');
        var n_reg_id_hidden = parseFloat(hidden.eq(0).val());
        var row = list.eq(i).find('div');
        var reste = row.eq(3).find('input');
        nreste = parseFloat(reste.val());
        var status = row.eq(4).find('input'); 
        (nreste>0) ? txt = 'NR' : (nreste==0) ? txt = 'R' : txt = 'AV';
        var obj = {
              "reg_id":parseInt(n_reg_id_hidden),
              "reste":nreste,
              "status":txt,
            };

        array2 = [...array2,obj];
      }
      // ***** END variables payements ******** //
      var url_update = "{{route('demande.update',['demande'=>":id"])}}".replace(':id', cmd_id);
      $.ajax({
        type:'post',
        url:url_update,
        data:{
          _token : _token.val(),
          id : cmd_id,
          date : date.val(),
          fournisseur : parseInt(fournisseur.val()),
          facture : facture.val(),
          lignes : array1,
          lists : lists,
          // mode:mode.val(),
          // avance:parseFloat(avance.val()),
          // reste:parseFloat(reste.val()),
          // status:status.val(),
          payements : array2,
          count_payements : array2.length,
          cmd_avance : calculAvances(),
          cmd_total : calculSomme(),
          cmd_reste : calculSomme()-calculAvances(),
        },
        success: function(data){
          // Swal.fire(data.message);
          message(data.status,'',data.message);
          if(data.status == "success"){
            setTimeout(() => {
              window.location.assign('{{route('demande.index')}}')
            }, 2000);
          }
          else{
            $('#valider').prop('disabled',false);
            $('#loading').prop('style','display : none');
          }
        } ,
        error:function(err){
          if(err.status === 500){
            // Swal.fire(err.statusText);
            message('error','',err.statusText);
          }
          else{
            // Swal.fire("Erreur d'enregistrement de l'achat !");
            message('error','',"Erreur d'enregistrement de l'achat !");
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
    var vLigne_id = td.eq(5).html();
    var vLigne_qte = td.eq(6).html();
    var vStock_qte = td.eq(7).html();
    // ################################# //
    p = parseFloat(vLigne_qte);
    nqte = 0;
    r = p - nqte;
    stock = parseFloat(vStock_qte);
    stockFinal = parseFloat(stock) - parseFloat(r);
    console.log(`p : ${p} | qte : ${nqte} | r : ${r} | stock : ${stock} | stockFinal : ${stockFinal}`);
    if(stockFinal<0){
      message('warning','Stock','Merci de vérifier la quantité en stock !');
      return;
    }
    // ################################# //
    prod_id.val(td.eq(0).html());
    libelle.val(td.eq(1).html());
    prix.val(td.eq(2).html());
    qte.val(1);
    total.val(td.eq(2).html());
    badge_qte.html(vStock_qte);
    // ################################# //
    list.eq(i).remove();
    var somme=$('#somme');
    somme.html(calculSomme());
    // calculReste();
    calculPayement();
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
    // ################################### //
    var ligne_id = $('#ligne_id');
    var ligne_qte = $('#ligne_qte');
    var stock_qte = $('#stock_qte');
    var badge_qte=$('#badge_qte');
    // ################################### //
    var vProd_id = td.eq(0).html();
    var vLibelle = td.eq(1).html();
    var vPrix = td.eq(2).html();
    var vQte = td.eq(3).html();
    var vTotal = td.eq(4).html();
    var vLigne_id = td.eq(5).html();
    var vLigne_qte = td.eq(6).html();
    var vStock_qte = td.eq(7).html();

    ligne_id.val(vLigne_id);
    prod_id.val(vProd_id);
    ligne_qte.val(vLigne_qte)
    stock_qte.val(vStock_qte)
    libelle.val(vLibelle);
    prix.val(vPrix);
    qte.val(vQte);
    total.val(vTotal);
  
    var nqte = parseFloat(quantite_stock(id));
    
    var p = 0;
    var r = 0;
    var stock = 0;
    var stockFinal = 0;

    p = parseFloat(vLigne_qte);
    r = p - nqte;
    stock = parseFloat(vStock_qte);
    stockFinal = parseFloat(stock) - parseFloat(r);
    console.log(`p : ${p} | qte : ${nqte} | r : ${r} | stock : ${stock} | stockFinal : ${stockFinal}`);
    if(stockFinal<0){
      message('warning','Stock','Merci de vérifier la quantité en stock !');
      return;
    }
    // ##################################### //
    badge_qte.html(parseFloat(stockFinal));
    // badge_qte.html(stock + nqte);
    // ################################### //
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
  function checkLigne(id){
    vLigne_id = 0;
    vLigne_qte = 0;
    var existe = false;
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    for (let i = 0; i < list.length; i++) {
      var prod_id = list.eq(i).find('td').eq(0).html();
      if(prod_id == id){
        vLigne_id = parseFloat(list.eq(i).find('td').eq(5).html());
        vLigne_qte = parseFloat(list.eq(i).find('td').eq(6).html());
        break;
      }
    }
    var obj = {'ligne_id':vLigne_id,'ligne_qte':vLigne_qte};
    return obj;
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
  function calculAvances(){
    var comp = 0;
    var payements=$('#payements');
    var list = payements.find('.form-row');
    for (let i = 0; i < list.length; i++) {
      var row = list.eq(i).find('div');
      var avance = row.eq(2).find('input');
      var navance = parseFloat(avance.val());
      comp += navance;
    }
    return comp.toFixed(2);
  }
  var lists = [];
  function getLignes(){
    // var cmd_id = <?php echo $demande->id;?>;
    var cmd_id = '{{$demande->id}}';
    $.ajax({
        type:'get',
        url:'{!!Route('demande.editDemande')!!}',
        data:{'id' : cmd_id},
        success: function(data){
          var lignedemandes = data.lignedemandes;
          // var payement = data.payement;
          // -----------BEGIN lignes--------------//
          var table = $('#lignes');
          table.find('tbody').html("");
          var lignes = '';
          lignedemandes.forEach(ligne => {
            // var prix = ligne.total_produit/parseFloat(ligne.quantite);
            // <td>${prix.toFixed(2)}</td>
            // <td>${ligne.produit.nom_produit}</td>
            lignes+=`<tr>
                    <td style="display : none;">${ligne.produit_id}</td>
                    <td>${ligne.produit.code_produit} | ${ligne.produit.nom_produit.substring(0,15)}...</td>
                    <td>${parseFloat(ligne.prix).toFixed(2)}</td>
                    <td>${ligne.quantite}</td>
                    <td>${parseFloat(ligne.total_produit).toFixed(2)}</td>
                    <td style="display : none;">${ligne.id}</td>
                    <td style="display : none;">${ligne.quantite}</td>
                    <td style="display : none;">${ligne.produit.quantite}</td>
                    <td>
                      <button class="btn btn-outline-success btn-sm" onclick="edit(${ligne.produit_id})"><i class="fas fa-edit"></i></button>
                      &nbsp;&nbsp;&nbsp;
                      <button class="btn btn-outline-danger btn-sm" onclick="remove(${ligne.produit_id})"><i class="fas fa-trash"></i></button>
                    </td>
                  </tr>`;
          });
          table.find('tbody').append(lignes);
          var somme=$('#somme');
          somme.html(calculSomme());
          // -----------END lignes--------------//
          // -----------BEGIN payement--------------//
          var mode=$("#mode");
          var avance=$("#avance");
          var reste=$('#reste');
          var status=$("#status");
          // mode.val(payement.mode_payement);
          // avance.val(parseFloat(payement.avance).toFixed(2));
          // reste.val(parseFloat(payement.reste).toFixed(2));
          // status.val(payement.payement);
          // -----------END payement--------------//
          calculPayement();
          /////////////////////////////////////
          lists = getDeletedProducts(table);
          /////////////////////////////////////
        } ,
        error:function(err){
            // Swal.fire(err);
            message('error','',err);
        },
      });
  }
  function getDeletedProducts(table){
    var list = table.find('tbody').find('tr');
    var array1 = [];
    for (let i = 0; i < list.length; i++) {
      var prod_id = list.eq(i).find('td').eq(0).html();
      var libelle = list.eq(i).find('td').eq(1).html();
      var prix = list.eq(i).find('td').eq(2).html();
      var qte = list.eq(i).find('td').eq(3).html();
      var total = list.eq(i).find('td').eq(4).html();
      var obj = {
            "prod_id":parseInt(prod_id),
            // "libelle":libelle,
            "prix": parseFloat(prix),
            "qte":parseFloat(qte),
            "total":parseFloat(total)
          };

      array1 = [...array1,obj];
    }
    return array1;
  }
  function calculPayement(){
    // var cmd_total = <?php echo $demande->total; ?>;
    var cmd_total = '{{$demande->total}}';
    var diff = calculSomme() - cmd_total;
    // var diff = 100;
    var payements=$('#payements');
    var list = payements.find('.form-row');
    /*for (let i = 0; i < list.length; i++) {
      var hidden = list.eq(i).find('input:hidden');
      var n_reg_id_hidden = parseFloat(hidden.eq(0).val());
      var n_reste_hidden = parseFloat(hidden.eq(1).val());
      var row = list.eq(i).find('div');
      var reste = row.eq(3).find('input');
      // -----------------------------------
      var avance = row.eq(2).find('input');
      // var navance = parseFloat(avance.val()).toFixed(2);
      var navance = parseFloat(avance.val());
      var n = n_reste_hidden+diff;
      (-n <= navance) ? reste.val(n.toFixed(2)): reste.val(-navance.toFixed(2));
      // -----------------------------------
      // reste.val(n_reste_hidden+diff);
      // var nreste = parseFloat(reste.val()).toFixed(2);
      var nreste = parseFloat(reste.val());
      var status = row.eq(4).find('input');
      (nreste>0) ? status.val('Non reglée'): (nreste==0) ? status.val('Reglée'): status.val('AVOIR');
    }*/
    var compAvance = 0;
    var somme  = calculSomme();
    for (let i = 0; i < list.length; i++) {
      var hidden = list.eq(i).find('input:hidden');
      var n_reg_id_hidden = parseFloat(hidden.eq(0).val());
      var n_reste_hidden = parseFloat(hidden.eq(1).val());
      var row = list.eq(i).find('div');
      var reste = row.eq(3).find('input');
      // -----------------------------------
      var avance = row.eq(2).find('input');
      var navance = parseFloat(avance.val());
      var compAvance = parseFloat(compAvance) + navance;
      // var n = n_reste_hidden+diff;
      // (-n <= navance) ? reste.val(n.toFixed(2)): reste.val(-navance.toFixed(2));
      var valReste = somme - compAvance;
      reste.val(valReste.toFixed(2));
      // -----------------------------------
      var nreste = parseFloat(reste.val());
      var status = row.eq(4).find('input');
      (nreste>0) ? status.val('Non reglée'): (nreste==0) ? status.val('Reglée'): status.val('AVOIR');
    }
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