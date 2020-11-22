@extends('layouts.app')

@section('title', 'Регистрация | Help Desk App')

@section('content')
    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">Регистрация</h1>
        <p class="lead">
            Пожалуйста, пройдите бесплатную регистрацию для работы с нашим сервисом. <br/>Это займет не более 1 минуты! :)
        </p>
    </div>

    <div class="container">
        <div class="col-12 col-md-5 m-auto">
            <form method="post" action="{{ route('register') }}" class="p-3 mb-3 border rounded">
                <h2 class="h5 pb-2">Регистрация в системе {{ $_ENV['APP_NAME'] }}</h2>

                <div class="form-group">
                    <input type="text" name="name" placeholder="Имя,фамилия" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="email" name="email" placeholder="E-mail" class="form-control" required>
                </div>

                <div class="form-group">
                    <input type="password" name="password" placeholder="Пароль" class="form-control" required>
                </div>

                {{ csrf_field() }}

                <button type="submit" class="btn btn-success">Регистрация</button>

                <span class="px-2">
                    Вы уже зарегистрированы? <a href="{{ route('login') }}">Войти</a>
                </span>
            </form>

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