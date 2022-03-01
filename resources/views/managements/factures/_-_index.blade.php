@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Panneau de Factures</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Factures</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <!-- Main card -->
    <div class="card">
        <div class="card-body">
            <!-- Begin Dates  -->
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-4">
                <label for="date1">Date de début :</label>
                <input type="date" class="form-control" name="date1" id="date1" placeholder="date1" value={{$dateFrom}}>
                </div> 
                <div class="col-sm-4">
                <label for="date2">Date de fin : </label>
                <input type="date" class="form-control" name="date2" id="date2" placeholder="date2" value={{$date}}>
                </div> 
                <div class="col-sm-2">
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
            <!-- begin search form --> 
            <form action="" method="get" class="search-form">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2">
                        @if(hasPermssion('create5') == 'yes') 
                            <a id="create" href="{{route('facture.create')}}" class="btn btn-primary m-b-10 ">
                                <i class="fa fa-plus"></i>&nbsp;Facture
                            </a>
                        @endif
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-8 col-sm-8">
                        <div class="input-group">
                            <input name="q" class="form-control" placeholder="search..." type="text">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-info btn-flat"><i class="fa fa-search"></i> </button>
                            </span>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2"></div>
                </div>
            </form>
            <!-- end search form --> 
            <br>
            {{-- ---------------- --}}
            <div id="factures_data">
                @include('managements.factures.index_data')
            </div>
            <div class="row" style="font-weight: bold;" id="tfoot_paginate">
                <div class="col-4 text-center">
                    <span>Totaux TTC :</span> 
                    <span>{{number_format($totaux_ttc,2)}} DH</span> 
                </div>
                <div class="col-4 text-center">
                    <span>Totaux TVA :</spant> 
                    <span>{{number_format($totaux_ttc - $totaux_ht,2)}} DH</span> 
                </div>
                <div class="col-4 text-center">
                    <span>Totaux HT :</spant> 
                    <span>{{number_format($totaux_ht,2)}} DH</span> 
                </div>
            </div>
            {{-- -----BEGIN table-responsive----------- --}}
            <div class="table-responsive">
                <table class="table" id="table" style="display : none">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Commande</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Total HT</th>
                            <th>Total TVA</th>
                            <th>Total TTC</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                            <tr>
                                <td>{{$facture->code}}</td>
                                <td>{{$facture->commande->code}}</td>
                                <td>{{$facture->date}}</td>
                                <td>{{$facture->commande->client->nom_client}}</td>
                                <td>{{number_format($facture->total_HT,2)}}</td>
                                <td>{{number_format($facture->total_TVA,2)}}</td>
                                <td>{{number_format($facture->total_TTC,2)}}</td>
                                <td>
                                    @if(hasPermssion('show6') == 'yes') 
                                    <a href="{{ action('FactureController@show',['facture'=> $facture])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                                    @endif
                                    @if(hasPermssion('delete6') == 'yes') 
                                    <button class="btn btn-outline-danger btn-sm remove-facture" 
                                    data-id="{{ $facture->id }}" 
                                    data-action="{{ route('facture.destroy',$facture->id) }}"> 
                                    <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="6">Totaux TTC :</th>
                            <th>{{number_format($totaux_ttc,2)}} DH</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="6">Totaux TVA :</th>
                            <th>{{number_format($totaux_ttc-$totaux_ht,2)}} DH</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="6">Totaux HT :</th>
                            <th>{{number_format($totaux_ht,2)}} DH</th>
                        </tr>
                        </tfoot>
                </table>
                {{-- BEGIN_TABLE_2 --}}
                <table class="table" id="table_print" style="display: none">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>#</th>
                            <th>Commande</th>
                            <th>Date</th>
                            <th>Client</th>
                            <th>Total HT</th>
                            <th>Total TVA</th>
                            <th>Total TTC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($factures as $facture)
                            <tr>
                                <td>{{$facture->code}}</td>
                                <td>{{$facture->commande->code}}</td>
                                <td>{{$facture->date}}</td>
                                <td>{{$facture->commande->client->nom_client}}</td>
                                <td>{{number_format($facture->total_HT,2)}}</td>
                                <td>{{number_format($facture->total_TVA,2)}}</td>
                                <td>{{number_format($facture->total_TTC,2)}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="text-right" colspan="6">Totaux TTC :</th>
                            <th>{{number_format($totaux_ttc,2)}} DH</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="6">Totaux TVA :</th>
                            <th>{{number_format($totaux_ttc-$totaux_ht,2)}} DH</th>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="6">Totaux HT :</th>
                            <th>{{number_format($totaux_ht,2)}} DH</th>
                        </tr>
                    </tfoot>
                </table>
                {{-- END_TABLE_2 --}}
            </div>
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
{{-- ################## --}}
{{-- <script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/html2canvas.min.js') }}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.pagination a',function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var search = $('input[name=q]').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            fetch_facture(page,search,date1,date2);
        });
        
        function fetch_facture(page,search,from,to){
            $.ajax({
                type:'GET',
                url:"{{route('facture.fetch_facture')}}" + "?page=" + page+ "&search=" + search + "&from=" + from+ "&to=" + to,
                success:function(data){
                    $('#factures_data').html(data);
                },
                error:function(){
                    console.log([]);    
                }
            });
        }

        $(document).on('keyup','input[name=q]',function(e){
            e.preventDefault();
            // searchFacture();
            searchFactureWithDate();
            // #### Paginate #### //
            var search = $('input[name=q]').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            fetch_facture(1,search,date1,date2);
            // #### Paginate #### //
        });

        $(document).on('click','button[type=submit]',function(e){
            e.preventDefault();
            // searchFacture();
            searchFactureWithDate();
            // #### Paginate #### //
            var search = $('input[name=q]').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            fetch_facture(1,search,date1,date2);
            // #### Paginate #### //
        });
        // searchFacture();
        // $(document).on('keyup','input[name=q]',function(e){
        //     e.preventDefault();
        //     searchFacture();
        // });
        // $(document).on('click','button[type=submit]',function(e){
        //     e.preventDefault();
        //     searchFacture();
        // });
        $(document).on('change','#date1',function(){
            // getFacture();
            searchFactureWithDate();
            // #### Paginate #### //
            var search = $('input[name=q]').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            fetch_facture(1,search,date1,date2);
            // #### Paginate #### //
        });
        $(document).on('change','#date2',function(){
            // getFacture();
            searchFactureWithDate();
            // #### Paginate #### //
            var search = $('input[name=q]').val();
            var date1 = $('#date1').val();
            var date2 = $('#date2').val();
            fetch_facture(1,search,date1,date2);
            // #### Paginate #### //
        });
    });
    function searchFacture() {
        $.ajax({
            type:'get',
            url:"{!!Route('facture.searchFacture')!!}",
            data:{'search':$('input[name=q]').val()},
            success:function(res){
                getInfos(res);
            },
            error:function(){
                console.log([]);    
            }
        });
    }
    function searchFactureWithDate() {
        $.ajax({
            type:'get',
            url:"{!!Route('facture.searchFactureWithDate')!!}",
            data:{
                'search':$('input[name=q]').val(),
                'from':$('#date1').val(),
                'to':$('#date2').val(),
            },
            success:function(res){
                getInfos(res);
            },
            error:function(){
                console.log([]);    
            }
        });
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
    function getInfos(data){
        var totaux_ttc = 0;
        var totaux_tva = 0;
        var totaux_ht = 0;

        var lignes = '';
        var lignes_print = '';
        var table = $('#table');
        var table_print = $('#table_print');
        var tfoot_paginate = $('#tfoot_paginate');

        table.find('tbody').html("");
        table_print.find('tbody').html("");
        table.find('tfoot').html("");
        table_print.find('tfoot').html("");
        data.forEach((facture,i) => {
            var url_show = "{{ action('FactureController@show',['facture'=> ':id'])}}".replace(':id', facture.id);
            var url_destroy = "{{ route('facture.destroy',':id')}}".replace(':id', facture.id);   
            var action = `
                @if(hasPermssion('show6') == 'yes') 
                <a href=${url_show} class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                @endif
                @if(hasPermssion('delete6') == 'yes') 
                <button class="btn btn-outline-danger btn-sm remove-facture" 
                data-id="${facture.id}"
                data-action=${url_destroy} > 
                <i class="fas fa-trash"></i>
                </button>
                @endif
            `;
            lignes += `<tr>
                <td>${facture.code}</td>
                <td>${facture.commande.code}</td>
                <td>${facture.date}</td>
                <td>${facture.commande.client.nom_client}</td>
                <td>${parseFloat(facture.total_HT).toFixed(2)}</td>
                <td>${parseFloat(facture.total_TVA).toFixed(2)}</td>
                <td>${parseFloat(facture.total_TTC).toFixed(2)}</td>
                <td>${action}</td>
            </tr>`;
            lignes_print += `<tr>
                <td>${facture.code}</td>
                <td>${facture.commande.code}</td>
                <td>${facture.date}</td>
                <td>${facture.commande.client.nom_client}</td>
                <td>${parseFloat(facture.total_HT).toFixed(2)}</td>
                <td>${parseFloat(facture.total_TVA).toFixed(2)}</td>
                <td>${parseFloat(facture.total_TTC).toFixed(2)}</td>
            </tr>`;
            totaux_ttc += parseFloat(facture.total_TTC); 
            totaux_ht += parseFloat(facture.total_HT); 
        });
        totaux_tva = totaux_ttc - totaux_ht; 
        table.find('tbody').append(lignes);
        // ############################################## //
        // for (let index = 0; index < 100; index++) {
        //     lignes_print += `
        //     <tr>
        //         <td>${index+1}</td>
        //         <td>commande</td>
        //         <td>date</td>
        //         <td>nom_client</td>
        //         <td>total_HT</td>
        //         <td>total_TVA</td>
        //         <td>total_TTC</td>
        //     </tr>
        //     `;            
        // }
        // ############################################## //
        table_print.find('tbody').append(lignes_print);
        var foot = `<tr>
                <th class="text-right" colspan="6">Totaux TTC :</th>
                <th class="text-right">${totaux_ttc.toFixed(2)} DH</th>
            </tr>
            <tr>
                <th class="text-right" colspan="6">Totaux TVA :</th>
                <th class="text-right">${totaux_tva.toFixed(2)} DH</th>
            </tr>
            <tr>
                <th class="text-right" colspan="6">Totaux HT :</th>
                <th class="text-right">${totaux_ht.toFixed(2)} DH</th>
        </tr>`
        table.find('tfoot').append(foot);
        table_print.find('tfoot').append(foot);
        tfoot_paginate.find('div').eq(0).find('span').eq(1).html(totaux_ttc+' DH');
        tfoot_paginate.find('div').eq(1).find('span').eq(1).html(totaux_tva+' DH');
        tfoot_paginate.find('div').eq(2).find('span').eq(1).html(totaux_ht+' DH');
    }
    function getFacture() {
        var date1 = $('#date1').val();
        var date2 = $('#date2').val();
        $.ajax({
            type:'get',
            url:"{!!Route('facture.getFacture')!!}",
            data:{
                'from' : date1,
                'to' : date2,
            },
            success:function(res){
                data = res.factures;
                getInfos(data);
            },
            error:function(){
                console.log([]);    
            }
        });
    }
    $("body").on("click",".remove-facture",function(){
        var current_object = $(this);
       // begin swal2
        Swal.fire({
            title: "Une facture est sur le point d'être détruite",
            text: "Est-ce que vous êtes d'accord ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Oui, supprimez-le!'
        }).then((result) => {
            if (result.isConfirmed) {
            // begin destroy
                var action = current_object.attr('data-action');
                var token = jQuery('meta[name="csrf-token"]').attr('content');
                var id = current_object.attr('data-id');
                $('body').html("<form class='form-inline remove-form' method='post' action='"+action+"'></form>");
                $('body').find('.remove-form').append('<input name="_method" type="hidden" value="delete">');
                $('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+token+'">');
                $('body').find('.remove-form').append('<input name="id" type="hidden" value="'+id+'">');
                $('body').find('.remove-form').submit();
            //end destroy
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // )
            }
        })
        // end swal2
    });
    // ################################
    function mycontent(paginate){
        var date1 = $('#date1').val();
        var date2 = $('#date2').val();
        // var table = $('#table');
        var table = $('#table_print');
        var row = table.find('tbody').find('tr');
        var ligne = '';
        ligne += `<h5 class="card-title text-center" id="title">Les factures : de ${format_date(date1)} à ${format_date(date2)}</h5>`;
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
            filename:     `factures[${date1}][${date2}].pdf`,
            // image:        { type: 'jpeg', quality: 0.98 },
            image:        { type: 'jpeg', quality: 1 },
            html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
            jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
        });
    }
    // ################################
</script>
@endsection