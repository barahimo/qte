@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Mouvements</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Mouvements</li>
  </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
  <!-- Main card -->
  <div class="card">
      <div class="card-body">
          {{-- ---------------- --}}
          <!-- Begin Dates  -->
          <div class="row">
            <div class="col-2"></div>
              <div class="col-4">
              <label for="date1">Date de début :</label>
              <input type="date" class="form-control" name="date1" id="date1" placeholder="date1" value={{$date}}>
              {{-- <input type="date" class="form-control" name="date1" id="date1" placeholder="date1" value="2021-08-01"> --}}
            </div> 
            <div class="col-4">
              <label for="date2">Date de fin :</label>
              <input type="date" class="form-control" name="date2" id="date2" placeholder="date2" value={{$date}}>
              {{-- <input type="date" class="form-control" name="date2" id="date2" placeholder="date2" value="2021-09-01"> --}}
            </div> 
            <div class="col-2">
              <div class="text-right">
                {{-- @if(in_array('print7',$permission) || Auth::user()->is_admin == 2) --}}
                @if(hasPermssion('print7') == 'yes') 
                <button onclick="onprint()" class="btn btn-outline-primary"><i class="fa fa-print"></i></button>
                @endif
              </div>
            </div> 
          </div>
          <!-- End Dates  -->
          <br>
          {{-- ---------------- --}}
          <!-- Begin Mouvements  -->
          <div id="content">
            <h5 class="card-title text-center" id="title">Les mouvements :</h5>
            <div class="table-responsive">
              <table class="table table-striped" id="table" >
                <thead class="bg-primary text-white">
                  <tr class="text-center">
                      <th>Date</th>
                      <th>Client</th>
                      <th>Nature</th>
                      <th>N° de pièce</th>
                      <th>Débit</th>
                      <th>Crédit</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr class="text-right">
                    <th colspan="4">Totaux :</th>
                    <th></th>
                    <th></th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
          <!-- End Mouvements  -->
      </div>
  </div>
</div>
<!-- /.content -->
<!-- #########################################################" -->
<div id="display" style="display : none">
  <div id="mypdf">
    <div class="row">
      <div class="col-6">
        <div class="text-left" id="logo">
          @if($company && ($company->logo || $company->logo != null))
          <img src="{{Storage::url($company->logo ?? null)}}"  alt="logo" style="width:80px;height:80px" class="img-fluid">
          @else
          <img src="{{asset('images/image.png')}}" alt="Logo" style="width:120px">
          @endif
        </div>
      </div>
      <div class="col-6">
        <div class="text-right">
          <br><br>
          <h5>Le : {{$date->isoFormat('DD/MM/YYYY')}}</h5>
        </div>
      </div>
    </div>
    <div id="pdf">
    </div>
  </div>
</div>
{{-- ############################################### --}}

<!-- ##################################################################### -->
{{-- <script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/html2canvas.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    getBalance();
    // test();
    $(document).on('change','#date1',function(){
      getBalance();
    });
    $(document).on('change','#date2',function(){
      getBalance();
    });
  });
  function format_date(date){
    today = new Date(date);
    var dd = today.getDate();
    var mm = today.getMonth() + 1;
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd;
    }
    if (mm < 10) {
        mm = '0' + mm;
    }
    var today = dd + '/' + mm + '/' + yyyy;
    return today;
  }
  function getBalance(){
    var date1 = $('#date1').val();
    var date2 = $('#date2').val();
    $('#title').html(`Les mouvements : de ${format_date(date1)} à ${format_date(date2)}`);
    $.ajax({
        type:'get',
        url:'{{Route('commande.getBalance')}}',
        data:{
          'from' : date1,
          'to' : date2,
        },
        success: function(res){
          var table = $('#table');
          table.find('tbody').html("");
          table.find('tfoot').html("");
          var lignes = '';
          var debit = 0;
          var credit = 0;
          var commandes = res.commandes;
          var reglements = res.reglements;
          commandes.forEach((ligne,i) => {
              debit += parseFloat(ligne.total); 
              lignes+=`<tr class="text-center">
                      <td>${format_date(ligne.date)}</td>
                      <td class="text-left">${ligne.client.nom_client}</td>
                      <td>BON</td>
                      <td>${ligne.code}</td>
                      <td class="text-right">${parseFloat(ligne.total).toFixed(2)} DH</td>
                      <td>-</td>
                  </tr>`;
          });
          reglements.forEach((ligne,i) => {
              if(ligne.avance>0){
                credit += parseFloat(ligne.avance); 
                lignes+=`<tr class="text-center">
                        <td>${format_date(ligne.date)}</td>
                        <td class="text-left">${ligne.commande.client.nom_client}</td>
                        <td>REGLEMENT</td>
                        <td>${ligne.code}</td>
                        <td>-</td>
                        <td class="text-right">${parseFloat(ligne.avance).toFixed(2)} DH</td>
                    </tr>`;
              }
          });
          table.find('tbody').append(lignes);
          var foot = `<tr class="text-right">
                        <th colspan="4">Totaux :</th>
                        <th>${parseFloat(debit).toFixed(2)} DH</th>
                        <th>${parseFloat(credit).toFixed(2)} DH</th>
                    </tr>`
          table.find('tfoot').append(foot);
        },
        error:function(err){
            Swal.fire("Erreur !");
        },
    });
  }
  // ######################################################## //
  function test() {
    var table = $('#table');
    table.find('tbody').html("");
    table.find('tfoot').html("");
    var lignes = '';
    var debit = 0;
    var credit = 0;
    for (let index = 0; index < 17; index++) {
      lignes+=`<tr class="text-center">
              <td>31/06/2021</td>
              <td class="text-left">"nom_client"</td>
              <td>BON</td>
              <td>BON-2109-0010</td>
              <td class="text-right">600 DH</td>
              <td>-</td>
          </tr>`;
    }
    for (let index = 0; index < 30; index++) {
      lignes+=`<tr class="text-center">
            <td>30/06/2021</td>
            <td class="text-left">"nom_client"</td>
            <td>REGLEMENT</td>
            <td>REG-2109-0005</td>
            <td>-</td>
            <td class="text-right">500 DH</td>
        </tr>`;
    }
    table.find('tbody').append(lignes);
    var foot = `<tr class="text-right">
                  <th colspan="4">Totaux :</th>
                  <th>1000 DH</th>
                  <th>2000 DH</th>
              </tr>`
    table.find('tfoot').append(foot);
  }
  function mycontent(paginate){
    var date1 = $('#date1').val();
    var date2 = $('#date2').val();
    var table = $('#table');
    var row = table.find('tbody').find('tr');
    var ligne = '';
    ligne += `<h5 class="card-title text-center" id="title">Les mouvements : de ${format_date(date1)} à ${format_date(date2)}</h5>`;
    if(row.length > 0){
      var dim = row.length; 
      var begin = 0;
      var end = paginate - 3;
      if(dim <= end){
        end = dim;
      }
      var page = 0;
      var change = false;
      // ################################################
      ligne += `<table class="table table-striped">`;
      ligne += `<thead>${table.find('thead').html()}</thead>`;
      ligne += `<tbody>`;
      for (let index = begin; index < end; index++) {
          ligne += `<tr class="text-center">${row.eq(index).html()}</tr>`;
      }
      ligne += `</tbody>`;
      if(end == dim){
        ligne += `<tfoot>${table.find('tfoot').html()}</tfoot>`
      }
      ligne += `</table>`;
      ligne += `<div class="text-right"><span>Page : ${page+1}<span><div>`;
      begin = end;
      dim -= end;
      end += paginate;
      page += 1;
      if(dim>0){
        ligne += `<div class="html2pdf__page-break"></div>`;
        change = true;
      }
      // ################################################
      while (dim >= paginate) {    
        ligne += `<table class="table table-striped">`;
        ligne += `<thead>${table.find('thead').html()}</thead>`;
        ligne += `<tbody>`;
        for (let index = begin; index < end; index++) {
            ligne += `<tr class="text-center">${row.eq(index).html()}</tr>`;
        }
        ligne += `</tbody>`;
        ligne += `</table>`;
        ligne += `<div class="text-right"><span>Page : ${page+1}<span><div>`;
        begin = end;
        end += paginate;
        dim -= paginate;
        page += 1;
        // if(dim>0){}
        ligne += `<div class="html2pdf__page-break"></div>`;
      }
      end = begin+dim;
      if(change){
        ligne += `<table class="table table-striped">`;
        ligne += `<thead>${table.find('thead').html()}</thead>`;
        ligne += `<tbody>`;
        for (let index = begin; index < end; index++) {
          ligne += `<tr class="text-center">${row.eq(index).html()}</tr>`;
        }
        ligne += `</tbody>`;
        ligne += `<tfoot>${table.find('tfoot').html()}</tfoot>`;
        ligne += `</table>`;
        ligne += `<div class="text-right"><span>Page : ${page+1}<span><div>`;
      }
    }
    return ligne;
  }
  function onprint(){
    var date1 = $('#date1').val();
    var date2 = $('#date2').val();
    var content = mycontent(21);
    // console.log(content);
    // return ;
    $('#pdf').html(content);
    var style = `
        margin-left: auto;
        margin-right: auto;
        font-size:12px;
    `;
    $('#pdf').prop('style',style);
    var element = document.querySelector("#mypdf");
    html2pdf(element, {
      margin:       10,
      filename:     `balance[${date1}][${date2}].pdf`,
      // image:        { type: 'jpeg', quality: 0.98 },
      image:        { type: 'jpeg', quality: 1 },
      html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
      jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
    });
  }
  // ############################################### //
  function autre_onprint(){
    // -------- declarartion des jsPDF and html2canvas ------------//
    window.html2canvas = html2canvas;
    window.jsPDF = window.jspdf.jsPDF;
    // -------- Change Style ------------//
    $('#pdf').html($('#content').html());
    var style = `
        height: 800px;
        width: 550px;
        margin-left: auto;
        margin-right: auto;
        font-size:8px;
    `;
    $('#mypdf').prop('style',style);
    // -------- Initialization de doc ------------//
    var doc = new jsPDF("p", "pt", "a4",true);
    // -------------------------------------------
    // doc.page=1; // use this as a counter.
    var pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
    var pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
    doc.text("text1", pageWidth / 2, pageHeight  - 50, {align: 'center'});
    doc.addPage();
    doc.text("text2", pageWidth / 2, pageHeight  - 50, {align: 'center'});
    doc.setPage($('#pdf').html());
    // -------------------------------------------
    // -------- html to pdf ------------//
    doc.html(document.querySelector("#mypdf"), {
        callback: function (doc) {
            doc.save("balance.pdf");
        },
        x: 20,
        y: 20,
    });
  }
</script>
<!-- ##################################################################### -->
@endsection