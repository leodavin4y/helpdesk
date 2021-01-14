@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item active">База знаний</li>
        </ol>

        <h1 class="h3 py-3 text-center">База знаний</h1>

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{ $message }}
            </div>
        @endif

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {!! $message !!}
            </div>
        @endif

        @if (Auth::user() && Auth::user()->isAdmin())
            <div class="py-2">
                <a href="{{ route('faq.store') }}" class="btn btn-sm btn-light">
                    <i class="fa fa-plus-square" aria-hidden="true"></i> Добавить
                </a>
            </div>
        @endif

        <p>
            В нашей базе знаний накоплено {{ $results->total() }} совет(ов) по исправлению популярных проблем.
            Прежде чем создавать заявку о возникшей неполадке, попробуйте воспользоваться поиском по базе знаний!
        </p>

        <div class="row">
            <div class="col-12">
                <form method="post" class="border p-3 mt-2 mb-3 rounded shadow-sm">
                    {{ csrf_field() }}
                    <label for="faqs_search" class="h5">Поиск</label>

                    <div class="input-group">
                        <input
                            type="text"
                            name="search" value="{{ $query ?? '' }}"
                            id="faqs_search"
                            class="form-control"
                            placeholder="Поиск по статьям базы знаний"
                            minlength="2"
                            maxlength="30"
                            required
                        >

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-sm btn-light input-group-text">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>

                @isset($query)
                    <div class="h5 py-2">Найдено результатов: {{ $results->total() }}</div>
                @elseif (count($results) > 0)
                    <div class="h5 py-2">Часто возникающие проблемы:</div>
                @else
                    <div class="alert alert-info">В данный момент база знаний пуста ...</div>
                @endif

                @foreach($results as $faq)
                    <div class="card mb-3 shadow-sm">
                        <div class="card-body">
                            <div class="clearfix">
                                <h5 class="card-title float-md-left">
                                    {{ $faq->title }}
                                </h5>

                                <span class="small text-muted float-md-right">{{ date('d-m-Y, H:i', strtotime($faq->created_at)) }}</span>
                            </div>

                            <div class="card-text text-overflow mb-2">
                                {{ $faq->annotation }}
                            </div>

                            <a href="{{ route('faq.view', [$faq->id]) }}" class="btn btn-primary">Детальнее</a>

                            @if (($user = Auth::user()) && $user->role === 3)
                                <a href="{{ route('faq.edit', [$faq->id]) }}" class="btn btn-sm btn-link">Изменить</a>
                                <a href="{{ route('faq.delete', [$faq->id]) }}" class="btn btn-sm btn-link text-danger">Удалить</a>
                            @endif
                        </div>
                    </div>
                @endforeach

                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection