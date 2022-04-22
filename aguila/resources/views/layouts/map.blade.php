<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('js/DataTables/datatables.min.css') }}" type="text/css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('js/leaflet/leaflet.css') }}" />
    @yield('style')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark navbar-laravel">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".Lsidebar" aria-controls=".sidebar" aria-expanded="false" aria-label="Toggle navigation" style="display: inline-block;">
                <span class="navbar-toggler-icon"></span>
            </button >
            <div class="container-fluid">
                
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->employee->full_name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <nav id="sidebar" class="navbar navbar-dark bg-dark navbar-laravel align-items-start">
            <!-- Links -->
            <ul class="nav flex-column">
                <!-- Firts Level -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home"></i><span class="sidebar-item">Inicio</span></a>
                </li>
                {!! App\Models\Menu::builMenuApp() !!}

            </ul>
        </nav>

        <main class="container-fluid" style="width: calc(100% - 82px); margin-left: 82px;">
            <div class="py-4">
            @yield('content')
            </div>
        </main>
    </div>
    <script type="text/javascript">
        var dtLanguage = "{{ asset('js/DataTables/Spanish.json') }}";
    </script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/DataTables/datatables.min.js') }}"></script>


    <script type="text/javascript">
        $(document).ready(function () {

        })
    </script>
    <script src="{{ asset('js/leaflet/leaflet.js') }}"></script>
    @yield('script')

</body>
</html>
