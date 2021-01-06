@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('faq') }}">База знаний</a></li>
            @if (isset($faq))
                <li class="breadcrumb-item active"><a href="{{ route('faq.view', [$faq->id]) }}">{{ $faq->title }}</a></li>
            @else
                <li class="breadcrumb-item active">Добавить</li>
            @endif
        </ol>

        <h1 class="h3 py-3 text-center">База знаний</h1>

        <div class="row">
            <div class="col-12">
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

                @if ($errors->any())
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{$error}}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (isset($faq))
                    <a href="{{ route('faq.view', [$faq->id]) }}" class="btn btn-sm btn-light">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        Вернуться к заметке
                    </a>

                    <form method="post" class="border p-3 mt-2 mb-3 rounded shadow-sm">
                        <h2 class="h4">Редактирование заметки</h2>
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="faq_title">Заголовок</label>
                            <input type="text" value="{{ $faq->title }}" id="faq_title" name="title" class="form-control" placeholder="Заголовок заметки" required>
                        </div>

                        <div class="form-group">
                            <label for="faq_text">Текст</label>
                            <textarea id="faq_text" name="text" class="form-control" placeholder="Текст заметки">
                                {{ $faq->text }}
                            </textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Сохранить в базе знаний</button>
                    </form>
                @else
                    <form method="post" class="border p-3 mt-2 mb-3 rounded shadow-sm">
                        <h2 class="h4">Новая заметка</h2>
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="faq_title">Заголовок</label>
                            <input type="text" id="faq_title" name="title" class="form-control" placeholder="Заголовок заметки" required>
                        </div>

                        <div class="form-group">
                            <label for="faq_text">Текст</label>
                            <textarea id="faq_text" name="text" class="form-control" placeholder="Текст заметки"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Сохранить в базе знаний</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('bodyEnd')
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        // https://www.tiny.cloud/docs/general-configuration-guide/basic-setup/
        tinymce.init({
            selector: 'textarea#faq_text',
            height: 300,
            plugins: 'advlist link image lists table wordcount fullscreen emoticons searchreplace',
            toolbar: 'undo redo | styleselect | bold italic | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent',
        });
    </script>
@endsection