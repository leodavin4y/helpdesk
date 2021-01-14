<div class="mt-3">
    <div class="row">
        <div class="col-4 col-md-2 col-lg-1">
            <h2 class="h5">Заявки</h2>
        </div>
        <div class="col-8 col-md-10 col-lg-11">
            <form method="post" id="statusForm" class="position-relative" style="top: -5px;">
                {{ csrf_field() }}

                <div class="form-group row mb-0 pb-0">
                    <label for="requests_status" class="d-none d-sm-block col-md-2 col-lg-1 col-form-label col-form-label-sm pr-0">Статус</label>

                    <div class="col-auto pl-0">
                        <select name="status" class="form-control form-control-sm" id="requests_status" onchange="$('#statusForm').submit()">
                            @foreach ($request_statuses['statuses'] as $status)
                                <option value="{{ $status->id }}" <?=$status->id == $request_statuses['selected'] ? 'selected' : ''?>>
                                    {{ $status->name }} ({{ $status->getCounter() }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table class="active_requests table table-hover table-responsive-md table-striped small mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Категория</th>
                <th scope="col">Приоритет</th>
                <th scope="col">Заголовок</th>
                <th scope="col">Инициатор</th>
                @if ($request_statuses['selected'] >= 2)
                    <th scope="col">Исполнитель</th>
                @endif
                <th scope="col">Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr id="req{{ $req->id }}">
                    <th scope="row">{{ $req->id }}</th>
                    <td>{{ $req->created_at }}</td>
                    <td>{{ $req->category->parent->name }}</td>
                    <td>{{ $req->priority->name }}</td>
                    <td>
                        <a href="{{ route('dashboard.request.show', [$req->id]) }}">{{ $req->title }}</a>
                    </td>
                    <td>
                        {{ $req->user->name }}
                    </td>
                    @if ($request_statuses['selected'] >= 2)
                        <td>
                            <a href="{{ route('admin.user', [$req->worker->id]) }}">{{ $req->worker->name }}</a>
                        </td>
                    @endif
                    <td >
                        <button
                            type="button"
                            class="btn btn-sm btn-link text-primary"
                            data-toggle="modal"
                            data-target="#requestShow"
                            onclick="showRequest({{ $req->id }})"
                        >
                            Открыть
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm btn-link text-primary"
                            data-toggle="modal"
                            data-target="#requestStatusesModal"
                            onclick="showStatusModal({{ $req->id }})"
                        >
                            Статус
                        </button>
                        <button
                            type="button"
                            class="btn btn-sm btn-link text-danger"
                            onclick="deleteRequest({{ $req->id }})"
                        >
                            Удалить
                        </button>
    
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="py-2">
        {{ $requests->links() }}
    </div>
</div>