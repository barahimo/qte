@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Ajout d'une nouvelle facture</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Facture</li>
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
  <!-- Begin Commande_Client  -->
  <div class="card text-left">
    <div class="card-body">
      {{-- <h4 class="card-title">Ajout d'une nouvelle facture :</h4> --}}
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
              <label for="client">Client :</label>      
              <select class="form-control" name="client" id="client">
                <option value="0">- Les clients -</option>
                @foreach($clients as $client)
                <option value="{{$client->id }}">{{ $client->nom_client}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-4"> 
              <label for="facture">Facture :</label>      
              <input type="text" class="form-control" name="facture" id="facture" placeholder="code facture" value="{{$code}}">
            </div>
          </div>
      </div>
    </div>
  </div>
  <!-- End Commande_Client  -->
  <br>
  <!-- Begin Mesure_Client  -->
  <div class="card text-left">
    <div class="card-header">
      <div class="card-title">
        <h5>Mesures</h5>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12">
           <div class="card text-black">
             <div class="card-header bg-primary text-white text-center" style="height: 40px;">
               <div class="card-title">
                 <h6 class="font-weight-bold" onclick="eventLoin()" style="cursor: pointer !important;">Vision de loin <i class="fas fa-arrow-down" id="icon_loin" onclick="eventLoin()"></i></h6>
               </div>
             </div>
             <div class="card-body" id="div_loin" style="display: none;">
               <div class="row">
                   <div class="col">
                     <fieldset style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;">
                       <legend style="font-size: 1em !important; color : #007BFF !important;text-align: left !important;">
                         <span><i class="fas fa-eye fa-1x">&nbsp;Gauche</i></span>
                       </legend>
                       <div class="form-row">
                         <div class="col-md-6">
                           <label for="sphere_gauche_loin">Sphère : </label>
                           <input type="text" class="form-control" name="sphere_gauche_loin" id="sphere_gauche_loin" placeholder="Sphère">
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_gauche_loin">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_gauche_loin" id="cylindre_gauche_loin" placeholder="Cylindre">
                         </div>
                         <div class="col-md-6">
                           <label for="axe_gauche_loin">Axe : </label>
                           <input type="text" class="form-control" name="axe_gauche_loin" id="axe_gauche_loin" placeholder="Axe">
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_gauche_loin">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_gauche_loin" id="lentille_gauche_loin" placeholder="Lentille">
                         </div>
                         <div class="col-md-6">
                           <label for="eip_gauche_loin">Eip : </label>
                           <input type="text" class="form-control" name="eip_gauche_loin" id="eip_gauche_loin" placeholder="Eip">
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_gauche_loin">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_gauche_loin" id="hauteur_gauche_loin" placeholder="Hauteur">
                         </div>
                       </div>
                     </fieldset>
                   </div>
                   <div class="col">
                     <fieldset style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;">
                       <legend style="font-size: 1em !important; color : #007BFF !important;text-align: left !important;">
                           <span><i class="fas fa-eye fa-1x">&nbsp;Droite</i></span>
                       </legend>
                       <div class="form-row">
                         <div class="col-md-6">
                           <label for="sphere_droite_loin">Sphère : </label>
                           <input type="text" class="form-control" name="sphere_droite_loin" id="sphere_droite_loin" placeholder="Sphère">
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_droite_loin">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_droite_loin" id="cylindre_droite_loin" placeholder="Cylindre">
                         </div>
                         <div class="col-md-6">
                           <label for="axe_droite_loin">Axe : </label>
                           <input type="text" class="form-control" name="axe_droite_loin" id="axe_droite_loin" placeholder="Axe">
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_droite_loin">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_droite_loin" id="lentille_droite_loin" placeholder="Lentille">
                         </div>
                         <div class="col-md-6">
                           <label for="eip_droite_loin">Eip : </label>
                           <input type="text" class="form-control" name="eip_droite_loin" id="eip_droite_loin" placeholder="Eip">
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_droite_loin">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_droite_loin" id="hauteur_droite_loin" placeholder="Hauteur">
                         </div>
                       </div>
                     </fieldset>
                   </div>
               </div>
             </div>
           </div>
        </div>
        <div class="col-md-12">
           <div class="card">
             <div class="card-header bg-primary text-white text-center" style="height: 40px;">
               <h6 class="font-weight-bold" onclick="eventPres()" style="cursor: pointer !important;">Vision de près <i class="fas fa-arrow-down" id="icon_pres" onclick="eventPres()"></i></h6>
             </div>
             <div class="card-body" id="div_pres" style="display: none;">
               <div class="row">
                   <div class="col">
                     <fieldset style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;">
                       <legend style="font-size: 1em !important; color : #007BFF !important;text-align: left !important;">
                         <span><i class="fas fa-eye fa-1x">&nbsp;Gauche</i></span>
                       </legend>
                       <div class="form-row">
                         <div class="col-md-6">
                           <label for="sphere_gauche_pres">Sphère : </label>
                           <input type="text" class="form-control" name="sphere_gauche_pres" id="sphere_gauche_pres" placeholder="Sphère">
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_gauche_pres">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_gauche_pres" id="cylindre_gauche_pres" placeholder="Cylindre">
                         </div>
                         <div class="col-md-6">
                           <label for="axe_gauche_pres">Axe : </label>
                           <input type="text" class="form-control" name="axe_gauche_pres" id="axe_gauche_pres" placeholder="Axe">
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_gauche_pres">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_gauche_pres" id="lentille_gauche_pres" placeholder="Lentille">
                         </div>
                         <div class="col-md-6">
                           <label for="dreip_gauche_pres">Eip : </label>
                           <input type="text" class="form-control" name="eip_gauche_pres" id="eip_gauche_pres" placeholder="Eip">
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_gauche_pres">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_gauche_pres" id="hauteur_gauche_pres" placeholder="Hauteur">
                         </div>
                       </div>
                     </fieldset>
                   </div>
                   <div class="col">
                     <fieldset style="border: 1px groove #ddd !important;padding: 0 1.4em 1.4em 1.4em !important;margin: 0 0 1.5em 0 !important;-webkit-box-shadow:  0px 0px 0px 0px #000;box-shadow:  0px 0px 0px 0px #000;">
                       <legend style="font-size: 1em !important; color : #007BFF !important;text-align: left !important;">
                           <span><i class="fas fa-eye fa-1x">&nbsp;Droite</i></span>
                       </legend>
                       <div class="form-row">
                         <div class="col-md-6">
                           <label for="sphere_droite_pres">Sphère : </label>
                           <input type="text" class="form-control" name="sphere_droite_pres" id="sphere_droite_pres" placeholder="Sphère">
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_droite_pres">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_droite_pres" id="cylindre_droite_pres" placeholder="Cylindre">
                         </div>
                         <div class="col-md-6">
                           <label for="axe_droite_pres">Axe : </label>
                           <input type="text" class="form-control" name="axe_droite_pres" id="axe_droite_pres" placeholder="Axe">
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_droite_pres">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_droite_pres" id="lentille_droite_pres" placeholder="Lentille">
                         </div>
                         <div class="col-md-6">
                           <label for="eip_droite_pres">Eip : </label>
                           <input type="text" class="form-control" name="eip_droite_pres" id="eip_droite_pres" placeholder="Eip">
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_droite_pres">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_droite_pres" id="hauteur_droite_pres" placeholder="Hauteur">
                         </div>
                       </div>
                     </fieldset>
                   </div>
               </div>
             </div>
           </div>
        </div>
      </div>
    </div>
 </div>
 <br>
 <!-- End Mesure_Client  -->
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
      <input type="hidden" name="prod_id" id="prod_id" value="" disabled>
      <input type="hidden" name="stock_qte" id="stock_qte" value="" disabled>
      <div class="card-text">
        {{-- <div class="form-row">
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
            <input type="number" class="form-control" name="qte" id="qte" value="1" min="1" max="1">
          </div>
          <div class="col-3">
            <label for="total">Total :</label>
            <input type="text" class="form-control" name="total" id="total" value="0.00" disabled>
          </div>
        </div> --}}
        {{-- // Rèf | Libelle | Qté | PU HT | MT HT | TVA % | MT TTC | Actions // --}}
        <div class="form-row">
          <div class="col-3">
            <label for="nom">Rèf :</label>
            <input type="text" class="form-control" name="ref" id="ref" value="rèf" disabled>
          </div>
          <div class="col-3">
            <label for="nom">Libelle :</label>
            <input type="text" class="form-control" name="libelle" id="libelle" value="libelle" disabled>
          </div>
          <div class="col-3">
            <label for="prix">PU HT :</label>
            <input type="number" class="form-control" name="prixht" id="prixht" value="0.00" min="0" step="0.01">
          </div>
          <div class="col-3">
            <label for="prix">PU TTC:</label>
            <input type="number" class="form-control" name="prix" id="prix" value="0.00" min="0" step="0.01">
          </div>
          <div class="col-3">
            <label for="qte">Qté : <span class="badge badge-info" id="badge_qte"></span></label>
            <input type="number" class="form-control" name="qte" id="qte" value="1" min="1" max="1">
          </div>
          <div class="col-3">
            <label for="prix">MT HT :</label>
            <input type="text" class="form-control" name="totalht" id="totalht" value="0.00" disabled>
          </div>
          <div class="col-3">
            <label for="prix">TVA % :</label>
            <input type="text" class="form-control" name="tva" id="tva" value="0" disabled>
          </div>
          <div class="col-3">
            <label for="total">MT TTC :</label>
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
  <!-- Begin LigneCommande  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Les Lignes des commandes :</h5>
      <div class="card-text">
        <div class="table-responsive">
          <table class="table" id="lignes">
            <thead class="bg-primary text-white">
              <tr>
                {{-- <th style="display : none;">#</th>
                <th>Libelle</th>
                <th>Prix</th>
                <th>Qté</th>
                <th>Total</th>
                <th>Actions</th> --}}
                <th style="display : none;">#</th>
                <th>Rèf</th>
                <th>Libelle</th>
                <th>Qté</th>
                <th>PU HT</th>
                <th>MT HT</th>
                <th>TVA %</th>
                <th>MT TTC</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
              {{-- <tr>
                <th style="display: none;"></th>
                <th></th>
                <th></th>
                <th>Total à payer</th>
                <th id="somme">0.00</th>
              </tr> --}}
              <tr></tr>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total HT</th>
                <th id="sommeht" class="text-right">0.00</th>
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total TVA</th>
                <th id="sommetva" class="text-right">0.00</th>
              </tr>
              <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Total TTC</th>
                <th id="somme" class="text-right">0.00</th>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- End LigneCommande  -->
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
    <button class="btn btn-secondary" id="valider">Valider la facture</button>
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
        url:'{!!Route('commande.productsCategory')!!}',
        data:{'id':cat_id},
        success:function(data){
          var options = '<option value="0" disabled="true" selected="true">-Product-</option>';
          if(data.length>0){
            for(var i=0;i<data.length;i++){
              options+=`<option value="${data[i].id}">${data[i].code_produit} | ${data[i].nom_produit.substring(0,15)}... | ${parseFloat(data[i].prix_produit_TTC).toFixed(2)} | ${parseFloat(data[i].quantite)}</option>`;
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

      var ref=$('#ref');
      var prixht=$('#prixht');
      var tva=$('#tva');
      var totalht=$('#totalht');

      $.ajax({
        type:'get',
        url:'{!!Route('commande.infosProducts')!!}',
        data:{'id':id},
        success:function(data){
          if(Object.keys(data).length>0){
            stock_qte.val(data.quantite);
            prod_id.val(data.id) ; 
            // libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...');
            // // (data.nom_produit) ? libelle.val(data.code_produit+' | '+data.nom_produit.substring(0,15)+'...') : libelle.val('');
            // // libelle.val(data.code_produit+' | '+data.nom_produit);
            // prix.val(parseFloat(data.prix_produit_TTC).toFixed(2));                
            // total.val(parseFloat(data.prix_produit_TTC).toFixed(2));   
            ref.val(data.code_produit);
            libelle.val(data.nom_produit.substring(0,15)+'...');
            prixht.val(parseFloat(data.prix_produit_HT).toFixed(2));                
            prix.val(parseFloat(data.prix_produit_TTC).toFixed(2));                
            total.val(parseFloat(data.prix_produit_TTC).toFixed(2));   
            tva.val(data.TVA);   
            totalht.val(parseFloat(data.prix_produit_HT).toFixed(2));   
            qte.val("1");
            qte.attr("max",parseFloat(data.quantite));
            badge_qte.html(parseFloat(data.quantite) - parseFloat(quantite_stock(prod_id.val())));
          }
        },
        error:function(){
        }
      });
    });
    // -----------End Change Product--------------//
    // -----------Change Qte--------------//
    function keyup_click_qte(){
      var qte=$('#qte').val();
      var prixht=$('#prixht').val();
      var prix=$('#prix').val();
      var totalht=$('#totalht');
      var total=$('#total');
      var NQte = parseFloat(qte);
      var NPrixht = parseFloat(prixht);
      var NPrix = parseFloat(prix);
      var NTotalht = NQte * NPrixht;
      var NTotal = NQte * NPrix;
      totalht.val(NTotalht.toFixed(2)) ;
      total.val(NTotal.toFixed(2)) ;
    }
    $(document).on('keyup','#qte',function(){
      keyup_click_qte();
    });
    $(document).on('click','#qte',function(){
      keyup_click_qte();
    });
    // -----------End Change Qte--------------//
    // -----------Begin AddLigne--------------//
    $(document).on('click','#addLigne',function(){
      var prod_id=$('#prod_id');
      var ref=$('#ref');
      var libelle=$('#libelle');
      var prixht=$('#prixht');
      var prix=$('#prix');
      var qte=$('#qte');
      var nqte = parseFloat(qte.val());
      var nmin = parseFloat(qte.attr('min'));
      var nmax = parseFloat(qte.attr('max'));
      var table=$('#lignes');
      var totalht=$('#totalht');
      var total=$('#total');
      var tva=$('#tva');
      var sommeht=$('#sommeht');
      var sommetva=$('#sommetva');
      var somme=$('#somme');
      var stock_qte=$('#stock_qte');
      var badge_qte=$('#badge_qte');
      vnstock_qte = parseFloat(stock_qte.val());

      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin || nqte > nmax)){
        if(libelle.val() == "" || prix.val() == "")
        message('','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
        message('','Les champs sont invalides !');
        if(nqte < nmin)
        message('Quantité','La quantité est invalide !');
        if(nqte > nmax)
        message('Quantité','La quantité demandée est supérieur à la quantité de stock !');
        return;
      }
      if(check(prod_id.val())){
        var q = parseFloat(quantite_stock(prod_id.val())) + parseFloat(qte.val());
        if(q < nmin || q > nmax){
          if(q < nmin)
          message('Quantité','La quantité est invalide !');
          if(q > nmax)
          message('Quantité','La quantité en stock est insuffisante !');
          return;
        }
        changeQte(qte.val(),prod_id.val());
      }
      else{
        var ligne=`<tr>
                    <td style="display : none;">${prod_id.val()}</td>
                    <td>${ref.val()}</td>
                    <td>${libelle.val()}</td>
                    <td>${qte.val()}</td>
                    <td>${parseFloat(prixht.val()).toFixed(2)}</td>
                    <td>${parseFloat(totalht.val()).toFixed(2)}</td>
                    <td>${tva.val()}</td>
                    <td>${parseFloat(total.val()).toFixed(2)}</td>
                    <td style="display : none;">${parseFloat(prix.val()).toFixed(2)}</td>
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
        totalht.val(prixht.val());
        total.val(prix.val());
        sommeht.html(calculSommeht());
        somme.html(calculSomme());
        sommetva.html(calculSommetva());
        calculReste();
        // badge_qte.html(parseFloat(nmax - parseFloat(quantite_stock(prod_id.val()))));
        badge_qte.html(parseFloat(stock_qte.val()) - parseFloat(quantite_stock(prod_id.val())));
    });
    // -----------End AddLigne--------------//
    // -----------Begin UpdateLigne--------------//
    $(document).on('click','#updateLigne',function(){
      var prod_id=$('#prod_id');
      var ref=$('#ref');
      var libelle=$('#libelle');
      var prixht=$('#prixht');
      var prix=$('#prix');
      var qte=$('#qte');
      var nqte = parseFloat(qte.val());
      var nmin = parseFloat(qte.attr('min'));
      var nmax = parseFloat(qte.attr('max'));
      var totalht=$('#totalht');
      var total=$('#total');
      var tva=$('#tva');
      var table=$('#lignes');
      var sommeht=$('#sommeht');
      var sommetva=$('#sommetva');
      var somme=$('#somme');
      var badge_qte=$('#badge_qte');
      var stock_qte=$('#stock_qte');

      if((libelle.val() == "" || prix.val() == "") || (libelle.val() == "libelle" || prix.val() == "0") || (nqte < nmin || nqte > nmax)){
        if(libelle.val() == "" || prix.val() == "")
        message('','Vérifier les champs vides !');
        if(libelle.val() == "libelle" || prix.val() == "0")
        message('','Les champs sont invalides !');
        if(nqte < nmin)
        message('Quantité','La quantité est invalide !');
        if(nqte > nmax)
        message('Quantité','La quantité demandée est supérieur à la quantité de stock !');
        return;
      }
      if(check(prod_id.val())){
        var index = checkIndex(prod_id.val());
        if(index != -1){
          var list = table.find('tbody').find('tr'); 
          list.eq(index).find('td').eq(0).html(prod_id.val());
          list.eq(index).find('td').eq(1).html(ref.val());
          list.eq(index).find('td').eq(2).html(libelle.val());
          list.eq(index).find('td').eq(3).html(qte.val());
          list.eq(index).find('td').eq(4).html(parseFloat(prixht.val()).toFixed(2));
          list.eq(index).find('td').eq(5).html(parseFloat(totalht.val()).toFixed(2));
          list.eq(index).find('td').eq(6).html(tva.val());
          list.eq(index).find('td').eq(7).html(parseFloat(total.val()).toFixed(2));
          badge_qte.html(parseFloat(stock_qte.val()) - parseFloat(nqte));
        }
      }
      qte.val("1");
      totalht.val(prixht.val());
      total.val(prix.val());
      sommeht.html(calculSommeht());
      sommetva.html(calculSommetva());
      somme.html(calculSomme());
      calculReste();
      // badge_qte.html(parseFloat(nmax - nqte));
    });
    // -----------End UpdateLigne--------------//
    // -----------keyup Prix--------------//
    function keyup_click_prixht(){
      var prix=$('#prix');
      var prixht=$('#prixht');
      var qte=$('#qte');
      var totalht=$('#totalht');
      var total=$('#total');
      var tva=$('#tva');

      NPrixht = parseFloat(prixht.val());
      NTva =  parseFloat(tva.val());
      prix.val((NPrixht + (NPrixht * NTva/100)).toFixed(2));
      NPrix = parseFloat(prix.val());
      NQte =  parseFloat(qte.val());
      NTotalht = NPrixht*NQte;
      totalht.val(NTotalht.toFixed(2));
      NTotal = NPrix*NQte;
      total.val(NTotal.toFixed(2));
    }
    $(document).on('keyup','#prixht',function(){
      keyup_click_prixht();
    });
    $(document).on('click','#prixht',function(){
      keyup_click_prixht();
    });
    function keyup_click_prix(){
      var prix=$('#prix');
      var prixht=$('#prixht');
      var qte=$('#qte');
      var totalht=$('#totalht');
      var total=$('#total');
      var tva=$('#tva');
      
      NPrix = parseFloat(prix.val());
      NTva =  parseFloat(tva.val());
      prixht.val((NPrix / (1 + NTva/100)).toFixed(2));
      NPrixht = parseFloat(prixht.val());
      NQte =  parseFloat(qte.val());
      NTotalht = NPrixht*NQte;
      totalht.val(NTotalht.toFixed(2));
      NTotal = NPrix*NQte;
      total.val(NTotal.toFixed(2));
    }
    $(document).on('keyup','#prix',function(){
      keyup_click_prix();
    });
    $(document).on('click','#prix',function(){
      keyup_click_prix();
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
      // ***** BEGIN variables commande ******** //
      var date=$('#date');
      var client=$('#client');
      var facture=$('#facture');
      // var gauche=$('#gauche');
      // --------------------- //
      sphere_gauche_loin=$('#sphere_gauche_loin');
      cylindre_gauche_loin=$('#cylindre_gauche_loin');
      axe_gauche_loin=$('#axe_gauche_loin');
      lentille_gauche_loin=$('#lentille_gauche_loin');
      eip_gauche_loin=$('#eip_gauche_loin');
      hauteur_gauche_loin=$('#hauteur_gauche_loin');
      // --------------------- //
      sphere_droite_loin=$('#sphere_droite_loin');
      cylindre_droite_loin=$('#cylindre_droite_loin');
      axe_droite_loin=$('#axe_droite_loin');
      lentille_droite_loin=$('#lentille_droite_loin');
      eip_droite_loin=$('#eip_droite_loin');
      hauteur_droite_loin=$('#hauteur_droite_loin');
      // --------------------- //
      sphere_gauche_pres=$('#sphere_gauche_pres');
      cylindre_gauche_pres=$('#cylindre_gauche_pres');
      axe_gauche_pres=$('#axe_gauche_pres');
      lentille_gauche_pres=$('#lentille_gauche_pres');
      eip_gauche_pres=$('#eip_gauche_pres');
      hauteur_gauche_pres=$('#hauteur_gauche_pres');
      // --------------------- //
      sphere_droite_pres=$('#sphere_droite_pres');
      cylindre_droite_pres=$('#cylindre_droite_pres');
      axe_droite_pres=$('#axe_droite_pres');
      lentille_droite_pres=$('#lentille_droite_pres');
      eip_droite_pres=$('#eip_droite_pres');
      hauteur_droite_pres=$('#hauteur_droite_pres');
      // --------------------- //
      // var droite=$('#droite');
      // ***** END variables commande ******** //

      // ***** BEGIN variables lignes ******** //
      var table=$('#lignes');
      var list = table.find('tbody').find('tr');
      var array = [];
      for (let i = 0; i < list.length; i++) {
        var prod_id = list.eq(i).find('td').eq(0).html();
        var ref = list.eq(i).find('td').eq(1).html();
        var libelle = list.eq(i).find('td').eq(2).html();
        var qte = list.eq(i).find('td').eq(3).html();
        var prixht = list.eq(i).find('td').eq(4).html();
        var NPrixht = parseFloat(prixht);
        var totalht = list.eq(i).find('td').eq(5).html();
        var tva = list.eq(i).find('td').eq(6).html();
        var NTva = parseFloat(tva);
        var total = list.eq(i).find('td').eq(7).html();

        NPrix = (NPrixht + (NPrixht * NTva/100)).toFixed(2);

        var obj = {
              "prod_id":parseInt(prod_id),
              // "libelle":libelle,
              // "prix":parseFloat(prix),
              "prix":parseFloat(NPrix),
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
        url:'{{Route('facture.store')}}',
        data:{
          _token : _token.val(),
          date : date.val(),
          client : parseInt(client.val()),
          code_facture : facture.val(),
          // gauche : gauche.val(),
          sphere_gauche_loin:sphere_gauche_loin.val(),
          cylindre_gauche_loin:cylindre_gauche_loin.val(),
          axe_gauche_loin:axe_gauche_loin.val(),
          lentille_gauche_loin:lentille_gauche_loin.val(),
          eip_gauche_loin:eip_gauche_loin.val(),
          hauteur_gauche_loin:hauteur_gauche_loin.val(),
          // --------------------- //
          sphere_droite_loin:sphere_droite_loin.val(),
          cylindre_droite_loin:cylindre_droite_loin.val(),
          axe_droite_loin:axe_droite_loin.val(),
          lentille_droite_loin:lentille_droite_loin.val(),
          eip_droite_loin:eip_droite_loin.val(),
          hauteur_droite_loin:hauteur_droite_loin.val(),
          // --------------------- //
          sphere_gauche_pres:sphere_gauche_pres.val(),
          cylindre_gauche_pres:cylindre_gauche_pres.val(),
          axe_gauche_pres:axe_gauche_pres.val(),
          lentille_gauche_pres:lentille_gauche_pres.val(),
          eip_gauche_pres:eip_gauche_pres.val(),
          hauteur_gauche_pres:hauteur_gauche_pres.val(),
          // --------------------- //
          sphere_droite_pres:sphere_droite_pres.val(),
          cylindre_droite_pres:cylindre_droite_pres.val(),
          axe_droite_pres:axe_droite_pres.val(),
          lentille_droite_pres:lentille_droite_pres.val(),
          eip_droite_pres:eip_droite_pres.val(),
          hauteur_droite_pres:hauteur_droite_pres.val(),
          // droite : droite.val(),
          lignes : array,
          mode:mode.val(),
          avance:parseFloat(avance.val()),
          reste:parseFloat(reste.val()),
          status:status,
          total:calculSomme(),
          totalht:calculSommeht(),
          totaltva:calculSommetva(),
        },
        success: function(data){
          // Swal.fire(data.message);
          message(data.status,'',data.message);
          if(data.status == "success"){
            // begin swal2
            Swal.fire({
                title: "Facture",
                text: "Voulez-vous imprimer cette facture ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Non',
                confirmButtonText: 'Oui'
            }).then((result) => {
                if (result.isConfirmed) {
                  var facture_id = data.facture_id;
                  if(!facture_id) return;
                  var url = "{{route('facture.show',['facture'=>":id"])}}".replace(':id', facture_id);
                  setTimeout(() => {
                    window.location.assign(url)
                  }, 2000);
                }
                else{
                  setTimeout(() => {
                    window.location.assign('{{route('facture.index')}}')
                  }, 2000);
                }
            })
            // end swal2
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
            // Swal.fire("Erreur d'enregistrement de la facture !");
            message('error','',"Erreur d'enregistrement de la facture !");
          }
        },
      });
    });
    // -----------End valider--------------//
  });
  // -----------Display vision loin--------------//
  var loin = true;
  var pres = true;
  function eventLoin(){
    (loin) ? styleDiv = 'display : block' : styleDiv = 'display : none';  
    $('#div_loin').attr('style',styleDiv);  
    (loin) ? iconClass = 'fas fa-arrow-up' : iconClass = 'fas fa-arrow-down';  
    $('#icon_loin').attr('class',iconClass);  
    loin = !loin; 
  }
  function eventPres(){
    (pres) ? styleDiv = 'display : block' : styleDiv = 'display : none';  
    $('#div_pres').attr('style',styleDiv);  
    (pres) ? iconClass = 'fas fa-arrow-up' : iconClass = 'fas fa-arrow-down';  
    $('#icon_pres').attr('class',iconClass);  
    pres = !pres; 
  }
  // -----------My function--------------//
  function remove(id){
    var i = checkIndex(id);
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var td = list.eq(i).find('td');
    // ################################# //
    var prod_id=$('#prod_id');
    var ref=$('#ref');
    var libelle=$('#libelle');
    var prixht=$('#prixht');
    var prix=$('#prix');
    var qte=$('#qte');
    var totalht=$('#totalht');
    var total=$('#total');
    var tva=$('#tva');
    var badge_qte=$('#badge_qte');
    // ################################# //
    prod_id.val(td.eq(0).html());
    ref.val(td.eq(1).html());
    libelle.val(td.eq(2).html());
    qte.val(1);
    prixht.val(td.eq(4).html());
    totalht.val(td.eq(4).html());
    tva.val(td.eq(6).html());
    total.val(td.eq(8).html());
    prix.val(td.eq(8).html());
    // ################################# //
    var vStock_qte = td.eq(9).html();
    badge_qte.html(vStock_qte);
    // ################################# //
    list.eq(i).remove();
    var sommeht=$('#sommeht');
    var sommetva=$('#sommetva');
    var somme=$('#somme');
    sommeht.html(calculSommeht());
    sommetva.html(calculSommetva());
    somme.html(calculSomme());
    calculReste();
  }
  function edit(id){
    var i = checkIndex(id);
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var td = list.eq(i).find('td');

    var prod_id = $('#prod_id');
    var ref = $('#ref');
    var libelle = $('#libelle');
    var prixht = $('#prixht');
    var prix = $('#prix');
    var qte = $('#qte');
    var totalht = $('#totalht');
    var total = $('#total');
    var tva = $('#tva');

    var vProd_id = td.eq(0).html();
    var vRef = td.eq(1).html();
    var vLibelle = td.eq(2).html();
    var vQte = td.eq(3).html();
    var vPrixht = td.eq(4).html();
    var NPrixht = parseFloat(vPrixht);
    var vTotalht = td.eq(5).html();
    var vTva = td.eq(6).html();
    var NTva = parseFloat(vTva);
    var vTotal = td.eq(7).html();

    prod_id.val(vProd_id);
    ref.val(vRef);
    libelle.val(vLibelle);
    prixht.val(vPrixht);
    prix.val((NPrixht + (NPrixht * NTva/100)).toFixed(2));
    qte.val(vQte);
    totalht.val(vTotalht);
    total.val(vTotal);
    // ################################### //
    var badge_qte=$('#badge_qte');
    $.ajax({
      type:'get',
      url:'{!!Route('commande.infosProducts')!!}',
      data:{'id':id},
      success:function(data){
        if(Object.keys(data).length>0){
          badge_qte.html(parseFloat(data.quantite) - parseFloat(quantite_stock(prod_id.val())));
        }
      },
      error:function(){}
    });
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
    var ref = list.eq(i).find('td').eq(1).html();
    var libelle = list.eq(i).find('td').eq(2).html();
    var qte = list.eq(i).find('td').eq(3).html();
    var prixht = list.eq(i).find('td').eq(4).html();
    var totalht = list.eq(i).find('td').eq(5).html();
    var tva = list.eq(i).find('td').eq(6).html();
    var total = list.eq(i).find('td').eq(7).html();

    var NTva = parseFloat(tva);
    var NPrixht = parseFloat(prixht);
    var NQte = parseFloat(qte) + parseFloat(qteNew);
    var NTotalht = NQte * NPrixht;
    var NTotal = (NTotalht + (NTotalht * NTva/100)).toFixed(2);
    list.eq(i).find('td').eq(3).html(NQte);
    list.eq(i).find('td').eq(5).html(parseFloat(NTotalht).toFixed(2));
    list.eq(i).find('td').eq(7).html(parseFloat(NTotal).toFixed(2));
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
  function calculSommeht(){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var sommeht = 0.0;
    for (let i = 0; i < list.length; i++) {
      var total = list.eq(i).find('td').eq(5).html();
      var NTotal = parseFloat(total);
      sommeht+=NTotal;
    }
    return sommeht.toFixed(2);
  }
  function calculSomme(){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var somme = 0.0;
    for (let i = 0; i < list.length; i++) {
      var total = list.eq(i).find('td').eq(7).html();
      var NTotal = parseFloat(total);
      somme+=NTotal;
    }
    return somme.toFixed(2);
  }
  function calculSommetva(){
    var table=$('#lignes');
    var list = table.find('tbody').find('tr'); 
    var sommeht = 0.0;
    var somme = 0.0;
    var sommetva = 0.0;
    for (let i = 0; i < list.length; i++) {
      var totalht = list.eq(i).find('td').eq(5).html();
      var total = list.eq(i).find('td').eq(7).html();
      var NTotalht = parseFloat(totalht);
      var NTotal = parseFloat(total);
      sommeht+=NTotalht;
      somme+=NTotal;
    }
    sommetva = somme - sommeht;
    return sommetva.toFixed(2);
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
  function message(title,text){
    Swal.fire({
      title: title,
      text: text,
      icon: 'warning',
      showCancelButton: true,
      showConfirmButton : false,
      cancelButtonColor: '#d33',
      cancelButtonText: 'Annuler',
    });
  }
</script>
<!-- ##################################################################### -->
@endsection