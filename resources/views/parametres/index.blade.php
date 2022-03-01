@extends('layout.dashboard')
@section('contenu')
<!-- ##################################################################### -->
<div class="container">
    @php
        if($count>0)
            $route = route('company.edit',['company'=>$company->id]);
        else
            $route = route('company.create');
    @endphp
    <!-- <a href="{{$route}}" class="btn btn-info">Configuration</a> -->
    <br>
    <a href="{{route('company.edit',['company'=>$company->id])}}" class="btn btn-info">Edit</a>
    <br>
    <a href="{{route('company.create')}}" class="btn btn-info">Create</a>
    <br>
    <a href="{{route('company.form')}}" class="btn btn-info">FORM</a>
    <br>
    <h1>this is view company</h1>
    <button id="test">TEST</button>
</div>
<!-- ################################################################ -->
<script type="text/javascript">
    $(document).ready(function(){
        // $('#search-input').on('keyup',function(){
        $(document).on('click','#test',function(){
            $.ajax({
                type : 'get',
                // url: '{!!URL::to('/hello')!!}',
                url: '/hello',
                // url : '/Patient.search',
                // data:{'search':search},
                success:function(data){
                    console.log(data.hello);
                    // console.log(data);
                // $('tbody').html(data);
                }
            })
            
        });
        $(document).on('keyup','#search-input',function(){
            var search=$(this).val();
            // console.log(search);
            $.ajax({
                type : 'get',
                url: 'http://localhost:8000/hello',
                // url: {!! URL::to('hello') !!},
                // url : '/Patient.search',
                data:{'search':search},
                success:function(data){
                    console.log(data.hello);
                // $('tbody').html(data);
                }
            })
        });
    });
</script>
@endsection
