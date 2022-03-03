@extends('layout.dashboard')
@section('contenu')
<div class="content-header sty-one">
<h1>Import / Export Clients</h1>
<ol class="breadcrumb">
    <li><a href="{{route('app.home')}}">Home</a></li>
    <li><i class="fa fa-angle-right"></i> Clients</li>
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
                <form action="{{ route('files.clientImport') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group mb-4" style="max-width: 500px; margin: 0 auto;">
                        <div class="custom-file text-left">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choisissez Fichier</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="importdata()">Importer fichier</button>
                    <a class="btn btn-success" href="{{ route('files.clientExport') }}"  onclick="exportdata()">Exporter fichier</a>
                </form>
                <script>
                    function importdata(){
                        $('#loading').prop('style','display : none');
                        $('#loading').prop('style','display : block');
                    }
                    function exportdata(){
                        $('#loading').prop('style','display : block');
                        setTimeout(() => {
                            // window.location.assign("{{route('files.studentExcel')}}")
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