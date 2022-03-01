<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table">
        <thead class="bg-primary text-white">
            <tr>
                <!-- <th>#</th> -->
                <th>Rèf</th>
                <th>Client</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $i = 0 ;
            @endphp
            @foreach($clients as $client)
            <tr>
                <!-- <td>{{++$i}}</td> -->
                <td>{{$client->code}}</td>
                <td>{{$client->nom_client}}</td>
                <td>
                    @if($client->adresse)
                        {{substr($client->adresse,0,25)}}...
                    @endif
                </td>
                <td>{{$client->telephone}}</td>
                <td>
                    @if(hasPermssion('show1') == 'yes') 
                    <a href="{{ action('ClientController@show',['client'=> $client->id])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                    @endif
                    @if(hasPermssion('edit1') == 'yes') 
                    <a href="{{route('client.edit',['client'=> $client->id])}}"class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(hasPermssion('delete1') == 'yes') 
                    <button class="btn btn-outline-danger btn-sm remove-client" 
                    data-id="{{ $client->id }}" 
                    data-action="{{ route('client.destroy',$client->id) }}"> 
                    <i class="fas fa-trash"></i>
                    </button>
                    @endif                        
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$clients->links()}}
</div>