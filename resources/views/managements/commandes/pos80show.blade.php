@extends('layout.dashboard')
@section('contenu')
<!-- #########################################################" -->
{{ Html::style(asset('css/commandestyle.css')) }}
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Commande</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Commande</li>
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
                                {{-- --------Begin Header----------- --}}
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-6 text-center">
                                        @if($company && ($company->logo || $company->logo != null))
                                            <img src="{{Storage::url($company->logo ?? null)}}"  alt="logo" style="width:80px;height:80px" class="img-fluid">
                                        @else
                                            <img src="{{asset('images/image.png')}}" alt="Logo" style="width:120px">
                                        @endif
                                    </div>
                                    <div class="col"></div>
                                </div>
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-6">
                                        Code client: {{$commande->client->code}} <br>  
                                        Nom client : {{$commande->client->nom_client}} <br>
                                        Télèphone : {{$commande->client->telephone}} <br>  
                                        Adresse : {{$commande->client->adresse}} <br>  
                                        @php
                                            $time = strtotime($commande->date);
                                            $date = date('d/m/Y',$time);
                                        @endphp
                                        Date commande : {{$date}}<br> 
                                    </div>
                                    <div class="col "></div>
                                </div>
                                {{-- --------End Header----------- --}}
                                {{-- --------Begin table----------- --}}
                                <div class="row">
                                    <div class="col"></div>
                                    <div class="col-6">
                                        <table cellspacing="0" cellpadding="0">
                                            <thead>
                                                <tr style="height:10px"></tr>
                                                <tr>
                                                    <th colspan="5" style="text-align:center; background-color:rgb(235, 233, 233);">
                                                        @php
                                                        $list = explode("-",$commande->code);
                                                        $list1 = $list[1];
                                                        $list2 = $list[2];
                                                        $code = $list1.'-'.$list2;
                                                        @endphp
                                                        BON n° : {{$code}}
                                                    </th>
                                                </tr>
                                                <tr style="height:10px"></tr>
                                                <tr style="height:10px; font-size : 7px">
                                                    <th colspan="5">
                                                        @if($commande->oeil_gauche)
                                                        Oeil gauche : {{$commande->oeil_gauche}} &nbsp;&nbsp;&nbsp; 
                                                        @endif
                                                        @if($commande->oeil_droite)
                                                        Oeil droite : {{$commande->oeil_droite}}
                                                        @endif
                                                    </th>
                                                </tr>
                                                <tr style="height:10px; font-size : 8px;">
                                                    <th colspan="5" class="text-right">
                                                        Montants exprimés en Dirham
                                                    </th>
                                                </tr>
                                                <tr class="headerFacture">
                                                    <th style="width:10%" class="text-center">Réf.</th>
                                                    <th style="width:50%" class="text-center">Désignation</th>
                                                    <th style="width:10%" class="text-center">Qté</th>
                                                    <th style="width:15%" class="text-center">PU</th>
                                                    <th style="width:15%" class="text-center">TOTAL</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size : 8px">
                                                @php 
                                                    $TTC = 0;
                                                @endphp
                                                @foreach($lignecommandes as $lignecommande)
                                                @php 
                                                    $prix_unit = $lignecommande->total_produit / $lignecommande->quantite;
                                                    $TTC += $lignecommande->total_produit;
                                                @endphp
                                                <tr class="bodyFacture">
                                                    <td style="width:10%" class="text-left">{{$lignecommande->produit->code_produit}}</td>
                                                    <td style="width:50%" class="text-left">{{$lignecommande->produit->nom_produit}}</td>
                                                    <td style="width:10%" class="text-center">{{$lignecommande->quantite}}</td>
                                                    <td style="width:15%" class="text-right">{{number_format($prix_unit,2)}}</td>
                                                    <td style="width:15%" class="text-right">{{number_format($lignecommande->total_produit,2)}}</td>
                                                </tr>
                                                @endforeach
                                                {{-- --------------------------- --}}
                                                {{-- <tr class="bodyFacture">
                                                    <td style="width:10%" class="text-left">code_produit</td>
                                                    <td style="width:50%" class="text-left">nom_produit</td>
                                                    <td style="width:10%" class="text-center">3</td>
                                                    <td style="width:15%" class="text-right">100</td>
                                                    <td style="width:15%" class="text-right">300</td>
                                                </tr> --}}
                                                {{-- --------------------------- --}}
                                                <tr class="tbody_ligne">
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr class="ttcFacture">
                                                    <td colspan="2" style="border-bottom: none !important"></td>
                                                    <th colspan="2" class="text-right">NET A PAYER</th>
                                                    <th colspan="1" class="text-right">{{number_format($TTC,2)}}</th>
                                                </tr>
                                                <tr style="height:10px;"></tr>
                                                <tr>
                                                    <th></th>
                                                    <th colspan="6">
                                                        @php
                                                        $numberToWords = new NumberToWords\NumberToWords();
                                                        $numberTransformer = $numberToWords->getNumberTransformer('fr');
                                                        // $numberWord1 = $numberTransformer->toWords(number_format($TTC,2)); // outputs "five thousand one hundred twenty"
                                                        $currencyTransformer = $numberToWords->getCurrencyTransformer('fr');
                                                        // $numberWord2 = $currencyTransformer->toWords(number_format($TTC,2)*100,'MAD'); // outputs "five thousand one hundred twenty"
                                                        // --------------------------------------------------------
                                                        $pow9 = pow(10,9);
                                                        $pow6 = pow(10,6);
                                                        $pow3 = pow(10,3);
                                                        // -----------------------------------------
                                                        $msg = '';
                                                        if($TTC>=$pow9){
                                                            $msg = $TTC;
                                                        }
                                                        else{
                                                            $million = intdiv($TTC , $pow6);
                                                            // $mille = intdiv(($TTC % $pow6) , $pow3);
                                                            $mille = intdiv(fmod($TTC , $pow6) , $pow3);
                                                            // $reste = ($TTC % $pow6) % $pow3;
                                                            $reste = fmod($TTC , $pow3);
                                                            if($million != 0){
                                                                $numberWord1 = $numberTransformer->toWords(number_format($million,2)); // outputs "five thousand one hundred twenty"
                                                                $msg .= $numberWord1.' MILLION ';
                                                            }
                                                            if($mille != 0){
                                                                $numberWord1 = $numberTransformer->toWords(number_format($mille,2)); // outputs "five thousand one hundred twenty"
                                                                $msg .= $numberWord1.' MILLE ';
                                                            }
                                                            $numberWord2 = $currencyTransformer->toWords(number_format($reste,2)*100,'MAD'); // outputs "five thousand one hundred twenty"
                                                            $msg .= $numberWord2;
                                                        }
                                                        // --------------------------------------------------------
                                                        @endphp    
                                                        La somme de : {{strtoupper($msg)}} 
                                                    </th>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr style="height:30px"></tr>
                                                <tr style="height: 10px">
                                                    <td colspan="7" class="text-center" style="text-align:center; background-color:rgb(235, 233, 233)">
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
        $('#pdf').find('.tbody_ligne').height(500-height_tbody);
        // console.log('height_tbody : '+height_tbody);
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
                var code = "<?php echo $commande->code;?>";
                    // doc.save("BON"+code+".pdf");
                    doc.save(code+".pdf");
            },
            x: 10,
            y: 10,
        });
    }
</script>
@endsection