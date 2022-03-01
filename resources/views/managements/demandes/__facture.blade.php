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
                <label for="gauche">Oeil gauche</label>
                <input type="text" class="form-control" name="oeil_gauche" id="gauche" placeholder="oeil_gauche" value="{{old('oeil_gauche',$commande->oeil_gauche ?? null)}}" disabled>
              </div>
              <div class="col-4">
                <label for="droite">Oeil droite</label>
                <input type="text" class="form-control" name="oeil_droite" id="droite" placeholder="oeil_droite" value="{{old('oeil_droite',$commande->oeil_droite ?? null)}}" disabled>
              </div>
            </div>
      </div>
    </div>
  </div>
  <!-- End Commande_Client  -->
  <br>
  <!-- Begin Code_Facture  -->
  <div class="card text-left">
    <div class="card-body">
      <div class="card-text">
            <div class="form-row">
                <div class="col-2"></div>   
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
                <div class="col-2"></div>   
            </div>
      </div>
    </div>
  </div>
  <!-- End Code_Facture  -->
  <br>
  <!-- Begin LigneCommande  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Les Lignes des commandes :</h5>
      <div class="card-text">
        <table class="table" id="lignes">
          <thead>
            <tr>
                <th>#</th>
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
        <input type="submit" class="btn btn-info bnt-lg" value="Valider la facture">
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
                  Swal.fire("Erreur de généralisation de code !");
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
          Swal.fire(err);
      },
    });
  }
</script>
@endsection

