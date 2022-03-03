@php
use function App\Providers\getNameLayout;
use function App\Providers\confirmRequest;
use Illuminate\Support\Str
@endphp
@extends(getNameLayout())
@section('content')
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
    <table class="table table-striped text-center  table-responsive">
        <thead class="thead-dark">
            <tr>
                <th scope="col" width="15%">{{ucfirst(__('date'))}}</th>
                <th scope="col" width="20%">{{ucfirst(__('title'))}}</th>
                <th scope="col" width="30%">{{ucfirst(__('text'))}}</th>
                <th scope="col" width="30%">{{ucfirst(__('file'))}}</th>
                <th scope="col" width="05%">{{ucfirst(__('action'))}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lessons as $index => $lesson)
            <tr>
                <td scope="row">{{$lesson->date}}</td>
                <td>
                    <a href="{{route('files.viewFile',['title' => $lesson->title,'created_at' => $lesson->created_at])}}" target="_block">
                        {{$lesson->title}}
                    </a>
                </td>
                <td>
                    @if($lesson->text)
                    {!!Str::words($lesson->text, 2)!!}
                    @else
                    <i class="fas fa-minus"></i>
                    @endif
                </td>
                <td>
                    @if($lesson->type)
                    <div class="col-md-4">
                        <div class="thumbnail">
                            @if(in_array($lesson->type,['png','jpg','jpeg']))
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <img src="{{asset('images/image.png')}}" alt="image" style="width:50%" width="10px" height="50px">
                                <div class="caption text-center">
                                    <span class="badge badge-warning">{{$lesson->path}}</span>
                                </div>
                            </a>
                            @elseif(in_array($lesson->type,['mpga','mp3','aac','ogg','ogx']))
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <audio controls src="{{Storage::url($lesson->path)}}">
                                    Your browser does not support the <code>audio</code> element.
                                </audio>
                                <div class="caption text-center">
                                    <span class="badge badge-warning">{{$lesson->path}}</span>
                                </div>
                            </a>
                            @elseif(in_array($lesson->type,['mp4']))
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <img src="{{asset('images/video.png')}}" alt="image" style="width:50%" width="10px" height="50px">
                                <div class="caption text-center">
                                    <span class="badge badge-warning">{{$lesson->path}}</span>
                                </div>
                            </a>
                            @elseif(in_array($lesson->type,['pdf']))
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <img src="{{asset('images/PDF.png')}}" alt="image" style="width:50%" width="10px" height="50px">
                                <div class="caption text-center">
                                    <span class="badge badge-warning">{{$lesson->path}}</span>
                                </div>
                            </a>
                            @elseif(in_array($lesson->type,['doc','docx','ppt','pptx','xls','xlsx']))
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <img src="{{asset('images/document.png')}}" alt="image" style="width:50%" width="10px" height="50px">
                                <div class="caption text-center">
                                    <span class="badge badge-warning">{{$lesson->path}}</span>
                                </div>
                            </a>
                            @elseif($lesson->type != "link")
                            <a href="{{Storage::url($lesson->path)}}" target="_block">
                                <span class="badge badge-warning">{{$lesson->path}}</span>
                            </a>
                            @elseif($lesson->type == "link")
                            @if(preg_match('/(http|https)/', $lesson->path))
                            <a href="{{$lesson->path}}" target="_block">{{$lesson->path}}</a>
                            @else
                            {{$lesson->path}}
                            @endif
                            @endif
                        </div>
                    </div>
                    @else
                    <i class="fas fa-minus"></i>
                    @endif
                </td>
                <td>
                    <button class="btn btn-danger btn-flat btn-sm remove-file" data-id="{{ $lesson->id }}" data-action="{{route('files.destroyFile',$lesson->id)}}"> <i class="fa fa-trash"></i></button>
                    {!!confirmRequest(".remove-file", "Are you sure ?", "You will not be able to recover this imaginary file!","error","Delete !","delete")!!}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection