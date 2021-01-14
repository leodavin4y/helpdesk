@extends('layouts.app')

@section('title', $faq->title . ' | База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('faq') }}">База знаний</a></li>
            <li class="breadcrumb-item active">{{ $faq->title }}</li>
        </ol>

        <h1 class="h3 py-3 text-center">База знаний</h1>

        @if (Auth::user() && Auth::user()->isAdmin())
            <div class="py-2">
                <a href="{{ route('faq.edit', [$faq->id]) }}" class="btn btn-sm btn-light">
                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                    Редактировать
                </a>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="border px-3 py-2 rounded shadow-sm">
                    <h2 class="h4 py-3">{{ $faq->title }}</h2>

                    {!! $faq->text !!}
                </div>
            </div>
        </div>
    </div>
@endsection