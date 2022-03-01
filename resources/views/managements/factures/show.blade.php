@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
    $test = 0;
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
                    <?php $route = route('facture.index');?>
                    <i class="fas fa-arrow-circle-left fa-3x" onclick="window.location.assign('{{$route}}')"></i>
                </div>
                <br>
                <div class="col-6 text-center">
                    {{-- @if(in_array('print6',$permission) || Auth::user()->is_admin == 2) --}}
                    @if(hasPermssion('print6') == 'yes') 
                    <button onclick="onprint()" class="btn btn-outline-primary">Imprimer <i class="fa fa-print"></i></button>
                    @endif
                </div>
            </div>
            {{-- <div id="content1" style="display:none"> --}}
            <div id="content1">
                <div class="align-center">
                    <div class="card border border-white" style="margin-top:20px;">
                        <div class="card-body" >
                            <div id="contenu1" class="text-black">
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
                                        <tr style="height:10px;">
                                            <th colspan="7">
                                                <!-- @if($commande->oeil_gauche)
                                                Oeil gauche : {{$commande->oeil_gauche}} &nbsp;&nbsp;&nbsp; 
                                                @endif
                                                @if($commande->oeil_droite)
                                                Oeil droite : {{$commande->oeil_droite}}
                                                @endif -->
                                                <?php
                                                $data_loin = $commande->vision_loin;
                                                $data_pres = $commande->vision_pres;
                                                ?>
                                                <div class="vision_loin">
                                                    <table>
                                                        <tr>
                                                            <th>Vision de loin</th>
                                                            <th>Sphère</th>
                                                            <th>Cylindre</th>
                                                            <th>Axe</th>
                                                            <th>Lentille</th>
                                                            <th>Eip</th>
                                                            <th>Hauteur</th>
                                                        </tr>
                                                        @if($data_loin)
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->sphere_droite_loin))
                                                                {{json_decode($data_loin,false)->sphere_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->cylindre_droite_loin))
                                                                {{json_decode($data_loin,false)->cylindre_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->axe_droite_loin))
                                                                {{json_decode($data_loin,false)->axe_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->lentille_droite_loin))
                                                                {{json_decode($data_loin,false)->lentille_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->eip_droite_loin))
                                                                {{json_decode($data_loin,false)->eip_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->hauteur_droite_loin))
                                                                {{json_decode($data_loin,false)->hauteur_droite_loin}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->sphere_gauche_loin))
                                                                {{json_decode($data_loin,false)->sphere_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->cylindre_gauche_loin))
                                                                {{json_decode($data_loin,false)->cylindre_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->axe_gauche_loin))
                                                                {{json_decode($data_loin,false)->axe_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->lentille_gauche_loin))
                                                                {{json_decode($data_loin,false)->lentille_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->eip_gauche_loin))
                                                                {{json_decode($data_loin,false)->eip_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->hauteur_gauche_loin))
                                                                {{json_decode($data_loin,false)->hauteur_gauche_loin}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                                <br>
                                                <div class="vision_pres">
                                                    <table>
                                                        <tr>
                                                            <th>Vision de près</th>
                                                            <th>Sphère</th>
                                                            <th>Cylindre</th>
                                                            <th>Axe</th>
                                                            <th>Lentille</th>
                                                            <th>Eip</th>
                                                            <th>Hauteur</th>
                                                        </tr>
                                                        @if($data_pres)
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->sphere_droite_pres))
                                                                {{json_decode($data_pres,false)->sphere_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->cylindre_droite_pres))
                                                                {{json_decode($data_pres,false)->cylindre_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->axe_droite_pres))
                                                                {{json_decode($data_pres,false)->axe_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->lentille_droite_pres))
                                                                {{json_decode($data_pres,false)->lentille_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->eip_droite_pres))
                                                                {{json_decode($data_pres,false)->eip_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->hauteur_droite_pres))
                                                                {{json_decode($data_pres,false)->hauteur_droite_pres}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->sphere_gauche_pres))
                                                                {{json_decode($data_pres,false)->sphere_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->cylindre_gauche_pres))
                                                                {{json_decode($data_pres,false)->cylindre_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->axe_gauche_pres))
                                                                {{json_decode($data_pres,false)->axe_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->lentille_gauche_pres))
                                                                {{json_decode($data_pres,false)->lentille_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->eip_gauche_pres))
                                                                {{json_decode($data_pres,false)->eip_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->hauteur_gauche_pres))
                                                                {{json_decode($data_pres,false)->hauteur_gauche_pres}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                            </th>
                                        </tr>
                                        <tr style="height:10px;">
                                            <th colspan="7" class="text-right">
                                                Montants exprimés en Dirham
                                            </th>
                                        </tr>
                                        <tr class="headerFacture1">
                                            <th style="width:6%" class="text-center">Réf.</th>
                                            <th style="width:45%" class="text-center">Désignation</th>
                                            <th style="width:5%" class="text-center">Qté</th>
                                            <th style="width:12%" class="text-center">PU. HT</th>
                                            <th style="width:5%" class="text-center">TVA</th>
                                            <th style="width:12%" class="text-center">MT. HT</th>
                                            <th style="width:15%" class="text-center">MT. TTC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                        <tr class="bodyFacture1">
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
                                        {{-- <tr class="bodyFacture1">
                                            <td style="width:10%;" class="text-left">code_produit</td>
                                            <td style="width:45%;" class="text-left">nom_produit</td>
                                            <td style="width:5%;" class="text-center">2</td>
                                            <td style="width:10%;" class="text-right">100</td>
                                            <td style="width:5%;" class="text-center">20</td>
                                            <td style="width:10%;" class="text-right">200</td>
                                            <td style="width:15%;" class="text-right">240</td>
                                        </tr> --}}
                                        {{-- --------------------------- --}}
                                        @php
                                        for ($i=0; $i < $test; $i++) { 
                                        @endphp
                                            <tr class="bodyFacture1">
                                                <td style="width:10%;" class="text-left">{{$i+1}}</td>
                                                <td style="width:45%;" class="text-left">nom_produit{{$i+1}}</td>
                                                <td style="width:5%;" class="text-center">2</td>
                                                <td style="width:10%;" class="text-right">100</td>
                                                <td style="width:5%;" class="text-center">20</td>
                                                <td style="width:10%;" class="text-right">200</td>
                                                <td style="width:15%;" class="text-right">240</td>
                                        </tr>
                                        @php
                                        }   
                                        @endphp
                                        {{-- <tr class="tbody_ligne1" style="height : 200px"> --}}
                                        <tr class="tbody_ligne1">
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
                                        <tr class="htFacture1">
                                            <td colspan="4" style="border-bottom: none !important"></td>
                                            <th colspan="2" class="text-right">Total HT :</th>
                                            <th colspan="1" class="text-right">{{number_format($HT,2)}}</td>
                                        </tr>
                                        <tr class="tvaFacture1">
                                            <td colspan="4" style="border-bottom: 0px solid red"></td>
                                            <th colspan="2" class="text-right">Total TVA :</th>
                                            <th colspan="1" class="text-right">{{number_format($TVA,2)}}</th>
                                        </tr>
                                        <tr class="ttcFacture1">
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
            <div id="content" style="display:none">
            {{-- <div id="content"> --}}
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
                                <table id="facture_print">
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
                                                <!-- @if($commande->oeil_gauche)
                                                Oeil gauche : {{$commande->oeil_gauche}} &nbsp;&nbsp;&nbsp; 
                                                @endif
                                                @if($commande->oeil_droite)
                                                Oeil droite : {{$commande->oeil_droite}}
                                                @endif -->
                                                <?php
                                                $data_loin = $commande->vision_loin;
                                                $data_pres = $commande->vision_pres;
                                                ?>
                                                <div class="vision_loin1">
                                                    <table>
                                                        <tr>
                                                            <th>Vision de loin</th>
                                                            <th>Sphère</th>
                                                            <th>Cylindre</th>
                                                            <th>Axe</th>
                                                            <th>Lentille</th>
                                                            <th>Eip</th>
                                                            <th>Hauteur</th>
                                                        </tr>
                                                        @if($data_loin)
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->sphere_droite_loin))
                                                                {{json_decode($data_loin,false)->sphere_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->cylindre_droite_loin))
                                                                {{json_decode($data_loin,false)->cylindre_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->axe_droite_loin))
                                                                {{json_decode($data_loin,false)->axe_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->lentille_droite_loin))
                                                                {{json_decode($data_loin,false)->lentille_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->eip_droite_loin))
                                                                {{json_decode($data_loin,false)->eip_droite_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->hauteur_droite_loin))
                                                                {{json_decode($data_loin,false)->hauteur_droite_loin}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->sphere_gauche_loin))
                                                                {{json_decode($data_loin,false)->sphere_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->cylindre_gauche_loin))
                                                                {{json_decode($data_loin,false)->cylindre_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->axe_gauche_loin))
                                                                {{json_decode($data_loin,false)->axe_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->lentille_gauche_loin))
                                                                {{json_decode($data_loin,false)->lentille_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->eip_gauche_loin))
                                                                {{json_decode($data_loin,false)->eip_gauche_loin}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_loin,false)->hauteur_gauche_loin))
                                                                {{json_decode($data_loin,false)->hauteur_gauche_loin}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
                                                <br>
                                                <div class="vision_pres1">
                                                    <table>
                                                        <tr>
                                                            <th>Vision de près</th>
                                                            <th>Sphère</th>
                                                            <th>Cylindre</th>
                                                            <th>Axe</th>
                                                            <th>Lentille</th>
                                                            <th>Eip</th>
                                                            <th>Hauteur</th>
                                                        </tr>
                                                        @if($data_pres)
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->sphere_droite_pres))
                                                                {{json_decode($data_pres,false)->sphere_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->cylindre_droite_pres))
                                                                {{json_decode($data_pres,false)->cylindre_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->axe_droite_pres))
                                                                {{json_decode($data_pres,false)->axe_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->lentille_droite_pres))
                                                                {{json_decode($data_pres,false)->lentille_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->eip_droite_pres))
                                                                {{json_decode($data_pres,false)->eip_droite_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->hauteur_droite_pres))
                                                                {{json_decode($data_pres,false)->hauteur_droite_pres}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->sphere_gauche_pres))
                                                                {{json_decode($data_pres,false)->sphere_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->cylindre_gauche_pres))
                                                                {{json_decode($data_pres,false)->cylindre_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->axe_gauche_pres))
                                                                {{json_decode($data_pres,false)->axe_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->lentille_gauche_pres))
                                                                {{json_decode($data_pres,false)->lentille_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->eip_gauche_pres))
                                                                {{json_decode($data_pres,false)->eip_gauche_pres}}
                                                                @endif
                                                            </th>
                                                            <th>
                                                                @if(isset(json_decode($data_pres,false)->hauteur_gauche_pres))
                                                                {{json_decode($data_pres,false)->hauteur_gauche_pres}}
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @else
                                                        <tr>
                                                            <th>Droite</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        <tr>
                                                            <th>Gauche</th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                            <th></th>
                                                        </tr>
                                                        @endif
                                                    </table>
                                                </div>
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
                                        @php
                                        for ($i=0; $i < $test; $i++) { 
                                        @endphp
                                            <tr class="bodyFacture">
                                                <td style="width:10%;" class="text-left">{{$i+1}}</td>
                                                <td style="width:45%;" class="text-left">nom_produit{{$i+1}}</td>
                                                <td style="width:5%;" class="text-center">2</td>
                                                <td style="width:10%;" class="text-right">100</td>
                                                <td style="width:5%;" class="text-center">20</td>
                                                <td style="width:10%;" class="text-right">200</td>
                                                <td style="width:15%;" class="text-right">240</td>
                                        </tr>
                                        @php
                                        }   
                                        @endphp
                                        <tr class="tbody_ligne">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </>
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

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js" integrity="sha512-ToRWKKOvhBSS8EtqSflysM/S7v9bB9V0X3B1+E7xo7XZBEZCPL3VX5SFIp8zxY19r7Sz0svqQVbAOx+QcLQSAQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.0.0/jspdf.plugin.autotable.min.js" integrity="sha512-+kPVF9VdutPIVzDoOsZji3s2YWbOdBFbh7OJhDhj3YcuHPjA2QTuXX/dmbr8zXdk9ReQb+ONZ9kRHn5uopPnWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}
{{-- <script src="https://unpkg.com/jspdf@latest/dist/jspdf.umd.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.3.1/html2canvas.min.js" integrity="sha512-Ki6BxhTDkeY2+bERO2RGKOGh6zvje2DxN3zPsNg4XhJGhkXiVXxIi1rkHUeZgZrf+5voBQJErceuCHtCCMuqTw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

<script src="{{ asset('js/jspdf.min.js') }}"></script>
<script src="{{ asset('js/jspdf.plugin.autotable.min.js') }}"></script>
<script src="{{ asset('js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('js/html2canvas.min.js') }}"></script>

<script type="application/javascript">
    onLoad();
    function onLoad(){
        var tbody = $('#contenu1').find('table').find('tbody');
        var height_tbody = tbody.outerHeight();
        $('#contenu1').find('.tbody_ligne1').height(773-height_tbody);
    }
    function dimensionTBODY(){
        $('#content').prop('style','display : block');
        var tbody = $('#contenu').find('table').find('tbody');
        var height_tbody = tbody.outerHeight();
        // $('#pdf').find('.tbody_ligne').height(500-height_tbody);
        $('#pdf').find('.tbody_ligne').height(350-height_tbody);
        $('#content').prop('style','display : none');
    }
    function onprint(){
        // -------- declarartion des jsPDF and html2canvas ------------//
        window.html2canvas = html2canvas;
        window.jsPDF = window.jspdf.jsPDF;
        // -------- Change Style ------------//
        $('#pdf').html($('#content').html());
        dimensionTBODY();
        var style = `
            margin-left: auto;
            margin-right: auto;
            font-size:10px;
            font-family: Arial, Helvetica, sans-serif;
        `;
        $('#pdf').prop('style',style);
        // -------- Initialization de doc ------------//
        var doc = new jsPDF("p", "pt", "a4",true);
        doc.html(document.querySelector("#pdf"), {
            callback: function (doc) {
                var code = "<?php echo $facture->code;?>";
                //--------------------------------------------//
                // window.open(doc.output('bloburl'), '_blank');
                //--------------------------------------------//
                // window.open(doc.output('dataurlnewwindow'));
                //--------------------------------------------//
                // var blob = doc.output("blob");
                // window.open(URL.createObjectURL(blob));
                //--------------------------------------------//
                // var string = doc.output('datauristring');
                // var iframe = "<iframe width='100%' height='100%' src='" + string + "'></iframe>"
                // var x = window.open();
                // x.document.open();
                // x.document.write(iframe);
                // x.document.close();
                //--------------------------------------------//
                window.open(doc.output('bloburl'),"_blank","top=100,left=200,width=1000,height=500");
                //--------------------------------------------//
                // doc.save(code+".pdf");
            },
            x: 10,
            y: 10,
        });
    }
</script>
@endsection