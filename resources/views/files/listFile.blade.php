@php
use function App\Providers\getNameLayout;
@endphp
@extends(getNameLayout())
@section('content')

<div class="row">
    <div class="col-4 mx-auto">
        <table style="text-align: center;">
            <tr>
                <th><i class="fas fa-chalkboard-teacher"></i></th>
                <th><i class="fas fa-newspaper"></i></th>
                <th><i class="fas fa-users"></i></th>
            </tr>
            <tr>
                <td style="padding: 10px; margin: 5px;">
                    <span>{{$teacher->firstName}} {{$teacher->lastName}}</span>
                </td>
                <td style="padding: 10px; margin: 5px;">
                    <span>{{$subject->libelle}}</span>
                </td>
                <td style="padding: 10px; margin: 5px;">
                    <span>{{$classroom->libelle}}</span>
                </td>
            </tr>
        </table>
    </div>
</div>
<table class="table table-striped text-center  table-responsive">
    <thead class="thead-dark">
        <tr>
            <th scope="col" width="25%">{{ucfirst(__('date'))}}</th>
            <th scope="col" width="50%">{{ucfirst(__('title'))}}</th>
            <th scope="col" width="25%">{{ucfirst(__('show'))}}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lessons as $index => $lesson)
        <tr>
            <td scope="row">{{$lesson->date}}</td>
            <td>{{$lesson->title}}</td>
            <td>
                <a href="{{route('files.viewFile',['title' => $lesson->title,'created_at' => $lesson->created_at])}}">
                    <i class="fas fa-eye fa-2x"></i>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection