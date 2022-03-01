<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table_paginate">
        <thead class="bg-primary text-white">
            <tr>
                <th>Rèf</th>
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
            @foreach($factures_paginate as $facture)
                <tr>
                    <td>{{$facture->code}}</td>
                    <td>{{$facture->commande->code}}</td>
                    <td>{{$facture->date}}</td>
                    <td>{{$facture->commande->client->nom_client}}</td>
                    <td>{{number_format($facture->total_HT,2, '.', '')}}</td>
                    <td>{{number_format($facture->total_TVA,2, '.', '')}}</td>
                    <td>{{number_format($facture->total_TTC,2, '.', '')}}</td>
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
    </table>
    {{$factures_paginate->links()}}
</div>