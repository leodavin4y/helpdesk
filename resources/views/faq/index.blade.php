@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">База знаний</h1>

        <p>
            В нашей базе знаний накоплено {{ $total_count }} совет(ов) по исправлению популярных проблем.
            Прежде чем создавать заявку о возникшей неполадке, попробуйте воспользоваться поиском по базе знаний!
        </p>

        <div class="row">
            <div class="col-12">
                <form method="post">
                    {{ csrf_field() }}
                    <label for="faqs_search">Поиск</label>

                    <div class="input-group mb-3">
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
                    <div class="py-2">Найдено результатов: {{ $results_count }}</div>
                @endif

                @foreach($results as $faq)
                    <div class="card">
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
            </div>
        </div>
    </div>
@endsection