@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
<!-- #########################################################" -->
{{ Html::style(asset('css/facturestyle.css')) }}
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Facture</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Facture</li>
    </ol>
</div>
{{-- ################## --}}
<br>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="row" style="text-align : right">
                <div class="col-6 text-center">
                    <i class="fas fa-arrow-circle-left fa-3x" onclick="window.location.assign('{{route('facture.index')}}')"></i>
                </div>
                <br>
                <div class="col-6 text-center">
                    {{-- @if(in_array('print6',$permission) || Auth::user()->is_admin == 2) --}}
                    @if(hasPermssion('print6') == 'yes') 
                    <button onclick="onprint()" class="btn btn-outline-primary">Imprimer <i class="fa fa-print"></i></button>
                    @endif
                </div>
            </div>
            <div id="content">
                <div class="align-center" style="display: flex;align-items: center;justify-content: center;">
                    <div class="card border border-white" style="margin-top:20px;">
                        <div class="card-body" > 
                            <div id="contenu" class="text-black">
                                <div class="row">
                                    <div class="col-6">
                                        @if($company && ($company->logo || $company->logo != null))
                                            <img src="{{Storage::url($company->logo ?? null)}}"  alt="logo" style="width:80px;height:80px" class="img-fluid">
                                        @else
                                            <img src="{{asset('images/image.png')}}" alt="Logo" style="width:120px">
                                        @endif
                                    </div>
                                    <div class="col-6">
                                        Code client: {{$commande->client->code}} <br>  
                                        Nom Client : {{$commande->client->nom_client}} <br>
                                        Télèphone : {{$commande->client->telephone}} <br>  
                                        Adresse : {{$commande->client->adresse}} <br>  
                                        @php
                                            $time = strtotime($facture->date);
                                            $date = date('d/m/Y',$time);
                                        @endphp
                                        Date facture : {{$date}}<br> 
                                    </div>
                                </div>
                                <table>
                                    <thead>
                                        <tr style="height:10px"></tr>
                                        <tr >
                                            <th colspan="7" style="text-align:center; background-color:rgb(235, 233, 233);">
                                                Facture N° : {{$facture->code}}
                                            </th>
                                        </tr>
                                        <tr style="height:10px"></tr>
                                        <tr style="height:10px; font-size : 7px">
                                            <th colspan="7">
                                                @if($commande->oeil_gauche)
                                                Oeil gauche : {{$commande->oeil_gauche}} &nbsp;&nbsp;&nbsp; 
                                                @endif
                                                @if($commande->oeil_droite)
                                                Oeil droite : {{$commande->oeil_droite}}
                                                @endif
                                            </th>
                                        </tr>
                                        <tr style="height:10px; font-size : 8px;">
                                            <th colspan="7" class="text-right">
                                                Montants exprimés en Dirham
                                            </th>
                                        </tr>
                                        <tr class="headerFacture">
                                            <th style="width:6%" class="text-center">Réf.</th>
                                            <th style="width:45%" class="text-center">Désignation</th>
                                            <th style="width:5%" class="text-center">Qté</th>
                                            <th style="width:12%" class="text-center">PU. HT</th>
                                            <th style="width:5%" class="text-center">TVA</th>
                                            <th style="width:12%" class="text-center">MT. HT</th>
                                            <th style="width:15%" class="text-center">MT. TTC</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size : 8px">
                                        @php 
                                            $TTC = 0;
                                            $HT = 0;
                                        @endphp
                                        @foreach($lignecommandes as $lignecommande)
                                        @php 
                                            $montant_HT = $lignecommande->total_produit / (1 + $lignecommande->produit->TVA/100);
                                            $prix_unit_HT = $montant_HT / $lignecommande->quantite;
                                            $HT += $montant_HT;
                                            $TTC += $lignecommande->total_produit;
                                        @endphp
                                        <tr class="bodyFacture">
                                            <td style="width:10%;" class="text-left">{{$lignecommande->produit->code_produit}}</td>
                                            <td style="width:45%;" class="text-left">{{$lignecommande->produit->nom_produit}}</td>
                                            <td style="width:5%;" class="text-center">{{$lignecommande->quantite}}</td>
                                            <td style="width:10%;" class="text-right">{{number_format($prix_unit_HT,2)}}</td>
                                            <td style="width:5%;" class="text-center">{{$lignecommande->produit->TVA}}</td>
                                            <td style="width:10%;" class="text-right">{{number_format($montant_HT,2)}}</td>
                                            <td style="width:15%;" class="text-right">{{number_format($lignecommande->total_produit,2)}}</td>
                                        </tr>
                                        @endforeach
                                        {{-- --------------------------- --}}
                                        {{-- <tr class="bodyFacture">
                                            <td style="width:10%;" class="text-left">code_produit</td>
                                            <td style="width:45%;" class="text-left">nom_produit</td>
                                            <td style="width:5%;" class="text-center">2</td>
                                            <td style="width:10%;" class="text-right">100</td>
                                            <td style="width:5%;" class="text-center">20</td>
                                            <td style="width:10%;" class="text-right">200</td>
                                            <td style="width:15%;" class="text-right">240</td>
                                        </tr> --}}
                                        {{-- --------------------------- --}}
                                        <tr class="tbody_ligne">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @php 
                                        $TVA = $TTC - $HT;
                                        @endphp
                                        <tr class="htFacture">
                                            <td colspan="4" style="border-bottom: none !important"></td>
                                            <th colspan="2" class="text-right">Total HT :</th>
                                            <th colspan="1" class="text-right">{{number_format($HT,2)}}</td>
                                        </tr>
                                        <tr class="tvaFacture">
                                            <td colspan="4" style="border-bottom: 0px solid red"></td>
                                            <th colspan="2" class="text-right">Total TVA :</th>
                                            <th colspan="1" class="text-right">{{number_format($TVA,2)}}</th>
                                        </tr>
                                        <tr class="ttcFacture">
                                            <td colspan="4" style="border-bottom: none !important"></td>
                                            <th colspan="2" class="text-right">Total TTC :</th>
                                            <th colspan="1" class="text-right">{{number_format($TTC,2)}}</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="6">
                                                Arrêté la présente facture à la somme : 
                                            </th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th colspan="6">
                                                @php
                                                $numberToWords = new NumberToWords\NumberToWords();
                                                $numberTransformer = $numberToWords->getNumberTransformer('fr');
                                                $currencyTransformer = $numberToWords->getCurrencyTransformer('fr');
                                                // $numberWord = $numberTransformer->toWords(number_format($TTC,2)); // outputs "five thousand one hundred twenty"
                                                // $numberWord = $currencyTransformer->toWords(number_format($TTC,2)*100,'MAD'); // outputs "five thousand one hundred twenty"
                                                // --------------------------------------------------------
                                                $pow9 = pow(10,9);
                                                $pow6 = pow(10,6);
                                                $pow3 = pow(10,3);
                                                $msg = '';
                                                if($TTC>=$pow9){
                                                    $msg = $TTC;
                                                }
                                                else {
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
                                                {{strtoupper($msg)}} 
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
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</div>
<!-- #########################################################" -->
<div id="display" style="display : none">
    <div id="pdf"></div>
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
        dimensionTBODY();
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
                var code = "<?php echo $facture->code;?>";
                doc.save(code+".pdf");
            },
            x: 10,
            y: 10,
        });
    }
</script>
@endsection