<ul class="nav nav-tabs mt-3">
    @foreach($tabs as $tab)
        <li class="nav-item">
            <a class="nav-link{{ $active_tab == $tab['id'] ? ' active' : '' }}" href="?status={{ $tab['id'] }}">
                {{ $tab['name'] }} (<?=$active_tab == $tab['id'] ? $requests->total() : $counter ?>)
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
                    <th scope="col">Статус</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Категория</th>
                    <th scope="col">Приоритет</th>
                    <th scope="col">Заголовок</th>
                    <th scope="col">Действие</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr id="req{{ $req->id }}">
                        <th scope="row">{{ $req->id }}</th>
                        <td class="status{{ $req->status->id }}">
                            {{ $req->status->name }}
                        </td>
                        <td>{{ $req->created_at }}</td>
                        <td><?=$req->category->parent_id ? $req->category->parent->name : $req->category->name?></td>
                        <td>{{ $req->priority->name }}</td>
                        <td>
                            <a href="{{ route('dashboard.request.show', [$req->id]) }}">{{ $req->title }}</a>
                        </td>
                        <td >
                            @if ($req->status_id === App\Models\RequestStatus::AWAIT_APPROVE)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link text-danger"
                                    data-toggle="modal"
                                    data-target="#reviewFailed"
                                    onclick="reviewFailed({{ $req->id }}, '{{ $req->title }}')"
                                    title="Проблема осталась"
                                >
                                    <i class="fa fa-repeat" aria-hidden="true"></i>
                                </button>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link text-success"
                                    data-toggle="modal"
                                    data-target="#reviewDone"
                                    onclick="reviewDone({{ $req->id }}, '{{ $req->title }}')"
                                    title="Проблема решена"
                                >
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </button>
                            @endif
                            <button
                                type="button"
                                class="btn btn-sm btn-link text-primary"
                                data-toggle="modal"
                                data-target="#requestShow"
                                onclick="showRequest({{ $req->id }})"
                                title="Открыть"
                            >
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                            @if ($active_tab == 1)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-link text-danger"
                                    onclick="deleteRequest({{ $req->id }})"
                                    title="Удалить"
                                >
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </button>
                            @endif
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

    <!-- Review Done Modal -->
    <div class="modal fade" id="reviewDone" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" data-action="{{ route('dashboard.requests.solved', 0) }}" class="modal-content">
                {{ csrf_field() }}

                <div class="modal-header">
                    <h5 class="modal-title">Проблема решена</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Вы действительно желаете установить статус "Проблема решена" для заявки <q class="request_name"></q>?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success">Подтвердить</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Review Failed Modal -->
    <div class="modal fade" id="reviewFailed" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" data-action="{{ route('dashboard.requests.repeat', 0) }}" class="modal-content">
                {{ csrf_field() }}

                <div class="modal-header">
                    <h5 class="modal-title">Проблема повторяется</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <p>Вы уверены что заявка <q class="request_name"></q> не решена?</p>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-success">Подтвердить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function reviewDone(id, name)
    {
        const form = $('#reviewDone form');

        form.attr('action', form.attr('data-action').replace('0', id));
        $('#reviewDone .request_name').text(name);
    }

    function reviewFailed(id, name)
    {
        const form = $('#reviewFailed form');

        form.attr('action', form.attr('data-action').replace('0', id));
        $('#reviewFailed .request_name').text(name);
    }
</script>