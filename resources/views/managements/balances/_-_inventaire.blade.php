@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
  <h1>Balances d'inventaires</h1>
  <ol class="breadcrumb">
      <li><a href="{{route('app.home')}}">Home</a></li>
      <li><i class="fa fa-angle-right"></i> Inventaire</li>
  </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
  <!-- Main card -->
  <div class="card">
      <div class="card-body">
          {{-- ---------------- --}}
          <!-- Begin Print  -->
          <div class="row">
            <div class="col-sm-12">
              <div class="text-right">
                {{-- @if(in_array('print7',$permission) || Auth::user()->is_admin == 2) --}}
                @if(hasPermssion('print7_2') == 'yes') 
                <button onclick="onprint()" class="btn btn-outline-primary"><i class="fa fa-print"></i></button>
                @endif
              </div>
            </div> 
          </div>
          <!-- End Print  -->
          <!-- Begin Dates  -->
          <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
              <label for="date1">Date de début :</label>
              <input type="date" class="form-control" name="date1" id="date1" placeholder="date1" value={{$dateFrom}}>
            </div> 
            <div class="col-sm-4">
              <label for="date2">Date de fin :</label>
              <input type="date" class="form-control" name="date2" id="date2" placeholder="date2" value={{$date}}>
            </div> 
            <div class="col-sm-2"></div> 
          </div>
          <!-- End Dates  -->
          <!-- Begin Produit  -->
          <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-4">
              <label for="date2">Catégories :</label>
              <select class="form-control" id="category">
                <!-- <option value="0" disabled="true" selected="true">-- Tous les catégories --</option> -->
                <option value="0" selected="true">-- Tous les catégories --</option>
                @foreach($categories as $cat)
                <option value="{{$cat->id}}">{{$cat->nom_categorie}}</option>
                @endforeach
              </select>
            </div> 
            <div class="col-sm-4">
              <label for="date2">Produits :</label>
              <select class="form-control" id="product">
                <option value="0" selected="true">-- Tous les produits --</option>
              </select>
            </div> 
          </div>
          <div class="col-sm-2"></div>
          <!-- End Produit  -->
          <br>
          {{-- ---------------- --}}
          <!-- Begin Inventaire  -->
          <div id="content">
            <h5 class="card-title text-center" id="title">Balances d'inventaires :</h5>
            <input type="hidden" id="type" value="date"/>
            <input type="hidden" id="link" value="date"/>
            <input type="hidden" id="order" value="desc"/>
            <div id="inventaire_data">
                @include('managements.balances.inventaire_data')
            </div>
          </div>
          <!-- End Inventaire  -->
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
    $('#title').html(`Balances d'inventaires : de ${format_date($('#date1').val())} à ${format_date($('#date2').val())}`);
    $(document).on('click','.pagination a',function(event){
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        fetch_inventaire(page,$('#date1').val(),$('#date2').val(),$('#category').val(),$('#product').val(),$('#type').val(),$('#link').val(),$('#order').val());
    });
    // getInventaire();
    // test();
    $(document).on('change','#date1',function(){
      fetch_inventaire(1,$('#date1').val(),$('#date2').val(),$('#category').val(),$('#product').val(),$('#type').val(),$('#link').val(),$('#order').val());
      // getInventaire();
    });
    $(document).on('change','#date2',function(){
      fetch_inventaire(1,$('#date1').val(),$('#date2').val(),$('#category').val(),$('#product').val(),$('#type').val(),$('#link').val(),$('#order').val());
      // getInventaire();
    });
    // -----------Change Category--------------//
    $(document).on('change','#category',function(){
      fetch_inventaire(1,$('#date1').val(),$('#date2').val(),$('#category').val(),0,$('#type').val(),$('#link').val(),$('#order').val());
      var cat_id=$(this).val();
      var product=$('#product');
      $.ajax({
        type:'get',
        url:"{!!Route('balance.productsCategoryBalance')!!}",
        data:{'id':cat_id},
        success:function(data){
          var options = '<option value="0" selected="true">-- Tous les produits --</option>';
          if(data.length>0){
            for(var i=0;i<data.length;i++){
              options+=`<option value="${data[i].id}">${data[i].code_produit} | ${data[i].nom_produit.substring(0,15)}... | ${parseFloat(data[i].quantite)}</option>`;
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
      console.log(id);
      fetch_inventaire(1,$('#date1').val(),$('#date2').val(),$('#category').val(),$('#product').val(),$('#type').val(),$('#link').val(),$('#order').val());
      // getInventaire();
    });
    // -----------End Change Product--------------//
  });
  function fetch_inventaire(page,from,to,category_id,produit_id,type,link,order){
      $.ajax({
          type:'GET',
          url:"{{route('balance.fetch_inventaire')}}" + "?page=" + page + "&from=" + from + "&to=" + to+ "&category_id=" + category_id+ "&produit_id=" + produit_id+ "&type=" + type+ "&link=" + link+ "&order=" + order,
          success:function(data){
              $('#inventaire_data').html(data);
              $('#title').html(`Balances d'inventaires : de ${format_date($('#date1').val())} à ${format_date($('#date2').val())}`);
          },
          error:function(){
              console.log([]);
          }
      });
  }
  function sorting(type,link,order){
    $('#type').val(type);
    $('#link').val(link);
    $('#order').val(order);
    fetch_inventaire(1,$('#date1').val(),$('#date2').val(),$('#category').val(),$('#product').val(),$('#type').val(),$('#link').val(),$('#order').val());
  }
  function isSort(param1,param2){
    var link =$('#link');
    var order =$('#order');
    link.val(param1);
    order.val(param2);
    getInventaire();
  }
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
  function getInventaire(){
    var date1 = $('#date1').val();
    var date2 = $('#date2').val();
    var produit_id = $('#product').val();
    var link =$('#link');
    var order =$('#order');
    $('#title').html(`Balances d'inventaires : de ${format_date($('#date1').val())} à ${format_date($('#date2').val())}`);
    $.ajax({
        type:'get',
        url:"{{Route('balance.getInventaire')}}",
        data:{
          'from' : date1,
          'to' : date2,
          'produit_id' : produit_id,
        },
        success: function(res){
          // var data = res.obj.sort(function(a, b){return a.produit_id - b.produit_id});
          function GetSortOrder(prop,order) { 
            return function(a, b) { 
              objA = a[prop];   
              objB = b[prop];   
              if(order == 'desc'){
                objA = b[prop];   
                objB = a[prop]; 
              }
              return objA - objB;
            } 
          }       
          function GetSortOrderString(prop,order) { 
            return function(a, b) { 
              objA = a[prop].toUpperCase();   
              objB = b[prop].toUpperCase();   
              if(order == 'desc'){
                objA = b[prop].toUpperCase();   
                objB = a[prop].toUpperCase(); 
              }
              if (objA > objB) {    
                  return 1;    
              } else if (objA < objB) {    
                  return -1;    
              }    
              return 0;    
            } 
          }
          function GetSortOrderDate(prop,order) { 
            return function(a, b) { 
              objA = new Date(a.date);   
              objB = new Date(b.date);   
              if(order == 'desc'){
                objA = new Date(b.date);   
                objB = new Date(a.date);  
              }
              return objA - objB;  
            } 
          }
          var data = res.obj.sort(GetSortOrderDate(link.val(),order.val()));
          switch(link.val()){
            case 'produit':
              data = res.obj.sort(GetSortOrder('produit_id',order.val()));
              break;
            case 'type':
            data = res.obj.sort(GetSortOrderString(link.val(),order.val()));
              break;
            case 'nom':
              data = res.obj.sort(GetSortOrderString(link.val(),order.val()));
              break;
            case 'prix':
              data = res.obj.sort(GetSortOrder(link.val(),order.val()));
              break;
            case 'quantite':
              data = res.obj.sort(GetSortOrder(link.val(),order.val()));
              break;
            case 'total':
              data = res.obj.sort(GetSortOrder(link.val(),order.val()));
              break;
            default:
              data = res.obj.sort(GetSortOrderString(link.val(),order.val()));
          }
          // #################################################################
          var quantite_entree = res.quantite_entree;
          var quantite_sortie = res.quantite_sortie;
          var total_entree = res.total_entree;
          var total_sortie = res.total_sortie;
          // console.log(data);
          // console.log(`total_entree : ${total_entree} | total_sortie : ${total_sortie} | total_stock : ${total_stock}`);
          var table = $('#table');
          table.find('tbody').html("");
          table.find('tfoot').html("");
          var lignes = '';
          data.forEach((ligne,i) => {
              (ligne.type == 'Sortie' ) ? style = "color : red" : style = "color : green";
              lignes+=`<tr class="text-center" style="${style}">
                      <td>${format_date(ligne.date)}</td>
                      <td class="text-left">${ligne.ref_produit} | ${ligne.nom_produit.substring(0,15)}...</td>
                      <td>${ligne.type}</td>
                      <td class="text-left">${ligne.nom}</td>
                      <td class="text-left">${parseFloat(ligne.prix).toFixed(2)} DH</td>
                      <td class="text-left">${ligne.quantite}</td>
                      <td class="text-left">${parseFloat(ligne.total).toFixed(2)} DH</td>
                  </tr>`;
          });
          table.find('tbody').append(lignes);
          var foot = `<tr>
                        <th class="text-right" colspan="6">Quantité entrée :</th>
                        <th class="text-left">${quantite_entree}</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="6">Quantité sortie :</th>
                        <th class="text-left">${quantite_sortie}</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="6">Recettes :</th>
                        <th class="text-left">${total_entree} DH</th>
                    </tr>
                    <tr>
                        <th class="text-right" colspan="6">Dépenses :</th>
                        <th class="text-left">${total_sortie} DH</th>
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
    ligne += `<h5 class="card-title text-center" id="title">Balances d'inventaires : de ${format_date(date1)} à ${format_date(date2)}</h5>`;
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
      ligne += `<table class="table table-striped table-bordered">`;
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
        ligne += `<table class="table table-striped table-bordered">`;
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
        ligne += `<table class="table table-striped table-bordered">`;
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
      filename:     `inventaire[${date1}][${date2}].pdf`,
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