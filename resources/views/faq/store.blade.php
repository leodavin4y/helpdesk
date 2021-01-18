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
                            <label for="faq_category">Категория</label>
                            <select name="category_id" id="faq_category" class="form-control">
                                <option>[Без категории]</option>
                                @foreach ($categories['parent'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="subcategory form-group">
                            <label for="category">Подкатегория</label>
                            <select name="subcategory_id" id="category" class="form-control">
                                @foreach ($categories['sub'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
                            <label for="faq_category">Категория</label>
                            <select name="category_id" id="faq_category" class="form-control">
                                <option value>[Без категории]</option>
                                @foreach ($categories['parent'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="subcategory form-group">
                            <label for="category">Подкатегория</label>
                            <select name="subcategory_id" id="category" class="form-control">
                                <option value>[Без категории]</option>
                                @foreach ($categories['sub'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
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
    <script>
        const subCategories = JSON.parse('@json($categories['sub'])');

        @if (isset($faq))
            window.addEventListener('load', () => {edit(JSON.parse('@json($faq->category)'))});
        @endif

        window.addEventListener('load', () => {
            const catSelect = $('select[name="category_id"]');
            const subCategoryDraw = () => {
                const catId = Number(catSelect.val());
                const subSelect = $('select[name="subcategory_id"]');
                const subSelectWrap = subSelect.parent();
                let subExist = false;

                subSelect.empty();

                subSelect.append($(`<option value="">[Без категории]</option>`));
                subCategories.forEach(sub => {
                    if (sub.parent_id === catId) {
                        subExist = true;
                        subSelect.append($(`<option value="${sub.id}">${sub.name}</option>`))
                    }
                });
                subExist ? subSelectWrap.removeClass('d-none') : subSelectWrap.addClass('d-none');
            };

            subCategoryDraw();
            catSelect.on('change', subCategoryDraw);
        });

        function edit(reqCategory) {
            // set category
            reqCategory.parent ?
                $('select[name="category_id"]').val(reqCategory.parent.id.toString()) :
                $('select[name="category_id"]').val(reqCategory.id.toString());

            // set sub category
            if (reqCategory.parent) {
                $('select[name="subcategory_id"]').append($(`<option value="${reqCategory.id}" selected>${reqCategory.name}</option>`));
                $('.subcategory').removeClass('d-none');
            } else {
                $('select[name="subcategory_id"]').empty();
                $('.subcategory').addClass('d-none');
            }
        }
    </script>
@endsection