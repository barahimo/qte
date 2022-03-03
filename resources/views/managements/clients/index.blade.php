@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Panneau des clients</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Clients</li>
    </ol>
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <!-- Main card -->
    <div class="card">
        <div class="card-body">
            {{-- ---------------- --}}
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2">
                    @if(hasPermssion('create1') == 'yes') 
                    <a href="{{route('client.create')}}" class="btn btn-primary m-b-10 "><i class="fa fa-user-plus"></i>  Client</a>
                    @endif
                </div>
                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-8">
                    <form action="" method="get" class="search-form">
                        <div class="input-group">
                            <input name="q" class="form-control" placeholder="chercher..." type="text">
                            <span class="input-group-btn">
                                <button type="submit" name="search" id="search-btn" class="btn btn-info btn-flat"><i class="fa fa-search"></i> </button>
                            </span>
                        </div>
                    </form>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2"></div>
            </div>
            <!-- search form --> 
            <br>
            {{-- ---------------- --}}
            <div id="clients_data">
                @include('managements.clients.index_data')
            </div>
        </div>
    </div>
</div>
<!-- /.content --> 
{{-- ################## --}}
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click','.pagination a',function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var search = $('input[name=q]').val();
            fetch_client(page,search);
        });
        
        function fetch_client(page,search){
            $.ajax({
                type:'GET',
                url:"{{route('client.fetch_client')}}" + "?page=" + page+ "&search=" + search,
                success:function(data){
                    $('#clients_data').html(data);
                },
                error:function(){
                    console.log([]);    
                }
            });
        }

        $(document).on('keyup','input[name=q]',function(e){
            fetch_client(1,$(this).val());
        });

        $(document).on('click','button[type=submit]',function(e){
            fetch_client(1,$(this).val());
        });
        // searchClient();
        // $(document).on('keyup','input[name=q]',function(e){
        //     e.preventDefault();
        //     searchClient();
        // });
        // $(document).on('click','button[type=submit]',function(e){
        //     e.preventDefault();
        //     searchClient();
        // });
    });
    function searchClient() {
        $.ajax({
            type:'get',
            url:"{!!Route('client.searchClient')!!}",
            data:{'search':$('input[name=q]').val()},
            success:function(res){
                var lignes = '';
                var table = $('#table');
                table.find('tbody').html("");
                res.forEach((client,i) => {
                    var url_show = "{{action('ClientController@show',['client'=> ':id'])}}".replace(':id', client.id);
                    var url_edit = "{{route('client.edit',['client'=> ':id'])}}".replace(':id', client.id);
                    var url_destroy = "{{ route('client.destroy',':id') }}".replace(':id', client.id);
                    var action = `
                            @if(hasPermssion('show1') == 'yes') 
                            <a href=${url_show} class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a>
                            @endif
                            @if(hasPermssion('edit1') == 'yes') 
                            <a href=${url_edit} class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                            @endif
                            @if(hasPermssion('delete1') == 'yes') 
                            <button class="btn btn-outline-danger btn-sm remove-client" 
                                data-id="${client.id}" 
                                data-action=${url_destroy}> 
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif `;
                    var adresse = '';
                    if(client.adresse != null)
                        adresse = client.adresse.substring(0,25)+'...';
                    lignes += `<tr>
                        <td>${i+1}</td>
                        <td>${client.code}</td>
                        <td>${client.nom_client}</td>
                        <td>${adresse}</td>
                        <td>${client.telephone}</td>
                        <td>${action}</td>
                    </tr>`;
                });
                table.find('tbody').append(lignes);

            },
            error:function(){
                console.log([]);    
            }
        });
    }
    $("body").on("click",".remove-client",function(){
        var current_object = $(this);
        // begin swal2
        Swal.fire({
            title: "Un client est sur le point d'être détruite",
            text: "Est-ce que vous êtes d'accord ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Oui, supprimez-le!'
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
                // Swal.fire(
                // 'Deleted!',
                // 'Your file has been deleted.',
                // 'success'
                // )
            }
        })
    // end swal2
    });
</script>
@endsection


