<ul class="nav nav-tabs mt-3">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link{{ $active_tab == $tab['id'] ? ' active' : '' }}" href="?status={{ $tab['id'] }}">{{ $tab['name'] }}</a>
        </li>
    @endforeach
</ul>

<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active px-3 pb-3 border border-top-0" role="tabpanel">
        <div class="small py-2">Записей: {{ $requests->total() }}</div>

        <table class="active_requests table table-responsive-md table-striped small mb-0">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Приоритет</th>
                    <th scope="col">Проект</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Инициатор</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr id="req{{ $req->id }}">
                        <th scope="row">{{ $req->id }}</th>
                        <td>{{ $req->category->name }}</td>
                        <td>{{ $req->priority->name }}</td>
                        <td>{{ $req->project->name }}</td>
                        <td>{{ $req->title }}</td>
                        <td>{{ $req->user->name }}</td>
                        <td>
                            <button
                                type="button"
                                class="btn btn-sm btn-link text-success py-0"
                                data-toggle="modal"
                                data-target="#workerStatus"
                                onclick="showWorkerStatusModal({{ $req->id }}, '{{ $req->title }}')"
                            >
                                На проверку
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-link text-primary py-0"
                                data-toggle="modal"
                                data-target="#requestShow"
                                onclick="showRequest({{ $req->id }})"
                            >
                                Открыть
                            </button>

                            <button
                                type="button"
                                class="btn btn-sm btn-link text-danger py-0"
                                onclick="deleteRequest({{ $req->id }})"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($requests->hasMorePages())
            <div class="py-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>