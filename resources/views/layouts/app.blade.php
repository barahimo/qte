<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OpticApp - Application</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- v4.0.0-alpha.6 -->
    <link rel="stylesheet" href="{{ asset('dist/bootstrap/css/bootstrap.min.css')}}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo_opticapp_16x16.png')}}">

    <!-- Google Font -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet"> --}}
    <link href="{{ asset('css/poppins.css')}}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/style.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/et-line-font/et-line-font.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/themify-icons/themify-icons.css')}}">
    <link rel="stylesheet" href="{{ asset('dist/css/simple-lineicon/simple-line-icons.css')}}">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <style>
        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="hold-transition login-page">
    @php
    // getPermission
    if(Auth::check()){
        $string = Auth::user()->permission;
        $list = [];
        $array = explode("','",$string);
        foreach ($array as $value) 
            foreach (explode("['",$value) as $val) 
            if($val != '')
                    array_push($list, $val);
                    $array = $list;
                    $list = [];
        foreach ($array as $value) 
        foreach (explode("']",$value) as $val) 
        if($val != '')
        array_push($list, $val);
        $permission = $list;
    }
    @endphp
    <?php
        use function App\Providers\hasPermssion;
    ?>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <!-- Logo --> 
                <a href="{{route('app.home')}}" class="navbar-brand logo blue-bg"> 
                    <span class="logo-lg">
                        <img src="{{ asset('dist/img/logo-blue.png')}}" alt="logo">
                    </span> 
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto text-right">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('login') }}"><i class="icon-lock"></i> {{ __('Se connecter') }}</a>
                            </li>
                            @endif
                            {{-- @if (Route::has('register'))
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('register') }}"><i class="icon-note"></i> {{ __('Créer compte') }}</a>
                            </li>
                            @endif --}}
                        @else
                            @if(hasPermssion('list9') == 'yes') 
                            <li class="nav-item links">
                                <a class="nav-link"  href="{{ route('user.editUser',Auth::user()->id) }}"><i class="icon-user"></i> Profil</a>
                            </li>
                            @endif
                            @if(Auth::user()->is_admin != 0)
                            @if(hasPermssion('list8') == 'yes') 
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('user.index') }}"><i class="icon-user"></i> Gestion des comptes</a>
                            </li>
                            @endif
                            @endif
                            @if(hasPermssion('create9') == 'yes' || hasPermssion('edit9') == 'yes')
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('company.index') }}"><i class="icon-gears"></i> Paramètres</a>
                            </li>
                            @endif
                            @if(hasPermssion('import9_2') == 'yes' || hasPermssion('export_2') == 'yes')
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('files.excel') }}"><i class="fa fa-cloud-download"></i> Import / Export</a>
                            </li>
                            @endif

                            
                            <li class="nav-item links">
                                <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-power-off"></i> Se déconnecter
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

    </div>

    <!-- jQuery 3 --> 
    <script src="{{ asset('dist/js/jquery.min.js')}}"></script>

    <script src="{{ asset('dist/plugins/popper/popper.min.js')}}"></script>

    <!-- v4.0.0-alpha.6 -->
    <script src="{{ asset('dist/bootstrap/js/bootstrap.min.js')}}"></script>

</body>
</html>
