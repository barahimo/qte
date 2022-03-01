@extends('layout.dashboard')

@section('contenu')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="row">
            <div class="col-md-3">
                <h4>Panneau Commandes</h4>
            </div>
            @if(session()->has('status'))
            <script>
                Swal.fire('{{ session('status')}}')
            </script>

            @endif




            <div class="col-md-9 text-right">
                <a href="{{route('commande.index')}}" class="btn btn-primary m-b-10 "><i class="fa fa-plus"> Nouveau Commande</i></a>
            </div> 
            

            <table class="table">
                    
                <tr>
                  <td scope="col">


                      @include('partials.searchcommande')

                  </td>
                  
                </tr>
              
            </table>
            

        </div>
        <div class="card" style="background-color: rgba(241, 241, 241, 0.842)">
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#id</th>
                            {{-- <th>Cadre</th> --}}
                            <th> Date</th>
                            <th> oeil_gauche</th>
                            <th> oeil_droit</th>
                            {{-- <th>avance</th>
                            <th>reste</th> --}}
                            <th>Nom client</th>
                            {{-- <th>id_client</th>
                            <th>total</th> --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($commandes as $commande)
                          <tr>
                              <td>{{$commande->id }}</td>
                              {{-- <td>{{$commande->cadre}}</td> --}}
                              <td>{{$commande->date}}</td>
                              <td>{{$commande->oeil_gauche}}</td>
                              <td>{{$commande->oeil_droite}}</td>
                              {{-- <td>{{$commande->avance}}</td>
                              <td>{{$commande->reste}}</td> --}}
                              <td>{{$commande->nom_client}}</td>

                              <td>
                                 

                                 
                                  <a href="{{ action('CommandeController@show',['commande'=> $commande])}}" class="btn btn-secondary btn-md"><i class="fas fa-info"></i></a>
                                  @if( Auth::user()->is_admin )
                                  <a href="{{route('commande.edit',['commande'=> $commande])}}"class="btn btn-success btn-md"><i class="fas fa-edit"></i></a>
                                  
                                  <button class="btn btn-danger btn-flat btn-md remove-commande" 
                                  data-id="{{ $commande->id }}" 
                                  data-action="{{ route('commande.destroy',$commande->id) }}"> 
                                  <i class="fas fa-trash"></i>
                                 </button>
                                  <a href="{{route('lignecommande.ajoute',['id'=> $commande->id])}}" class="btn btn-primary btn-md "> <i class="fa fa-plus">lignecommande</i></a>
                                  @endif
                                </td>
                         </tr>
                       @endforeach
                   </tbody>
               </table>
           </div>
<script type="text/javascript">
   
   
   $("body").on("click",".remove-commande",function(){
       var current_object = $(this);
       
       Swal.fire({
           title: 'la commande, sa facture et sa règlement seront tout supprimées ',
           text: "vous voulez vraiment la supprimer !",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'oui, je suis sur!'
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
            //    Swal.fire(
            //    'Deleted!',
            //    'Your file has been deleted.',
            //    'success'
            //    )
           }
           })
       // end swal2
       });
           </script>
       </div>
       {{ $commandes->links()}}
        
    </div>
</div>

@endsection

