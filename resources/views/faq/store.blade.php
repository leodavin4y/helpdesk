@extends('layouts.app')

@section('title', 'База знаний | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">База знаний</h1>

        <div class="row">
            <div class="col-12">
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

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
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

                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
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