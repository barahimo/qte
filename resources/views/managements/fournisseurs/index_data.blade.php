<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table">
        <thead class="bg-primary text-white">
            <tr>
                <!-- <th>#</th> -->
                <th>Rèf</th>
                <th>Fournisseur</th>
                <th>Adresse</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $i = 0 ;
            @endphp
            @foreach($fournisseurs as $fournisseur)
            <tr>
                <!-- <td>{{++$i}}</td> -->
                <td>{{$fournisseur->code}}</td>
                <td>{{$fournisseur->nom_fournisseur}}</td>
                <td>
                    @if($fournisseur->adresse)
                        {{substr($fournisseur->adresse,0,25)}}...
                    @endif
                </td>
                <td>{{$fournisseur->tel}}</td>
                <td>
                    @if(hasPermssion('show1_2') == 'yes') 
                    <a href="{{ action('FournisseurController@show',['fournisseur'=> $fournisseur->id])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                    @endif
                    @if(hasPermssion('edit1_2') == 'yes') 
                    <a href="{{route('fournisseur.edit',['fournisseur'=> $fournisseur->id])}}"class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                    @endif
                    @if(hasPermssion('delete1_2') == 'yes') 
                    <button class="btn btn-outline-danger btn-sm remove-fournisseur" 
                    data-id="{{ $fournisseur->id }}" 
                    data-action="{{ route('fournisseur.destroy',$fournisseur->id) }}"> 
                    <i class="fas fa-trash"></i>
                    </button>
                    @endif                        
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$fournisseurs->links()}}
</div>