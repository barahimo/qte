@extends('layout.dashboard')
@section('contenu')
<!-- #########################################################" -->
{{ Html::style(asset('css/reglementstyle.css')) }}
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Reglement</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Reglement</li>
    </ol>
</div>
{{-- ################## --}}
<br>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row" style="text-align : right">
                <div class="col-6 text-center">
                    <i class="fas fa-arrow-circle-left fa-3x" onclick="window.location.assign('{{route('commande.index')}}')"></i>
                </div>
                <br>
                <div class="col-6 text-center">
                    <button onclick="onprint()" class="btn btn-outline-primary"><i class="fa fa-print"></i></button>
                </div>
            </div>
            <div id="content">
                <div class="align-center" style="display: flex;align-items: center;justify-content: center;">
                    <div class="card border border-white" style="margin-top:20px;">
                        <div class="card-body">
                            <div id="contenu" class="text-black">
                                {{-- --------Begin header----------- --}}
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-6">
                                        <div class="row">
                                            <div class="col-6">
                                                @if($company && ($company->logo || $company->logo != null))
                                                <img src="{{Storage::url($company->logo ?? null)}}"  alt="logo" style="width:80px;height:80px" class="img-fluid">
                                                @else
                                                <img src="{{asset('images/image.png')}}" alt="Logo" style="width:120px">
                                                @endif
                                            </div>
                                            <div class="col-6 text-right">
                                                @php
                                                $time = strtotime($reglement->date);
                                                $date = date('d/m/Y',$time);
                                                @endphp
                                                Le, {{$date}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"></div>
                                </div>
                                {{-- --------End header----------- --}}
                                {{-- --------Begin table----------- --}}
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-6">
                                        <table cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr style="height:10px"></tr>
                                                <tr>
                                                    <th colspan="2" style="text-align:center">
                                                        RECEPISSE DE REGLEMENT
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" style="text-align:center; background-color:rgb(235, 233, 233);">
                                                        @php
                                                        $list = explode("-",$reglement->code);
                                                        $list1 = $list[1];
                                                        $list2 = $list[2];
                                                        $code = $list1.'-'.$list2;
                                                        @endphp
                                                        Reçu n° : {{$code}}
                                                    </th>
                                                </tr>
                                                <tr style="height:10px"></tr>
                                            </thead>
                                            <tbody>
                                                <!-- Client -->
                                                <tr>
                                                    <td class="text-left" colspan="2">
                                                        <span class="font-weight-bold">Client : </span>
                                                        {{$reglement->commande->client->code}} | {{$reglement->commande->client->nom_client}}
                                                    </td>
                                                </tr>
                                                <!-- Code Comande -->
                                                <tr>
                                                    <td class="text-left"colspan="2">
                                                        <span class="font-weight-bold">Commande : </span>
                                                        {{$reglement->commande->code}}
                                                    </td>
                                                </tr>
                                                <tr style="height:15px"></tr>
                                                <!-- Total à payer -->
                                                <tr>
                                                    <th  class="text-left">Total à payer :</th>
                                                    <td  class="text-right">{{number_format($reglement->commande->total, 2)}} dh</td>
                                                </tr>
                                                <tr style="height:5px"></tr>
                                                <!-- Montant réglé -->
                                                <tr>
                                                    <th  class="text-left">Montant réglé :</th>
                                                    <td  class="text-right">{{number_format($reglement->avance, 2)}} dh</td>
                                                </tr>
                                                <tr style="height:5px"></tr>
                                                <!-- Total des règlements -->
                                                <tr>
                                                    <th  class="text-left">Total des règlements :</th>
                                                    @php
                                                    $total_reg = $reglement->commande->total - $reglement->reste;
                                                    @endphp
                                                    <td  class="text-right">{{number_format($total_reg, 2)}} dh</td>
                                                </tr>
                                                <tr style="height:5px"></tr>
                                                <!-- Reste à payer -->
                                                <tr>
                                                    <th  class="text-left">Reste à payer :</th>
                                                    <td  class="text-right">{{number_format($reglement->reste, 2)}} dh</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="tbody_ligne" style="height:10px"></tr>
                                                <tr>
                                                    <td colspan="2" class="text-center" style="background-color:rgb(235, 233, 233);">
                                                        {!!$adresse!!}
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <div class="col"></div>
                                </div>
                                {{-- --------End table----------- --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #########################################################" -->
<div id="display" style="display : none">
    <div id="pdf" style="width: 700px"></div>
</div>
<!-- #########################################################" -->
<!-- #########################################################" -->
{{-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.1/html2canvas.min.js" integrity="sha512-Ki6BxhTDkeY2+bERO2RGKOGh6zvje2DxN3zPsNg4XhJGhkXiVXxIi1rkHUeZgZrf+5voBQJErceuCHtCCMuqTw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
<script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/html2canvas.min.js') }}"></script>
<script type="application/javascript">
    function dimensionTBODY(){
        // var tbody = $('#display').find('#pdf').find('table').find('tbody');
        var tbody = $('#contenu').find('table').find('tbody');
        var height_tbody = tbody.outerHeight();
        // var lignes = tbody.find('tr');
        // tbody_ligne = lignes.eq(lignes.length - 6);
        // tbody_ligne.height(300-height_tbody);
        // $('#pdf').find('.tbody_ligne').height(300-height_tbody);
        $('#pdf').find('.tbody_ligne').height(550-height_tbody);
        console.log('height_tbody : '+height_tbody);
        // var height_tbody = $('#display').find('table').find('tbody').outerHeight();
        // $('#display').find('.tbody_ligne').height(480-height_tbody);
        // var height_tbody = $('table').find('tbody').outerHeight();
        // $('.tbody_ligne').height(500-height_tbody);
        // console.log(document.getElementById('tr1').offsetHeight);
        // console.log(document.getElementById('tr2').offsetHeight);
    }
    function onprint(){
        // -------- declarartion des jsPDF and html2canvas ------------//
        window.html2canvas = html2canvas;
        window.jsPDF = window.jspdf.jsPDF;
        // -------- Change Style ------------//
        $('#pdf').html($('#content').html());
        // dimensionTBODY();
        // $('#pdf').prop('style','height: 700px;width: 500px;margin-left: auto;margin-right: auto;');
            // height: 800px;
            // width: 550px;
            // height: 780px;
            // width: 580px;
        var style = `
            margin-left: auto;
            margin-right: auto;
            font-size:10px;
            font-family: Arial, Helvetica, sans-serif;
        `;
        $('#pdf').prop('style',style);
        // -------- Initialization de doc ------------//
        var doc = new jsPDF("p", "pt", "a4",true);
        // -------- html to pdf ------------//
        // -------- Footer ------------//
        // -------------- //
        // var foot1 = `Siège social : --------------`;
        // var foot2 = `Téléphone : --------`;
        // var foot3 = `I.F. :--------`;
        // doc.setFontSize(10);//optional
        // var pageHeight = doc.internal.pageSize.height || doc.internal.pageSize.getHeight();
        // var pageWidth = doc.internal.pageSize.width || doc.internal.pageSize.getWidth();
        // -------------- //
        // doc.text(foot1, pageWidth / 2, pageHeight  - 50, {align: 'center'});
        // doc.text(foot2, pageWidth / 2, pageHeight  - 35, {align: 'center'});
        // doc.text(foot3, pageWidth / 2, pageHeight  - 20, {align: 'center'});
        // -------- Footer ------------//
        doc.html(document.querySelector("#pdf"), {
            callback: function (doc) {
                var code = "<?php echo $reglement->code;?>";
                // doc.save("REG-"+code+".pdf");
                doc.save(code+".pdf");
            },
            x: 10,
            y: 10,
        });
    }
</script>
@endsection