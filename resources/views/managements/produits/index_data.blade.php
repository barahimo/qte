<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table">
        <thead class="bg-primary text-white">
            <tr>
                <!-- <th>#</th> -->
                <th>Rèf</th>
                <th>Produit</th>
                <th>TVA</th>
                <th>PA HT</th>
                <th>PA TTC</th>
                <th>PV HT</th>
                <th>PV TTC</th>
                <th>Catégorie</th>                            
                <th>Quantité</th>                            
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produits as $key=>$produit)
                <tr>
                    <!-- <td>{{$key+1}}</td> -->
                    <td>{{$produit->code_produit}}</td>
                    <td>{{$produit->nom_produit}}</td>
                    <td>{{$produit->TVA}}%</td>
                    <td>{{number_format($produit->prix_HT,2, '.', '')}}</td>
                    <td>{{number_format($produit->prix_TTC,2, '.', '')}}</td>
                    <td>{{number_format($produit->prix_produit_HT,2, '.', '')}}</td>
                    <td>{{number_format($produit->prix_produit_TTC,2, '.', '')}}</td>
                    <td>{{$produit->categorie->nom_categorie}}</td>
                    @if(number_format($produit->quantite,2, '.', '') > 0 && number_format($produit->quantite,2, '.', '') <= 10)
                    <td style="color : orange">{{number_format($produit->quantite,2, '.', '')}}</td>
                    @elseif(number_format($produit->quantite,2, '.', '') > 10)
                    <td style="color : green">{{number_format($produit->quantite,2, '.', '')}}</td>
                    @else
                    <td style="color : red">{{number_format($produit->quantite,2, '.', '')}}</td>
                    @endif
                    <td>
                        @if(hasPermssion('show3') == 'yes') 
                        <a href="{{ action('ProduitController@show',['produit'=> $produit])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                        @endif
                        @if(hasPermssion('edit3') == 'yes') 
                        <a href="{{route('produit.edit',['produit'=> $produit])}}"class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(hasPermssion('delete3') == 'yes') 
                        <button class="btn btn-outline-danger btn-sm remove-produit" 
                        data-id="{{ $produit->id }}" 
                        data-action="{{ route('produit.destroy',$produit->id) }}"> 
                        <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$produits->links()}}
</div>