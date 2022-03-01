@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
{{-- ################## --}}
<!-- Content Header (Page header) -->
<div class="content-header sty-one">
    <h1>Panneau des utilisateurs</h1>
    <ol class="breadcrumb">
        <li><a href="{{route('app.home')}}">Home</a></li>
        <li><i class="fa fa-angle-right"></i> Utilisateurs</li>
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
                    {{-- @if(in_array('create8',$permission) || Auth::user()->is_admin == 2) --}}
                    @if(hasPermssion('create8') == 'yes') 
                    <a href="{{route('user.create')}}" class="btn btn-primary m-b-10 "><i class="fa fa-user-circle"></i>  Utilisateur</a>
                    @endif
                </div>
                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-8">
                    
                </div>
                <div class="col-xl-3 col-lg-3 col-md-2 col-sm-2"></div>
            </div>
            <!-- search form --> 
            <br>
            {{-- ---------------- --}}
            <div id="users_data">
                @include('managements.users.index_data')
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
            fetch_user(page);
        });
        
        function fetch_user(page){
            $.ajax({
                type:'GET',
                url:"{{route('user.fetch_user')}}" + "?page=" + page,
                success:function(data){
                    $('#users_data').html(data);
                },
                error:function(){
                    console.log([]);    
                }
            });
        }
    });
    $("body").on("click",".remove-user",function(){
        var current_object = $(this);
        var is_admin = "{{Auth::user()->is_admin}}";
        var msg = "";
        (is_admin == 2) ? msg = "Un utilisateur et ses composants sont sur le point d'être détruite" : msg = "Un utilisateur est sur le point d'être détruite";
        Swal.fire({
            title: msg,
            text: "Est-ce que vous êtes d'accord ?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Annuler',
            confirmButtonText: 'Oui, supprimez-le!'
        }).then((result) => {
            if (result.isConfirmed) {
                var action = current_object.attr('data-action');
                var token = jQuery('meta[name="csrf-token"]').attr('content');
                var id = current_object.attr('data-id');
                $('body').html("<form class='form-inline remove-form' method='post' action='"+action+"'></form>");
                $('body').find('.remove-form').append('<input name="_method" type="hidden" value="delete">');
                $('body').find('.remove-form').append('<input name="_token" type="hidden" value="'+token+'">');
                $('body').find('.remove-form').append('<input name="id" type="hidden" value="'+id+'">');
                $('body').find('.remove-form').submit();
            }
        })
    });
</script>
@endsection