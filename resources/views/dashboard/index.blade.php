@extends('layouts.app')

@section('title', 'Панель управления | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item active">Управление</li>
        </ol>

        <h1 class="h3 py-3 text-center">Панель управления</h1>

        <div class="row">
            <div class="col-12">
                <div class="p-2 mb-2 border rounded">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#requestNewModal" onclick="requestStore()">
                        <i class="fa fa-plus" aria-hidden="true" style="color:#b1b1b1;"></i> Новая заявка
                    </button>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        {!! session('success') !!}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {!! session('error') !!}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (Auth::user()->role === 3)
                    @include('dashboard.admin')
                @endif

                @if (Auth::user()->role === 2)
                    @include('dashboard.worker')
                @endif

                @if (Auth::user()->role === 1)
                    @include('dashboard.user')
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Statuses -->
    <div class="modal fade" id="requestStatusesModal" tabindex="-1" role="dialog" aria-labelledby="requestStatusesModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form
                method="post"
                class="modal-content"
                action="{{ route('dashboard.request.status', 0) }}"
                data-action="{{ route('dashboard.request.status', 0) }}"
            >
                {{ csrf_field() }}

                <div class="modal-header">
                    <h5 class="modal-title">Изменить статус заявки</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <button type="button" class="btn btn-sm btn-link px-0" onclick="setWorker()">Назначить исполнителя</button>

                    <div class="form-group">
                        <label for="modal_status">Cтатус заявки</label>
                        <select name="status" id="modal_status" class="form-control">
                            @foreach ($request_statuses['statuses'] as $status)
                                @if ($status->id == $request_statuses['selected'])
                                    <option value="{{ $status->id }}" selected>{{ $status->name }}</option>
                                @else
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endif
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

    <!-- Modal New Request -->
    <x-modal-new-request/>

    <!-- Modal Show Request -->
    <x-modal-show-request/>

    <script>
        const subCategories = JSON.parse('@json($request['categories']['sub'])');
        const activeRequests = JSON.parse('@json($requests, JSON_UNESCAPED_UNICODE)');

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

            activeRequests.data.forEach(activeReq => {
                if (activeReq.id === id) req = activeReq;
            });

            if (!req) return;

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

        function showStatusModal(id)
        {
            const form = $('#requestStatusesModal form').get(0);

            $('#requestStatusesModal h5').text(`Изменить статус заявки #${id}`);
            form.action = form.dataset.action.replace('0', id);

            $('#modal_status').on('change', e => {
                if (e.target.value == 2) setWorker()
            });
        }

        function setWorker()
        {
            const select = $('<select class="form-control" name="worker" id="workers"></select>');
            const wrap = $('<div class="form-group">' +
                '<label>Исполнитель</label>' +
              '</div>'
            );

            wrap.appendTo('#requestStatusesModal .modal-body');
            select.appendTo(wrap);

            $.ajax({
                method: 'GET',
                url: `/dashboard/users/2/get`,
                success: r => {
                    r.users.forEach(user => {
                        $(`<option value=${user.id}>${user.name}</option>`).appendTo(select)
                    });

                    $('#modal_status').val(2).on('change', () => wrap.remove());
                }
            });

            // Событие закрытия окна
            $('#requestStatusesModal').on('hidden.bs.modal', function () {
                wrap.remove();
                // $('#modal_status').parent().show();
            })
        }

        function showWorkerStatusModal(id, title)
        {
            const form = $('#workerStatus form');

            $('#worker_req_name').text(title);
            form.attr('action', form.attr('data-action').replace('0', id));
        }
    </script>
@endsection