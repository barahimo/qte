<?php
    use function App\Providers\hasPermssion;
?>
<div class="table-responsive">
    <table class="table" id="table">
        <thead class="bg-primary text-white">
            <tr>
                <th>#</th>
                <th>Nom</th>
                <th>Email</th>
                {{-- <th>Type</th> --}}
                <th>Type</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @php 
            $i = 0 ;
            @endphp
            @foreach($users as $user)
            <tr>
                <td>{{++$i}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                {{-- <td>
                    @if($user->is_admin == 0)
                    User
                    @endif
                    @if($user->is_admin == 1)
                    Admin
                    @endif
                </td> --}}
                <td>
                    @if($user->status == 0)
                    InActive
                    @endif
                    @if($user->status == 1)
                    Active
                    @endif
                </td>
                <td>
                    {{-- <a href="{{ action('UserController@show',['user'=> $user->id])}}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-info"></i></a> --}}
                    {{-- @if( Auth::user()->is_admin ) --}}
                    {{-- @if(in_array('edit8',$permission) || Auth::user()->is_admin == 2) --}}
                    @if(hasPermssion('edit8') == 'yes') 
                    <a href="{{route('user.edit',['user'=> $user->id])}}" class="btn btn-outline-success btn-sm"><i class="fas fa-edit"></i></a>
                    @endif
                    {{-- @if(in_array('delete8',$permission) || Auth::user()->is_admin == 2) --}}
                    @if(hasPermssion('delete8') == 'yes') 
                    <button class="btn btn-outline-danger btn-sm remove-user" 
                        data-id="{{ $user->id }}" 
                        data-action="{{ route('user.destroy',$user->id) }}"> 
                        <i class="fas fa-trash"></i>
                    </button>
                    @endif
                    {{-- @endif --}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{$users->links()}}
</div>