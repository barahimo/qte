@extends('layout.dashboard')
@section('contenu')
<div class="content-header sty-one">
<h1>Import / Export</h1>
<ol class="breadcrumb">
    <li><a href="{{route('app.home')}}">Home</a></li>
    <li><i class="fa fa-angle-right"></i> Import / Export</li>
</ol>
</div>
{{-- ################## --}}
{{ Html::style(asset('css/loadingstyle.css')) }}
{{-- ################## --}}
<div style="display:none;" id="loading" class="text-center">
  <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width:200px">
</div>
{{-- ################## --}}
<!-- Main content -->
<div class="content">
    <!-- Main row -->
    <div class="row">
        {{-- begin Clients --}}
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <a href="{{ route('files.fournisseurExcel') }}">
                <div class="card-body">
                    <div class="m-b-1"> <i class="icon-people f-30 text-blue"></i> </div>
                    <div class="info-widget-text row">
                        <div class="col-sm-7 pull-left"><span>Fournisseurs</span></div>
                        <div class="col-sm-5 pull-right text-right text-blue f-25">
                            <i class="fa fa-file-code-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </a>
            </div>
        </div>
        {{-- end Clients --}}
        {{-- begin Clients --}}
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 m-b-2">
            <div class="card">
            <a href="{{ route('files.clientExcel') }}">
                <div class="card-body">
                    <div class="m-b-1"> <i class="icon-people f-30 text-blue"></i> </div>
                    <div class="info-widget-text row">
                        <div class="col-sm-7 pull-left"><span>Clients</span></div>
                        <div class="col-sm-5 pull-right text-right text-blue f-25">
                            <i class="fa fa-file-code-o" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </a>
            </div>
        </div>
        {{-- end Clients --}}
    </div>
    <!-- Main card -->
</div>
<!-- /.content --> 
@endsection