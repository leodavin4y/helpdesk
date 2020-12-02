@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')

        <div class="container">
            <h1 class="h3 py-3 text-center">База знаний</h1>
            <div class="row">
                <div class="col-lg-12">

                        <label for="users_search">Поиск</label>
                        <div class="input-group mb-3">
                            <input type="text" name="search" value="{{ $search ?? '' }}" id="faqs_search" class="form-control" placeholder="поиск по статьям базы знаний" required>

                            <div class="input-group-append">
                                <button type="submit" class="btn btn-sm btn-light input-group-text">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>

    @foreach($allFaqs as $faq)
        <div  class="alert alert-info">
            <p class="font-weight-bold">{{ $faq->title }}</p>
            <p class="font-weight-normal">{{ $faq->text }}</p>
            <p><small>{{ $faq->created_at }}</small></p>
            <a href="#"><button class="btn btn-info">Детальнее</button></a>
        </div>


        @endforeach


@endsection