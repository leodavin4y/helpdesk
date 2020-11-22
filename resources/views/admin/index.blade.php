@extends('layouts.app')

@section('title', 'Администрирование | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">Администрирование</h1>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#users" data-toggle="tab" aria-controls="users" aria-selected="true">
                            <i class="fa fa-users" aria-hidden="true"></i> Пользователи
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active px-3 pb-3 border border-top-0" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <form method="post" action="{{ route('admin.users.search') }}" class="pt-3">
                            <label for="users_search">Поиск</label>
                            <div class="input-group mb-3">
                                {{ csrf_field() }}
                                <input type="text" name="search" value="{{ $search ?? '' }}" id="users_search" class="form-control" placeholder="Введите имя или почтовый адрес" required>

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-light input-group-text">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        @isset($users)
                            @foreach ($users as $user)
                                <div class="w-100 p-2 border rounded mb-2">
                                    <span class="badge badge-dark">Имя</span> {{ $user->name }}
                                    <span class="badge badge-dark ml-2">E-mail</span> {{ $user->email }}
                                    <form method="post" action="{{ route('admin.users.delete', [$user->id]) }}" class="d-inline-block">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-link text-danger">Удалить</button>
                                    </form>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection