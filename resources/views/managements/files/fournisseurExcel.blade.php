@extends('layout.dashboard')
@section('contenu')
<?php
    use function App\Providers\hasPermssion;
?>
<div class="content-header sty-one">
<h1>Import / Export Fournisseurs</h1>
<ol class="breadcrumb">
    <li><a href="{{route('app.home')}}">Home</a></li>
    <li><i class="fa fa-angle-right"></i> Fournisseurs</li>
</ol>
</div>
{{-- ################## --}}
{{ Html::style(asset('css/loadingstyle.css')) }}
{{-- ################## --}}
<div style="display:none;" id="loading" class="text-center">
  <img src="{{asset('images/loading.gif')}}" alt="Loading" style="width:200px">
</div>
{{-- ################## --}}
<div class="container">
    <div class="container mt-5 text-center">
        <h2 class="mb-4">
            Importer et exporter Excel dans une base de données
        </h2>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('files.fournisseurImport') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choisissez Fichier</label>
                        </div>
                    </div>
                    @if(hasPermssion('import9_2') == 'yes') 
                    <button type="submit" class="btn btn-primary" onclick="importdata()">Importer fichier</button>
                    @endif
                    @if(hasPermssion('export9_2') == 'yes') 
                    <a class="btn btn-success" href="{{ route('files.fournisseurExport') }}"  onclick="exportdata()">Exporter fichier</a>
                    @endif
                </form>
                <script>
                    function importdata(){
                        $('#loading').prop('style','display : none');
                        $('#loading').prop('style','display : block');
                    }
                    function exportdata(){
                        $('#loading').prop('style','display : block');
                        setTimeout(() => {
                            // window.location.assign("{{route('files.fournisseurExcel')}}")
                            $('#loading').prop('style','display : none');
                        }, 2000);
                    }
                    // Add the following code if you want the name of the file appear on select
                    $(".custom-file-input").on("change", function() {
                        var fileName = $(this).val().split("\\").pop();
                        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
                    });
                </script>
            </div>
        </div>
    </div>
</div>
@endsection