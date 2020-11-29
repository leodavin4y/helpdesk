@extends('layouts.app')

@section('title', 'Панель управления | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <h1 class="h3 py-3 text-center">Панель управления</h1>

        <div class="row">
            <div class="col-12">
                <div class="p-2 border rounded">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#requestNewModal" onclick="requestStore()">
                        <i class="fa fa-plus" aria-hidden="true" style="color:#b1b1b1;"></i> Новая заявка
                    </button>
                </div>

                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Активные заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Завершенные заявки</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Modal New Request -->
    <div class="modal fade" id="requestNewModal" tabindex="-1" role="dialog" aria-labelledby="requestNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" class="modal-content" data-action="{{ route('dashboard.request.store') }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Новая заявка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select name="category" id="category" class="form-control">
                            @foreach ($request['categories']['parent'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="subcategory form-group"></div>

                    <div class="form-group">
                        <label for="priority">Приоритет</label>
                        <select name="priority" id="priority" class="form-control">
                            @foreach ($request['priorities'] as $priority)
                                <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priority">Проект</label>
                        <select name="project" id="project" class="form-control">
                            @foreach ($request['projects'] as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const subCategories = JSON.parse('@json($request['categories']['sub'])');

        function requestStore()
        {
            const categoryInput = $('#requestNewModal form #category');
            const subCategory = $('#requestNewModal form .subcategory');
            const subCategoryInput = $('<select name="priority" id="priority" class="form-control"></select>');
            let subCategoryContent = null;
            const addOption = (data) => {
                if (!subCategoryContent) {
                    subCategoryContent = $('<div/>', {className: 'form-group'});
                    subCategory.append(
                        subCategoryContent
                            .append($('<label>Подкатегория</label>'))
                            .append(subCategoryInput)
                    );
                }

                subCategoryInput.append(
                    $('<option/>', {
                        value: data.id,
                        text: data.name
                    })
                )
            };
            const showSubCategories = (categoryId) => {
                if (subCategoryInput.children().length > 0) subCategoryInput.empty();

                subCategories.forEach(sub => {
                    if (sub.parent_id !== categoryId) return;

                    addOption(sub);
                });
            };

            showSubCategories(Number(categoryInput.val()));

            categoryInput.on('change', (e) => {
                if (subCategoryContent) subCategoryContent.remove();
                subCategoryContent = null;

                showSubCategories(Number(e.target.value))
            });
        }
    </script>
@endsection