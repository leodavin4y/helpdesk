@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">База знаний</h1>

        @if (Auth::user() && Auth::user()->isAdmin())
            <div class="py-2">
                <a href="{{ route('faq.store') }}">Добавить запись</a>
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

                            <p class="card-text">
                                {{ $faq->text }}
                            </p>

                            <a href="#" class="btn btn-primary">Детальнее</a>
                        </div>
                    </div>
                @endforeach

                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection