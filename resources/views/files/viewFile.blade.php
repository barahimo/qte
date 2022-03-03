@php
use function App\Providers\getNameLayout;
@endphp
@extends(getNameLayout())
@section('content')
<div class="row">
    <div class="col-lg-6">
        <h1>{{ucfirst(__('message'))}}</h1>
        <div class="form-group">
            {{ucfirst(__('date'))}} :
            <div style="background-color: white; color: black; border: 1px solid black; padding: 10px; border-radius: 10px;">
                <span class="pl-3">{{$lessons[0]->date}}</span>
            </div>
        </div>
        <div class="form-group">
            {{ucfirst(__('title'))}} :
            <div style="background-color: white; color: black; border: 1px solid black; padding: 10px; border-radius: 10px;">
                <span class="pl-3">{{$lessons[0]->title}}</span>
            </div>
        </div>
        <div class="form-group">
            {{ucfirst(__('text'))}} :
            <div style="background-color: white; color: black; border: 1px solid black; padding: 10px; border-radius: 10px;">
                {!!$lessons[0]->text!!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        @if($countFile>0)
        <h1>{{ucfirst(__('files'))}}</h1>
        <table class="table table-striped text-center">
            @foreach($lessons as $lesson)
            @if($lesson->type != "link")
            <tr>
                <td>
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
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @endif
            @endforeach
        </table>
        @endif
        @if($countLink>0)
        <h1>{{ucfirst(__('links'))}}</h1>
        <ol>
            @foreach($lessons as $lesson)
            @if($lesson->type == "link")
            <li>
                @if(preg_match('/(http|https)/', $lesson->path, $matches))
                <a href="{{$lesson->path}}" target="_block">{{$lesson->path}}</a>
                @else
                {{$lesson->path}}
                @endif
            </li>
            @endif
            @endforeach
        </ol>
        @endif
    </div>
</div>


@endsection