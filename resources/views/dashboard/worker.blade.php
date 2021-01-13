<ul class="nav nav-tabs mt-3">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link{{ $active_tab == $tab['id'] ? ' active' : '' }}" href="?status={{ $tab['id'] }}">
                {{ $tab['name'] }}
                @foreach ($request_statuses['statuses'] as $s)
                    @if ($s->id === $tab['id'])
                        ({{ $s->getCounterByWorker($user->id) }})
                    @endif
                @endforeach
            </a>
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
                    <th scope="col">Дата создания</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Приоритет</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Инициатор</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr id="req{{ $req->id }}">
                        <th scope="row">{{ $req->id }}</th>
                        <td>{{ $req->created_at }}</td>
                        <td>{{ $req->category->name }}</td>
                        <td>{{ $req->priority->name }}</td>
                        <td>
                            <a href="{{ route('dashboard.request.show', [$req->id]) }}">{{ $req->title }}</a>
                        </td>
                        <td>{{ $req->user->name }}</td>
                        <td>
                            @if ($req->status_id === 2)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link text-success py-0"
                                    data-toggle="modal"
                                    data-target="#workerStatus"
                                    onclick="showWorkerStatusModal({{ $req->id }}, '{{ $req->title }}')"
                                >
                                    На проверку
                                </button>
                            @endif

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

        @if ($requests->hasPages())
            <div class="py-3">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Statuses for worker -->
<div class="modal fade" id="workerStatus" tabindex="-1" role="dialog" aria-labelledby="workerStatus" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form
                method="post"
                class="modal-content"
                action="{{ route('dashboard.requests.worker.done', 0) }}"
                data-action="{{ route('dashboard.requests.worker.done', 0) }}"
        >
            {{ csrf_field() }}

            <div class="modal-header">
                <h5 class="modal-title">Подтвердите завершение работ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p>Отправить заявку: <q id="worker_req_name"></q> на проверку?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-success">На проверку</button>
            </div>
        </form>
    </div>
</div>