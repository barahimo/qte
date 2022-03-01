  <ul>
    @foreach($data as $key=>$o)
    <li>{{$o->date}} | {{$data->firstItem()+$key}}</li>
    @endforeach
  </ul>
  {{ $data->links() }}