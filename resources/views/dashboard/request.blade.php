@extends('layouts.app')

@section('title', 'Панель управления | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item active">Заявка #{{ $request->id }}</li>
        </ol>

        <h1 class="h5 pt-3">Заявка #{{ $request->id }}</h1>
        <h2 class="h5 pb-3">{{ $request->title }}</h2>
        <div>Инициатор: {{ $request->user->name }}</div>
        <div>Проект: {{ $request->project->name }}</div>
        <div class="pb-3">Статус: {{ $request->status->name }}</div>
        <p>Описание: {{ $request->description }}</p>

        @if(session('success'))
            <div class="alert alert-success">
                {!! session('success') !!}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {!! session('error') !!}
            </div>
        @endif

        <div class="chat mt-5">
            <div class="pb-3">Сообщения:</div>

            @if (count($messages) === 0)
                <div class="alert alert-info">
                    История сообщений пуста
                </div>
            @else
                <div class="chat__history mb-1" style="max-height: 45vh; overflow-y: auto;">
                    @foreach($messages as $msg)
                        <div class="card p-2 mb-2">
                            <div class="h6">{{ $msg->user->name }}</div>

                            <div>
                                {{ $msg->text }}
                            </div>

                            <div>
                                <span class="small pr-2">{{ date('Y-m-d, H:i', strtotime($msg->created_at)) }}</span>
                                <a href="{{ route('dashboard.request.message.delete', [$msg->id]) }}" class="small">Удалить</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($messages->hasPages())
                <div class="my-2">
                    {{ $messages->links() }}
                </div>
            @endif

            <div class="chat__form border rounded p-3">
                <form method="post" action="{{ route('dashboard.messages.new', [$request->id]) }}">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <textarea name="text" class="form-control" placeholder="Написать сообщение"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">Отправить</button>
                </form>
            </div>
        </div>
    </div>
@endsection