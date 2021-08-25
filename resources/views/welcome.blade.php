<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Music For Peace</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <!-- Alpinejs -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.2/dist/alpine.min.js" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">

        <!-- Fonts -->
        <style>
        #background {
            background-color: pink;
            height: 100%;
        }
        body{
            background-color: pink;
        }

        #loginContainer * {
            color: #fff;
            font-family: "Helvetica Neue", "Helvetica", "Arial", sans-serif;
            font-weight: normal;
            line-height: 1em;
            box-sizing: border-box;
        }

        #loginContainer {
            width: 100%;
            margin: 0 auto;
            position: relative;
            max-width: 1024px;
        }

        #loginText {
            padding: 45px;
            display: table-cell;
        }

        #loginText h1 {
            color: black;
            font-size: 60px;
            font-weight: bold;
        }

        #loginText h2 {
            margin: 35px 0;
        }

        #loginText ul {
            padding: 0;
        }

        #loginText li {
            font-size: 20px;
            color: grey;
            list-style-type: none;
            padding: 5px 30px;
            background: url({{ URL::asset('storage/img/tick.png') }}) no-repeat 0 0;
        }
        
        </style>
    
    </head>
    <body>
         <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'MusicForPeace') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/home') }}">Home</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Log in</a>
                                </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                          
                        @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <br><br><br><br><br>
        <div id="background">
            <div id="loginContainer">
                <div id="loginText">
                    <h1>Music For Peace</h1>
                    <h2>Promote peace through music</h2>
                    <ul>
                        <li>Only postive music</li>
                        <li>Upload your music through Web App</li>
                        <li>Start listening on MusicForPeace Mobile App (Android)</li>
                    </ul>
                </div>
            </div>
        </div>
    </body>
</html>
