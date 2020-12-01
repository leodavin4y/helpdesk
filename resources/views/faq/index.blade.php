@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')

        <div class="container">
            <h1 class="h3 py-3 text-center">База знаний</h1>
        </div>
    @foreach($allFaqs as $faq)
        <div  class="alert alert-info">
            <h3>{{ $faq->title }}</h3>
            <p>{{ $faq->text }}</p>
            <p><small>{{ $faq->created_at }}</small></p>
            <a href="#"><button class="btn btn-info">Детальнее</button></a>
        </div>


        @endforeach


@endsection