@php
use function App\Providers\getNameLayout;
@endphp
@extends(getNameLayout())
@section('content')
<!-- Icons font CSS-->
<link href="{{asset('/colorlib/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">
<link href="{{asset('/colorlib/vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
<!-- Font special for pages-->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<!-- Vendor CSS-->
<link href="{{asset('/colorlib/vendor/select2/select2.min.css')}}" rel="stylesheet" media="all">
<link href="{{asset('/colorlib/vendor/datepicker/daterangepicker.css')}}" rel="stylesheet" media="all">
<!-- Main CSS-->
<link href="{{asset('/colorlib/css/main.css')}}" rel="stylesheet" media="all">
<!-- Jquery JS-->
<script src="{{asset('/colorlib/vendor/jquery/jquery.min.js')}}"></script>
<!-- Vendor JS-->
<script src="{{asset('/colorlib/vendor/select2/select2.min.js')}}"></script>
<script src="{{asset('/colorlib/vendor/datepicker/moment.min.js')}}"></script>
<script src="{{asset('/colorlib/vendor/datepicker/daterangepicker.js')}}"></script>
<!-- Main JS-->
<script src="{{asset('/colorlib/js/global.js')}}"></script>
<!-- Textarea -->
<!-- <link rel="stylesheet" type="text/css" href="{{asset('/textarea/samples.css')}}" />
<script src="{{ asset('/textarea/ckeditor.js') }}"></script>
<script src="{{ asset('/textarea/sample.js') }}"></script> -->

<link rel="stylesheet" type="text/css" href="{{asset('/textarea/ckeditor/samples/css/samples.css')}}" />
<script src="{{ asset('/textarea/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('/textarea/ckeditor/samples/js/sample.js') }}"></script>

<div class="container">
    <div class="row">
        <div class="col-4 mx-auto">
            <table style="text-align: center;">
                <tr>
                    <th>
                        <i class="fas fa-chalkboard-teacher"></i>
                    </th>
                    <th>
                        <i class="fas fa-newspaper"></i>
                    </th>
                    <th>
                        <i class="fas fa-users"></i>
                    </th>
                </tr>
                <tr>
                    <td style="padding: 10px; margin: 5px;">
                        <span>{{$teacher->firstName}} {{$teacher->lastName}}</span>
                    </td>
                    <td style="padding: 10px; margin: 5px;">
                        <span>{{$subject->libelle}}</span>
                    </td>
                    <td style="padding: 10px; margin: 5px;">
                        <a href="{{route('classrooms.show',$classroom->id)}}">{{$classroom->libelle}}</a>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<main class="py-4">
    <div class="wrapper wrapper--w790">
        <div class="card card-5">
            <div class="card-heading">
                <h2 class="title">{{ucfirst(__('message'))}}</h2>
            </div>
            <div class="card-body">
                <form method="post" action="{{route('files.storeFile')}}" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="teacher_id" value="{{$teacher->id}}" hidden>
                    <input type="text" name="subject_id" value="{{$subject->id}}" hidden>
                    <input type="text" name="classroom_id" value="{{$classroom->id}}" hidden>
                    <div class="form-row">
                        <div class="name">{{ucfirst(__('title'))}}</div>
                        <div class="value">
                            <div class="input-group">
                                <input class="input--style-5" type="text" name="title">
                            </div>
                        </div>
                    </div>
                    <div>
                        <textarea class="" id="text" name="text" cols="200" rows="50"></textarea>
                        <script>
                            CKEDITOR.replace('text');
                        </script>
                    </div>
                    <br>
                    <div class="form-row">
                        <div class="name">{{ucfirst(__('date'))}}</div>
                        <div class="">
                            <div class="input-group">
                                <input class="input--style-5" type="date" id="date" name="date" value="{{now()->format('Y-m-d')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="name">{{ucfirst(__('files'))}}</div>
                        <div class="value">
                            <div class="input-group">
                                <input class="input--style-5" type="file" name="filename[]" multiple>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn--radius-2 btn--blue btnAdd" type="button">+ {{ucfirst(__('link'))}}</button>
                    </div>
                    <hr>
                    <p class="incrementLink" style="background-color: yellow; text-align: center;"></p>
                    <div class="cloneLink d-none">
                        <div class="boxLink">
                            <div class="form-group">
                                <button class="btn btn-danger btn-sm removeLink" type="button">- {{ucfirst(__('link'))}}</button>
                            </div>
                            <div class="form-group">
                                <input class="form-control link" type="text" name="link[]" placeholder="{{ucfirst(__('link'))}}">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div>
                        <button class="btn btn--radius-2 btn--green" type="submit">{{ucfirst(__('submit'))}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $(".btnAdd").click(function() {
            var box = $(".cloneLink").html();
            $(".incrementLink").after(box);
        });
        $("body").on("click", ".removeLink", function() {
            $(this).parents(".boxLink").remove();
        });
    });
</script>

@endsection