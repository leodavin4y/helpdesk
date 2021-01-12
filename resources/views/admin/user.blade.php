@extends('layouts.app')

@section('title', 'Администрирование | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item">
                <a href="/">Главная</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.index') }}">Администрирование</a>
            </li>
            <li class="breadcrumb-item active">Отчёт #{{ $user->id }} - {{ $user->name }}</li>
        </ol>

        <h1 class="h3 py-3 text-center">Отчёт #{{ $user->id }} - {{ $user->name }}</h1>

        <div class="row">
            <div class="col-12">
                <div class="h5">Информация о пользователе:</div>
                <ul>
                    <li>Роль: {{ $user->getRoleName() }}</li>
                    <li>Имя: {{ $user->name }}</li>
                    <li>E-mail: {{ $user->email }}</li>
                    <li>Дата регистрации: {{ $user->created_at }}</li>
                </ul>
            </div>

            <div class="col-12">
                <div class="h5">Всего активных заявок: {{ $active_requests }}</div>
            </div>

            <div class="col-12">
                <div class="row mb-1">
                    <div class="col-auto">
                        <div class="h5">Кол-во выполненных заявок за</div>
                    </div>
                    <div class="col-auto">
                        <form method="post">
                            {{ csrf_field() }}

                            <select name="period" class="d-inline-block form-control form-control-sm" onchange="$(this).parent().submit()">
                                @foreach ($periods as $key => $name)
                                    <option value="{{ $key }}" <?=request()->input('period') === $key ? 'selected' : ''?>>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                    <div class="col-auto">
                        <span class="badge badge-dark rounded-pill py-2 px-3">{{ $requests->total() }}</span>
                    </div>
                </div>

                @if (count($requests) > 0)
                    <div class="row">
                        <div class="col-12">
                            <table class="active_requests table table-hover table-responsive-md table-striped small mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Категория</th>
                                        <th scope="col">Приоритет</th>
                                        <th scope="col">Заголовок</th>
                                        <th scope="col">Инициатор</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $req)
                                        <tr id="req{{ $req->id }}">
                                            <th scope="row">{{ $req->id }}</th>
                                            <td>{{ $req->category->name }}</td>
                                            <td>{{ $req->priority->name }}</td>
                                            <td>
                                                <a href="{{ route('dashboard.request.show', [$req->id]) }}">{{ $req->title }}</a>
                                            </td>
                                            <td>{{ $req->user->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $requests->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection