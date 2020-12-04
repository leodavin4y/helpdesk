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
                        <a class="nav-link active" href="#active_req" data-toggle="tab" aria-controls="active_req" aria-selected="true">Активные заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Завершенные заявки</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active px-3 pb-3 border border-top-0" id="active_req" role="tabpanel" aria-labelledby="active_req-tab">
                        <div class="small py-2">Записей: {{ $active_requests_count }}</div>
                        <table class="active_requests table table-responsive-md table-striped small mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Категория</th>
                                    <th scope="col">Приоритет</th>
                                    <th scope="col">Проект</th>
                                    <th scope="col">Заголовок</th>
                                    <th scope="col">Действие</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($active_requests as $act_req)
                                    <tr id="req{{ $act_req->id }}">
                                        <th scope="row">{{ $act_req->id }}</th>
                                        <td>{{ $act_req->category->name }}</td>
                                        <td>{{ $act_req->priority->name }}</td>
                                        <td>{{ $act_req->project->name }}</td>
                                        <td>{{ $act_req->title }}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link text-primary"
                                                data-toggle="modal"
                                                data-target="#requestShow"
                                                onclick="showRequest({{ $act_req->id }})"
                                            >
                                                Открыть
                                            </button>
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-link text-danger"
                                                onclick="deleteRequest({{ $act_req->id }})"
                                            >
                                                Удалить
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal New Request -->
    <div class="modal fade" id="requestNewModal" tabindex="-1" role="dialog" aria-labelledby="requestNewModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" class="modal-content" action="{{ route('dashboard.request.store') }}" data-action="{{ route('dashboard.request.store') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Новая заявка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="category">Категория</label>
                        <select name="category_id" id="category" class="form-control" required>
                            @foreach ($request['categories']['parent'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="subcategory form-group">
                        <label for="category">Подкатегория</label>
                        <select name="subcategory_id" id="category" class="form-control">
                            @foreach ($request['categories']['sub'] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priority">Приоритет</label>
                        <select name="priority_id" id="priority" class="form-control" required>
                            @foreach ($request['priorities'] as $priority)
                                <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="priority">Проект</label>
                        <select name="project_id" id="project" class="form-control" required>
                            @foreach ($request['projects'] as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Заголовок заявки</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Краткое описание проблемы" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Заголовок</label>
                        <textarea name="description" id="description" class="form-control" placeholder="Полное описание проблемы" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Show Request -->
    <div class="modal fade" id="requestShow" tabindex="-1" role="dialog" aria-labelledby="requestShow" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" class="modal-content" action="{{ route('dashboard.request.store') }}" data-action="{{ route('dashboard.request.store') }}">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h5 class="modal-title">Новая заявка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="category">Категория</label>
                            <select name="category_id" class="form-control" disabled>
                                @foreach ($request['categories']['parent'] as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="subcategory form-group d-none">
                            <label for="category">Подкатегория</label>
                            <select name="subcategory_id" class="form-control" disabled></select>
                        </div>

                        <div class="form-group">
                            <label for="priority">Приоритет</label>
                            <select name="priority_id" class="form-control" disabled>
                                @foreach ($request['priorities'] as $priority)
                                    <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority">Проект</label>
                            <select name="project_id" class="form-control" disabled>
                                @foreach ($request['projects'] as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="title">Заголовок заявки</label>
                            <input type="text" name="title" class="form-control" placeholder="Краткое описание проблемы" disabled>
                        </div>

                        <div class="form-group">
                            <label for="description">Заголовок</label>
                            <textarea name="description" class="form-control" placeholder="Полное описание проблемы" disabled></textarea>
                        </div>
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
        const activeRequests = JSON.parse('@json($active_requests)');

        function requestStore()
        {
            const catSelect = $('#requestNewModal select[name="category_id"]');
            const subCategoryDraw = () => {
                const catId = Number(catSelect.val());
                const subSelect = $('#requestNewModal select[name="subcategory_id"]');
                const subSelectWrap = subSelect.parent();
                let subExist = false;

                subSelect.empty();

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
        }

        function showRequest(id)
        {
            let req = null;

            activeRequests.forEach(activeReq => {
                if (activeReq.id === id) req = activeReq;
            });

            if (!req) return;

            console.log(req);

            $('#requestShow h5').text(`Заявка #${req.id} | ${req.title}`);

            // set category
            req.category.parent ?
                $('#requestShow select[name="category_id"]').val(req.category.parent.id.toString()) :
                $('#requestShow select[name="category_id"]').val(req.category_id.toString());

            // set sub category
            if (req.category.parent) {
                $('#requestShow select[name="subcategory_id"]').append($(`<option value="${req.category.id}" selected>${req.category.name}</option>`));
                $('#requestShow .subcategory').removeClass('d-none');
            } else {
                $('#requestShow select[name="subcategory_id"]').empty();
                $('#requestShow .subcategory').addClass('d-none');
            }

            // set priority
            $('#requestShow select[name="priority_id"]').val(req.priority_id);

            // set project
            $('#requestShow select[name="project_id"]').val(req.project_id);

            // set title
            $('#requestShow input[name="title"]').val(req.title);

            // set description
            $('#requestShow textarea[name="description"]').val(req.description);
        }

        function deleteRequest(id)
        {
            $.ajax({
                type: 'POST',
                url: "{{ route('dashboard.request.delete', [0]) }}".replace('/0/', `/${id}/`),
                success: () => {
                    $(`.active_requests #req${id}`).hide(300)
                }
            });
        }
    </script>
@endsection