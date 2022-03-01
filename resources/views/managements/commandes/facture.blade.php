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
<div class="container">
  <br>
  <!-- Begin Commande_Client  -->
  <div class="card text-left">
    <div class="card-body">
      <div class="card-text">
            <div class="form-row">
              <div class="col-4"> 
                <label for="client">Client</label>
                <input type="text" class="form-control" name="client" id="client" placeholder="client" value="{{$commande->client->nom_client}}" disabled>
              </div>
              <div class="col-4">
                  <label for="date">Date</label>
                  <input type="date" 
                  class="form-control" 
                  name="date" 
                  id="date" 
                  value="{{old('date',$commande->date)}}"
                  placeholder="date">
              </div>     
              <div class="col-4">  
                  <label for="code">Code Facture :</label>   
                  <input type="text" class="form-control" name="code" id="code" placeholder="code" value="{{$code}}" >
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
                 <h6 class="font-weight-bold">Vision de loin <i class="fas fa-arrow-down" id="icon_loin" onclick="eventLoin()"></i></h6>
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
                           <input type="text" class="form-control" name="sphere_gauche_loin" id="sphere_gauche_loin" placeholder="Sphère" value="{{old('sphere_gauche_loin',json_decode($commande->vision_loin,false)->sphere_gauche_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_gauche_loin">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_gauche_loin" id="cylindre_gauche_loin" placeholder="Cylindre" value="{{old('cylindre_gauche_loin',json_decode($commande->vision_loin,false)->cylindre_gauche_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="axe_gauche_loin">Axe : </label>
                           <input type="text" class="form-control" name="axe_gauche_loin" id="axe_gauche_loin" placeholder="Axe" value="{{old('axe_gauche_loin',json_decode($commande->vision_loin,false)->axe_gauche_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_gauche_loin">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_gauche_loin" id="lentille_gauche_loin" placeholder="Lentille" value="{{old('lentille_gauche_loin',json_decode($commande->vision_loin,false)->lentille_gauche_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="eip_gauche_loin">Eip : </label>
                           <input type="text" class="form-control" name="eip_gauche_loin" id="eip_gauche_loin" placeholder="Eip" value="{{old('eip_gauche_loin',json_decode($commande->vision_loin,false)->eip_gauche_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_gauche_loin">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_gauche_loin" id="hauteur_gauche_loin" placeholder="Hauteur" value="{{old('hauteur_gauche_loin',json_decode($commande->vision_loin,false)->hauteur_gauche_loin ?? null)}}" disabled>
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
                           <input type="text" class="form-control" name="sphere_droite_loin" id="sphere_droite_loin" placeholder="Sphère" value="{{old('sphere_droite_loin',json_decode($commande->vision_loin,false)->sphere_droite_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_droite_loin">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_droite_loin" id="cylindre_droite_loin" placeholder="Cylindre" value="{{old('cylindre_droite_loin',json_decode($commande->vision_loin,false)->cylindre_droite_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="axe_droite_loin">Axe : </label>
                           <input type="text" class="form-control" name="axe_droite_loin" id="axe_droite_loin" placeholder="Axe" value="{{old('axe_droite_loin',json_decode($commande->vision_loin,false)->axe_droite_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_droite_loin">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_droite_loin" id="lentille_droite_loin" placeholder="Lentille" value="{{old('lentille_droite_loin',json_decode($commande->vision_loin,false)->lentille_droite_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="eip_droite_loin">Eip : </label>
                           <input type="text" class="form-control" name="eip_droite_loin" id="eip_droite_loin" placeholder="Eip" value="{{old('eip_droite_loin',json_decode($commande->vision_loin,false)->eip_droite_loin ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_droite_loin">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_droite_loin" id="hauteur_droite_loin" placeholder="Hauteur" value="{{old('hauteur_droite_loin',json_decode($commande->vision_loin,false)->hauteur_droite_loin ?? null)}}" disabled>
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
               <h6 class="font-weight-bold">Vision de près <i class="fas fa-arrow-down" id="icon_pres" onclick="eventPres()"></i></h6>
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
                           <input type="text" class="form-control" name="sphere_gauche_pres" id="sphere_gauche_pres" placeholder="Sphère" value="{{old('sphere_gauche_pres',json_decode($commande->vision_pres,false)->sphere_gauche_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_gauche_pres">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_gauche_pres" id="cylindre_gauche_pres" placeholder="Cylindre" value="{{old('cylindre_gauche_pres',json_decode($commande->vision_pres,false)->cylindre_gauche_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="axe_gauche_pres">Axe : </label>
                           <input type="text" class="form-control" name="axe_gauche_pres" id="axe_gauche_pres" placeholder="Axe" value="{{old('axe_gauche_pres',json_decode($commande->vision_pres,false)->axe_gauche_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_gauche_pres">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_gauche_pres" id="lentille_gauche_pres" placeholder="Lentille" value="{{old('lentille_gauche_pres',json_decode($commande->vision_pres,false)->lentille_gauche_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="dreip_gauche_pres">Eip : </label>
                           <input type="text" class="form-control" name="eip_gauche_pres" id="eip_gauche_pres" placeholder="Eip" value="{{old('eip_gauche_pres',json_decode($commande->vision_pres,false)->eip_gauche_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_gauche_pres">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_gauche_pres" id="hauteur_gauche_pres" placeholder="Hauteur" value="{{old('hauteur_gauche_pres',json_decode($commande->vision_pres,false)->hauteur_gauche_pres ?? null)}}" disabled>
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
                           <input type="text" class="form-control" name="sphere_droite_pres" id="sphere_droite_pres" placeholder="Sphère" value="{{old('sphere_droite_pres',json_decode($commande->vision_pres,false)->sphere_droite_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="cylindre_droite_pres">Cylindre : </label>
                           <input type="text" class="form-control" name="cylindre_droite_pres" id="cylindre_droite_pres" placeholder="Cylindre" value="{{old('cylindre_droite_pres',json_decode($commande->vision_pres,false)->cylindre_droite_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="axe_droite_pres">Axe : </label>
                           <input type="text" class="form-control" name="axe_droite_pres" id="axe_droite_pres" placeholder="Axe" value="{{old('axe_droite_pres',json_decode($commande->vision_pres,false)->axe_droite_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="lentille_droite_pres">Lentille : </label>
                           <input type="text" class="form-control" name="lentille_droite_pres" id="lentille_droite_pres" placeholder="Lentille" value="{{old('lentille_droite_pres',json_decode($commande->vision_pres,false)->lentille_droite_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="eip_droite_pres">Eip : </label>
                           <input type="text" class="form-control" name="eip_droite_pres" id="eip_droite_pres" placeholder="Eip" value="{{old('eip_droite_pres',json_decode($commande->vision_pres,false)->eip_droite_pres ?? null)}}" disabled>
                         </div>
                         <div class="col-md-6">
                           <label for="hauteur_droite_pres">Hauteur : </label>
                           <input type="text" class="form-control" name="hauteur_droite_pres" id="hauteur_droite_pres" placeholder="Hauteur" value="{{old('hauteur_droite_pres',json_decode($commande->vision_pres,false)->hauteur_droite_pres ?? null)}}" disabled>
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
  <!-- Begin LigneCommande  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Les Lignes des commandes :</h5>
      <div class="card-text">
        <table class="table" id="lignes">
          <thead>
            <tr>
                <th>Rèf</th>
                <th>Libelle</th>
                <th>Qté</th>
                <th>PU HT</th>
                <th>MT HT</th>
                <th>TVA %</th>
                <th>MT TTC</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr></tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Total HT</th>
              <th id="ht">0.00</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Total TVA</th>
              <th id="tva">0.00</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th>Total TTC</th>
              <th id="ttc">0.00</th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <!-- End LigneCommande  -->

  <br>
  <!-- Begin FORM  -->
  <div class="text-right">
    <form  method="POST" action="{{route('facture.store2')}}">
        @csrf 
        <input type="hidden" name="commande_id" value="{{$commande->id}}">
        <input type="hidden" name="date" value="{{$date}}">
        <input type="hidden" name="code" id="newCode" value="{{$code}}">
        <input type="hidden" name="total_HT" value="{{$HT}}">
        <input type="hidden" name="total_TVA" value="{{$TVA}}">
        <input type="hidden" name="total_TTC" value="{{$TTC}}" >
        <input type="hidden" name="reglement" value="à réception">
        <input type="submit" class="btn btn-info bnt-lg" value="Valider la facture" onclick="$(this).prop('style','pointer-events: none;background: #ccc;');">
    </form>
</div>
  <!-- End FORM  -->
  <br>
</div>

<!-- ---------  BEGIN SCRIPT --------- -->
<script type="text/javascript">
  $(document).ready(function(){
      getLignes();
      // -----------BEGIN Generation de  Code--------------//
      $(document).on('change','#date',function(){
          $.ajax({
              type:'get',
              url:'{{Route('commande.codeFacture')}}',
              data:{
                  date : $('#date').val(),
              },
              success: function(res){
                  $('#code').val(res);
                  $('#newCode').val(res);
              } ,
              error:function(err){
                  // Swal.fire("Erreur de généralisation de code !");
                  message('error','',"Erreur de généralisation de code !");
              },
          });
      });
      // -----------END Generation de  Code--------------//
      // -----------BEGIN Generation de  Code--------------//
      $(document).on('keyup','#code',function(){
          $('#newCode').val($(this).val());
      });
      // -----------END Generation de  Code--------------//
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
    
  function getLignes(){
    var cmd_id = <?php echo $commande->id;?>;
    $.ajax({
      type:'get',
      url:'{!!route('commande.editCommande')!!}',
      data:{'id' : cmd_id},
      success: function(data){
        var lignecommandes = data.lignecommandes
        var reglement = data.reglement
        // -----------BEGIN lignes--------------//
        var table = $('#lignes');
        table.find('tbody').html("");
        var lignes = '';
        var montant_HT = 0;
        var prix_unit_HT = 0;
        var HT = 0;
        var TTC = 0;
        lignecommandes.forEach(ligne => {
            montant_HT = ligne.total_produit / (1 + ligne.produit.TVA/100);
            prix_unit_HT = montant_HT / ligne.quantite;
            HT += montant_HT;
            TTC += parseFloat(ligne.total_produit);
            lignes+=`<tr>
                    <td>${ligne.produit.code_produit}</td>
                    <td>${ligne.produit.nom_produit}</td>
                    <td>${ligne.quantite}</td>
                    <td>${parseFloat(prix_unit_HT).toFixed(2)}</td>
                    <td>${parseFloat(montant_HT).toFixed(2)}</td>
                    <td>${ligne.produit.TVA}</td>
                    <td>${parseFloat(ligne.total_produit).toFixed(2)}</td>
                </tr>`;
        });
        table.find('tbody').append(lignes);
        // var somme=$('#somme');
        // somme.html(calculSomme());
        var TVA = TTC - HT;
        $('#ht').html(parseFloat(HT).toFixed(2));
        $('#tva').html(parseFloat(TVA).toFixed(2));
        $('#ttc').html(parseFloat(TTC).toFixed(2));
        // -----------END lignes--------------// 
      } ,
      error:function(err){
          // Swal.fire(err);
          message('error','',err);
      },
    });
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
@endsection

