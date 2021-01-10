@extends('layouts.app')

@section('title', 'Вход | Help Desk App')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Вход</h1>
        <p class="lead">
            Доступ к системе разрешен только авторизованным пользователям
        </p>
    </div>

    <div class="container">
        <div class="col-12 col-md-4 m-auto">
            <form method="post" action="{{ route('login') }}" class="p-3 mb-3 border rounded">
                <h2 class="h5">Вход в систему {{ $_ENV['APP_NAME'] }}</h2>

                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" class="form-control" required>
                </div>

                {{csrf_field()}}

                <button type="submit" class="btn btn-success">Войти</button>
            </form>

            <div class="py-2 alert bg-light text-center">Нет учетной записи? <a href="{{ route('register') }}">Регистрация</a></div>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul class="m-0 p-0 pl-2">
                        @foreach ($errors as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endisset
        </div>
    </div>
@endsection