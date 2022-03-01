<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table">
        <thead class="bg-primary text-white">
            <tr>
                <!-- <th>#</th> -->
                <th>Libelle</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $key=>$categorie)
                <tr>
                    <!-- <td>{{$key+1 }}</td> -->
                    <td>{{$categorie->nom_categorie}}</td>
                    <td>
                        @if($categorie->description)
                        {{substr($categorie->description,0,25)}}...
                        @endif
                    </td>
                    <td>
                        @if(hasPermssion('show2') == 'yes')
                        <a href="{{ action('CategorieController@show',['categorie'=> $categorie])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                        @endif
                        @if(hasPermssion('edit2') == 'yes')
                        <a href="{{route('categorie.edit',['categorie'=> $categorie])}}"class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                        @endif
                        @if(hasPermssion('delete2') == 'yes')
                        <button class="btn btn-outline-danger btn-sm remove-categorie" 
                        data-id="{{ $categorie->id }}" 
                        data-action="{{ route('categorie.destroy',$categorie->id) }}"> 
                        <i class="fas fa-trash"></i>
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{$categories->links()}}
</div>