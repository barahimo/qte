@extends('layout.dashboard')
@section('contenu')
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Créer un règlements fournisseur</h1>
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
      <select class="form-control" name="fournisseur" id="fournisseur">
        <option value="">--La liste des fournisseurs--</option>
        @foreach($fournisseurs as $item)
        <option value="{{$item->id }}" @if ($item->id == old('fournisseur_id',$fournisseur)) selected="selected" @endif>{{ $item->nom_fournisseur}}</option>
        @endforeach
      </select>
    </div>
    <div class="col-6">
      <input type="date" 
      class="form-control" 
      name="date" 
      id="date" 
      value={{$date}}
      placeholder="date">
    </div>
  </div>
  <br>
  <!-- Begin payement  -->
  <div class="card text-left">
    <div class="card-body">
      <h5 class="card-title">Règlement :</h5>
      <div class="card-text">
        <div class="form-row">
          <div class="col-3">
            <label for="mode">Mode de règlement :</label>
            <select class="form-control" id="mode" name='mode' placeholder="mode payement"> 
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
  <!-- End payement  -->
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
              <th>Fournisseur</th>
              <th>Facture</th>
              <th>Montant total</th>
              <th>Montant payer</th>
              <th>Reste à payer</th>
              <th>[Avance]</th>
              <th>[Reste]</th>
              <th>[Status]</th>
          </tr>
          </thead>
          <tbody>
            @foreach($demandes as $key => $demande)
            <?php
            $facture = '';
            if($demande->facture)
              $facture = $demande->facture;
            else if(!$demande->facture || $demande->facture ==  '')
              $facture = '##';
            ?>
            <tr>
                <td id="cmd_id{{$key}}" style="display: none">{{$demande->id}}</td>
                <td>{{$demande->code}}</td>
                <td>{{$demande->date}}</td>
                <td>{{$demande->fournisseur->nom_fournisseur}}</td>
                <td>{{$demande->facture}}</td>
                <td id="total{{$key}}">{{$demande->total}}</td>
                <td id="avance1{{$key}}">{{$demande->avance}}</td>
                <td id="reste1{{$key}}">{{$demande->reste}}</td>
                <td id="avance2{{$key}}">
                  <!-- 0.00 -->
                  <input type="number" 
                    min="0" 
                    step="0.01" 
                    style="width: 50%" 
                    value="0.00" 
                    onclick="setAvances('{{$demande->id}}')" 
                    onkeyup="setAvances('{{$demande->id}}')">
                </td>
                <td id="reste2{{$key}}">{{$demande->reste}}</td>
                <td id="status{{$key}}">NR</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            @php
              $total = 0;
              $avance = 0;
              $reste = 0;
              foreach($demandes as $demande){
                $total += $demande->total;
                $avance += $demande->avance;
                $reste += $demande->reste;
              }
            @endphp
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th></th>
              <th id="ftotal">{{number_format($total, 2, '.', '')}}</th>
              <th id="favance1">{{number_format($avance, 2, '.', '')}}</th>
              <th id="freste1">{{number_format($reste, 2, '.', '')}}</th>
              <th id="favance2"></th>
              <th id="freste2"></th>
              <th></th>
            </tr>
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
    var fournisseur=$('#fournisseur');
    var table = $('#table');
    var tbody = table.find('tbody');
    var tfoot = table.find('tfoot');
  // -----------End Déclaration des variables--------------//
  $(document).ready(function(){
    // -----------Change Select_fournisseur--------------//
    $(document).on('change','#fournisseur',function(){
      $fournisseur = $(this).val();
      if($fournisseur == "")
        // getPayements();
        getPayements3();
      else
        // getPayements($fournisseur);
        getPayements3($fournisseur);
      // --------------
    });
    // -----------End Select_fournisseur--------------//
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
      if(fournisseur.val() == "") {
        // Swal.fire("Veuillez choisir un fournisseur");
        message('warning','',"Veuillez choisir un fournisseur");
        return;
      }
      var fournisseur_id = parseInt(fournisseur.val());
      //--->Envoi des information via method POST
      var _token=$('input[name=_token]'); 
      // var _token = $('meta[name="csrf-token"]').attr('content');
      // ***** BEGIN variables lignes ******** //
      var list = tbody.find('tr');
      // :: :: :: TRAITEMENT :: :: :: //
      var val1 = $('#avance').val();
      var val2 = 0;
      for (let i = 0; i < list.length; i++) {
        var n = $('#avance2'+i).find('input').val();
        val2 = val2 + parseFloat(n);
      }
      if(parseFloat(val1) != parseFloat(val2)) {
        // Swal.fire("Merci de vérifier le solde d'avance !");
        message('warning','',"Merci de vérifier le solde d'avance !");
        return;
      }
      $('#valider').prop('disabled',true);
      $('#loading').prop('style','display : block');
      // :: :: :: TRAITEMENT :: :: :: //
      var array = [];
      for (let i = 0; i < list.length; i++) {
        // var cmd_id = list.eq(i).find('td').eq(0).html();
        var cmd_id = $('#cmd_id'+i).html();
        // var avance = list.eq(i).find('td').eq(7).find('input').val();
        var avance = $('#avance2'+i).find('input').val();
        var navance = parseFloat(avance);
        // var reste = list.eq(i).find('td').eq(8).html();
        var reste = $('#reste2'+i).html();
        // var status = list.eq(i).find('td').eq(9).html();
        var status = $('#status'+i).html();
        if(navance > 0){
          var obj = {
                "cmd_id":parseInt(cmd_id),
                "avance":navance,
                "reste":parseFloat(reste),
                "status":status,
              };
          array = [...array,obj];
        }
      }
      // ***** END variables lignes ******** //
      $.ajax({
        type:'post',
        url:"{!!URL::to('storePayements')!!}",
        data:{
          _token : _token.val(),
          // _token : _token,
          date : date.val(),
          mode:mode.val(),
          fournisseur : fournisseur_id,
          lignes : array,
        },
        success: function(data){
          Swal.fire(data.message);
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
  calculs();
  // --------------
  getTfoot();
  // --------------
  // test();
  function test(){}
  function search(){ }
  function sommeTotal(){
    var list = tbody.find('tr');
    var ntotal = 0;
    for (let index = 0; index < list.length; index++) {
      // var ligne = list.eq(index).find('td');
      // var total = parseFloat(ligne.eq(4).html());
      var total = parseFloat($('#total'+index).html());
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
      var avance = parseFloat($('#avance1'+index).html());
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
      var avance = parseFloat($('#avance2'+index).find('input').val());
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
      var reste = parseFloat($('#reste1'+index).html());
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
      var reste = parseFloat($('#reste2'+index).html());
      nreste += reste;
    }
    return nreste;
  }
  // Permet de calculer Le total des restes
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
      var reste_cmd = $('#reste1'+index);
      var nreste_cmd = parseFloat(reste_cmd.html());
      // var av = ligne.eq(7);
      var av = $('#avance2'+index);
      // var res = ligne.eq(8);
      var res = $('#reste2'+index);
      // var stat = ligne.eq(9);
      var stat = $('#status'+index);
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
      var cmd_id = $('#cmd_id'+i).html();
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
    var reste_cmd = $('#reste1'+index);
    var nreste_cmd = parseFloat(reste_cmd.html());
    // --------------
    // var av = ligne.eq(7);
    var av = $('#avance2'+index);
    // var res = ligne.eq(8);
    var res = $('#reste2'+index);
    // var stat = ligne.eq(9);
    var stat = $('#status'+index);
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
  function getPayements3(param){
    $.ajax({
      type:'get',
      url:"{!!URL::to('getPayements3')!!}",
      // url:'{!!Route('payement.getPayements3')!!}',
      data:{'fournisseur':param},
      success:function(data){
          // console.log(data);
          // return;
          var table = $('#table');
          table.find('tbody').html("");
          var lignes = '';
          data.forEach((ligne,index) => {
            (ligne.facture == null || ligne.facture == '') ? facture='':facture=ligne.facture;
            lignes+=`<tr>
                      <td id="cmd_id${index}" style="display: none">${ligne.id}</td>
                      <td>${ligne.code}</td>
                      <td>${ligne.date}</td>
                      <td>${ligne.fournisseur.nom_fournisseur}</td>
                      <td>${facture}</td>
                      <td id="total${index}">${parseFloat(ligne.total).toFixed(2)}</td>
                      <td id="avance1${index}">${parseFloat(ligne.avance).toFixed(2)}</td>
                      <td id="reste1${index}">${parseFloat(ligne.reste).toFixed(2)}</td>
                      <td id="avance2${index}">
                        <input type="number" min="0" step="0.01" style="width: 50%" value="0.00" onclick="setAvances(${ligne.id})" onkeyup="setAvances(${ligne.id})">
                      </td>
                      <td id="reste2${index}">${parseFloat(ligne.reste).toFixed(2)}</td>
                      <td id="status${index}">NR</td>
                    </tr>`;
          });
          table.find('tbody').append(lignes);
          // ------------------------------------------------
          // table.find('tfoot').html("");
          // total = 0;
          // avance = 0;
          // reste = 0;
          // data.forEach(ligne => {
          //   total += parseFloat(ligne.total);
          //   avance += parseFloat(ligne.avance);
          //   reste += parseFloat(ligne.reste);
          // });
          // var ligne = '';
          // ligne+=`<tr>
          //           <th></th>
          //           <th></th>
          //           <th></th>
          //           <th></th>
          //           <th>${total.toFixed(2)}</th>
          //           <th>${avance.toFixed(2)}</th>
          //           <th>${reste.toFixed(2)}</th>
          //           <th></th>
          //           <th></th>
          //           <th></th>
          //         </tr>`;
          // table.find('tfoot').append(ligne);
          // ------------------------------------------------
          calculs();
          getTfoot();
      },
      error:function(){
        console.log([]);    
      }
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
<!-- ##################################################################### -->
@endsection