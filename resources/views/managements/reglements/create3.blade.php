@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Créer un règlement client</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Règlement</li>
  </ol>
</div>
{{-- ################## --}}
<br>
{{-- ################## --}}
{{ Html::style(asset('css/loadingstyle.css')) }}
{{-- ################## --}}
<div style="display:none;" id="loading" class="text-center">
  <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width:200px">
</div>
{{-- ################## --}}
<div class="container">
  <div class="row">
    <div class="col-6">
      <input type="date" 
      class="form-control" 
      name="date" 
      id="date" 
      value={{$date}}
      placeholder="date">
    </div>
    <div class="col-6">
      <input type="text" class="form-control" name="client" id="client" value="{{$commande->client->nom_client}}" disabled>
    </div>
  </div>
  <br>
  <!-- Begin Reglement  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Règlement :</h5>
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
            <input type="number" id="avance" name="avance" min="0" step="0.01" class="form-control" placeholder="0.00" value="0.00">
          </div>
          <div class="col-3">
            <label for="reste">Reste :</label>
            <input type="number" id="reste" name="reste" class="form-control" placeholder="reste" min="0" step="0.01" value="0.00" disabled>

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
  <!-- Begin TABLE -->
  <br>
  <div class="card">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table" id="table">
          <thead class="bg-primary text-white">
            <tr>
              <th style="display: none">cmd_id</th>
              <th>Rèf</th>
              <th>Date</th>
              {{-- <th>Client</th> --}}
              <th>Montant total</th>
              <th>Montant payer</th>
              <th>Reste à payer</th>
              <th>[Avance]</th>
              <th>[Reste]</th>
              <th>[Status]</th>
          </tr>
          </thead>
          <tbody>
            <tr>
                <td id="cmd_id" style="display: none">{{$commande->id}}</td>
                <td>{{$commande->code}}</td>
                <td>{{$commande->date}}</td>
                {{-- <td id="client">{{$commande->client->nom_client}}</td> --}}
                <td id="total">{{number_format($commande->total, 2, '.', '')}}</td>
                <td id="avance1">{{number_format($commande->avance, 2, '.', '')}}</td>
                <td id="reste1">{{number_format($commande->reste, 2, '.', '')}}</td>
                <td id="avance2">
                  <input type="number" 
                    min="0" 
                    style="width: 50%" 
                    value="0.00" 
                    step="0.01" 
                    onclick="setAvances('{{$commande->id}}')" 
                    onkeyup="setAvances('{{$commande->id}}')">
                </td>
                <td id="reste2">{{number_format($commande->reste, 2, '.', '')}}</td>
                <td id="status2">NR</td>
            </tr>
          </tbody>
          <tfoot>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
  <!-- End TABLE -->
  <br>
  <div class="text-right">
    <button class="btn btn-secondary" id="valider">Effectuer le règlement</button>
  </div>
  <br>
</div>
<!-- ---------  BEGIN SCRIPT --------- -->
<script type="text/javascript">
  // -----------Begin Déclaration des variables--------------//
    var avance = $('#avance');
    var reste = $('#reste');
    var stat = $('#status');
    var mode=$('#mode');
    var date=$('#date');
    var client=$('#client');
    var table = $('#table');
    var tbody = table.find('tbody');
    var tfoot = table.find('tfoot');
  // -----------End Déclaration des variables--------------//
  $(document).ready(function(){
    // -----------Change Select_Client--------------//
    $(document).on('change','#client',function(){
      $client = $(this).val();
      if($client == "")
        getReglements();
      else
        getReglements($client);
    });
    // -----------End Select_Client--------------//
    // -----------keyup Avance--------------//
    $(document).on('keyup','#avance',function(){
      var navance = parseFloat(avance.val());
      if(navance > sommeReste())
        avance.val(sommeReste());
      if(avance.val()=="")
        avance.val(0);
      // --------------
      calculs();
      calculsLignes();
      // --------------
      getTfoot();
    });
    $(document).on('click','#avance',function(){
      var navance = parseFloat(avance.val());
      if(navance > sommeReste())
        avance.val(sommeReste());
      if(avance.val()=="")
        avance.val(0);
      // --------------
      calculs();
      calculsLignes();
      // --------------
      getTfoot();
    });
    // -----------End keyup Avance--------------//
    // -----------Begin valider--------------//
    $(document).on('click','#valider',function(e){
      var val1 = $('#avance').val();
      var val2 = $('#avance2').find('input').val();
      if(parseFloat(val1) != parseFloat(val2)) {
        // Swal.fire("Merci de vérifier le solde d'avance !");
        message('warning','',"Merci de vérifier le solde d'avance !");
        return;
      }
      $('#valider').prop('disabled',true);
      $('#loading').prop('style','display : block');
      //---> Envoi des information via method POST
      var _token=$('input[name=_token]'); 
      // var _token = $('meta[name="csrf-token"]').attr('content');
      // ***** BEGIN variables lignes ******** //
      var list = tbody.find('tr');
      var i = 0;
      var array = [];
        // var cmd_id = list.eq(i).find('td').eq(0).html();
        // var client = list.eq(i).find('td').eq(3).html();
        // var avance = list.eq(i).find('td').eq(7).find('input').val();
        // var navance = parseFloat(avance);
        // var reste = list.eq(i).find('td').eq(8).html();
        // var status = list.eq(i).find('td').eq(9).html();
        var cmd_id = $('#cmd_id').html();
        var client = $('#client').html();
        var avance = $('#avance2').find('input').val();
        var navance = parseFloat(avance);
        var reste = $('#reste2').html();
        var status = $('#status2').html();
        if(navance > 0){
          var obj = {
                "cmd_id":parseInt(cmd_id),
                "client":client,
                "avance":navance,
                "reste":parseFloat(reste),
                "status":status,
              };
          array = [...array,obj];
        }
      // ***** END variables lignes ******** //
      $.ajax({
        type:'post',
        url:"{!!URL::to('storeReglements3')!!}",
        data:{
          _token : _token.val(),
          // _token : _token,
          date : date.val(),
          mode:mode.val(),
          lignes : array,
        },
        success: function(data){
          // Swal.fire(data.message);
            message(data.status,'',data.message);
          if(data.status == "success"){
            setTimeout(() => {
              window.location.assign("{{route('commande.index')}}")
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
            // Swal.fire("Erreur d'enregistrement de règlement !");
            message('error','',"Erreur d'enregistrement de règlement !");
          }
        },
      });
    });
    // -----------End valider--------------//
  });
  // -----------My function--------------//
  test();
  calculs();
  // --------------
  getTfoot();
  function test(){
  }
  function search(){ }
  function sommeTotal(){
    var list = tbody.find('tr');
    var ntotal = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var total = parseFloat(ligne.eq(4).html());
      var total = parseFloat($('#total').html());
      ntotal += total;
    }
    return ntotal;
  }
  function sommeAvance(){
    var list = tbody.find('tr');
    var navance = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var avance = parseFloat(ligne.eq(5).html());
      var avance = parseFloat($('#avance1').html());
      navance += avance;
    }
    return navance;
  }
  function sommeAvance2(){
    var list = tbody.find('tr');
    var navance = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var avance = parseFloat(ligne.eq(7).find('input').val());
      var avance = parseFloat($('#avance2').find('input').val());
      navance += avance;
    }
    return navance;
  }
  function sommeReste(){
    var list = tbody.find('tr');
    var nreste = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var reste = parseFloat(ligne.eq(6).html());
      var reste = parseFloat($('#reste1').html());
      nreste += reste;
    }
    return nreste;
  }
  function sommeReste2(){
    var list = tbody.find('tr');
    var nreste = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var reste = parseFloat(ligne.eq(8).html());
      var reste = parseFloat($('#reste2').html());
      nreste += reste;
    }
    return nreste;
  }
  function calculs(){
    var sreste = sommeReste();
    var res = sreste-parseFloat(avance.val());
    reste.val(res.toFixed(2));
    (res>0) ? stat.val('NR'): stat.val('R');
  }
  function calculsLignes(){
    var navance = parseFloat(avance.val());
    var reg = navance;
    var list = tbody.find('tr');
    for (let index = 0; index < list.length; index++) {  
      // var ligne = list.eq(index).find('td');
      // --------------
      // var reste_cmd = ligne.eq(6);
      var reste_cmd = $('#reste1');
      var nreste_cmd = parseFloat(reste_cmd.html());
      // var av = ligne.eq(7);
      var av = $('#avance2');
      // var res = ligne.eq(8);
      var res = $('#reste2');
      // var stat = ligne.eq(9);
      var stat = $('#status2');
      var nres = 0;
      var pay = 0;
      // --------------
      if(reg>=nreste_cmd)
        pay = nreste_cmd;
      else 
        pay = reg;
      // av.html(pay);
      av.find('input').val(pay.toFixed(2));
      nres = nreste_cmd - pay;
      // res.html(nres);
      res.html(parseFloat(nres).toFixed(2));
      // reg -= parseFloat(av.html());
      reg -= parseFloat(av.find('input').val());
      (nres>0)?stat.html('NR'):stat.html('R');
    }
    // --------------
    getTfoot();
  }
  function checkIndex(id){
    var index = -1;
    var list = tbody.find('tr'); 
    for (let i = 0; i < list.length; i++) {
      // var cmd_id = list.eq(i).find('td').eq(0).html();
      var cmd_id = $('#cmd_id').html();
      if(cmd_id == id){
        index = i;
        break;
      }
    }
    return index;
  }
  function setAvances(cmd_id){
    var index = checkIndex(cmd_id);
    var list = tbody.find('tr');
    // var ligne = list.eq(index).find('td');
    // --------------
    // var reste_cmd = ligne.eq(6);
    var reste_cmd = $('#reste1');
    var nreste_cmd = parseFloat(reste_cmd.html());
    // --------------
    // var av = ligne.eq(7);
    var av = $('#avance2');
    // var res = ligne.eq(8);
    var res = $('#reste2');
    // var stat = ligne.eq(9);
    var stat = $('#status2');
    // --------------
    var input = av.find('input');
    var pay = parseFloat(input.val());
    if(pay > nreste_cmd)
        input.val(nreste_cmd.toFixed(2));
    if(input.val()=="")
      input.val(0);
    // --------------
    var nres = nreste_cmd - pay;
    res.html(nres.toFixed(2));
    (nres>0)?stat.html('NR'):stat.html('R');
    // --------------
    avance.val(sommeAvance2().toFixed(2));
    reste.val(sommeReste2().toFixed(2));
    (parseFloat(reste.val())>0)?$('#status').val('NR'):$('#status').val('R');
    // --------------
    getTfoot();
  }
  function getTfoot(){
    // var list = tfoot.find('tr');
    // var total = list.find('th').eq(4);
    var total = $('#ftotal');
    // var avance1 = list.find('th').eq(5);
    var avance1 = $('#favance1');
    // var reste1 = list.find('th').eq(6);
    var reste1 = $('#freste1');
    // var avance2 = list.find('th').eq(7);
    var avance2 = $('#favance2');
    // var reste2 = list.find('th').eq(8);
    var reste2 = $('#freste2');
    total.html(sommeTotal().toFixed(2));
    avance1.html(sommeAvance().toFixed(2));
    reste1.html(sommeReste().toFixed(2));
    avance2.html(sommeAvance2().toFixed(2));
    reste2.html(sommeReste2().toFixed(2));
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

