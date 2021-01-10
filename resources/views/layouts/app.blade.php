<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap-reboot.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <!-- Menu-->
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
        <a href="{{ route('home') }}" class="my-0 mr-md-auto font-weight-normal">
            <h5 class="font-weight-normal">{{ $_ENV['APP_NAME'] }}</h5>
        </a>

        <nav class="my-2 my-md-0 mr-md-3">
            @if (!Auth::user())
                <a class="p-2 text-dark" href="{{ route('faq') }}">База знаний</a>
            @else
                <a class="p-2 text-dark" href="{{ route('dashboard') }}">Панель управления</a>
                <a class="p-2 text-dark" href="{{ route('faq') }}">База знаний</a>

                @if (Auth::user()->isAdmin())
                    <a class="p-2 text-dark" href="{{ route('admin.index') }}">Администрирование</a>
                @endif
            @endif
        </nav>

        @if (Auth::user())
            <div class="float-md-right">
                <span class="badge badge-dark font-weight-normal small rounded-pill px-2 py-1" style="font-size:0.65em">
                    {{ Auth::user()->getRoleName() }}
                </span>
                <span class="pl-2">{{ Auth::user()->name }}</span>

                <form method="post" action="{{ route('logout') }}" class="d-inline-block">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-link">Выход</button>
                </form>
            </div>
        @else
            <a class="btn btn-outline-primary" href="{{ route('register') }}">Регистрация</a>
        @endif
    </div>

    <!-- Content -->
    <div class="vh-50">
        @yield('content')
    </div>

    @if (!Auth::user())
        <!-- Footer -->
        <div class="container">
            <footer class="pt-4 my-md-5 pt-md-5 border-top">
                <div class="row">
                    <div class="col-12 col-md">
                        <img class="mb-2 float-left" src="https://getbootstrap.com/docs/4.0/assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">
                        <small class="text-muted ml-2">&copy; 2020-2021 & PHP{{ phpversion() }} & {{ $_SERVER['SERVER_SOFTWARE'] }}</small>
                    </div>
                </div>
            </footer>
        </div>
    @endif

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    @yield('bodyEnd')
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });
    </script>
</body>
</html>
