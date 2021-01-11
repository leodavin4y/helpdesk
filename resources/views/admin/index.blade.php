@extends('layouts.app')

@section('title', 'Администрирование | ' . $_ENV['APP_NAME'])

@section('content')
    <div class="container">
        <ol class="breadcrumb bg-light">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item active">Администрирование</li>
        </ol>

        <h1 class="h3 py-3 text-center">Администрирование</h1>

        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs mt-3">
                    <li class="nav-item">
                        <a class="nav-link active" href="#users" data-toggle="tab" aria-controls="users" aria-selected="true">
                            <i class="fa fa-users" aria-hidden="true"></i> Пользователи
                            <span class="badge badge-dark rounded-pill px-2">{{ $users_total }}</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active px-3 pb-3 border border-top-0" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <form method="post" action="{{ route('admin.users.search') }}" class="pt-3">
                            <label for="users_search">Поиск</label>
                            <div class="input-group mb-3">
                                {{ csrf_field() }}
                                <input type="text" name="search" value="{{ $search ?? '' }}" id="users_search" class="form-control" placeholder="Введите имя или почтовый адрес" required>

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-sm btn-light input-group-text">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="small pb-1">Найдено: {{ $users->total() }} / Отображается: {{ $users->count() }}</div>

                        @isset($users)
                            <table class="table table-responsive-md table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Имя</th>
                                        <th scope="col">Почта</th>
                                        <th scope="col">Роль</th>
                                        <th scope="col">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <th scope="row">{{ $user->id }}</th>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="small">{{ $user->getRoleName() }}</span>
                                            </td>
                                            <td>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-link text-primary"
                                                    data-toggle="modal"
                                                    data-target="#editProfileModal"
                                                    onclick="editProfile({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', {{ $user->role }})"
                                                >
                                                    Изменить
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-link text-danger"
                                                    onclick="userDelete({{ $user->id }})"
                                                >
                                                    Удалить
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            {{ $users->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form method="post" class="modal-content" data-action="{{ route('admin.users.edit', 0) }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Редактировать профиль</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <button type="button" onclick="$('#newPassWrap').toggle()" class="btn btn-sm btn-light">Сбросить пароль</button>

                        <div id="newPassWrap" class="mt-2" style="display: none">
                            <label for="newPass">Введите новый пароль</label>
                            <input type="password" name="password" id="newPass" class="form-control" placeholder="От 6 до 20 символов">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="profileName">Имя</label>
                        <input type="text" name="name" id="profileName" class="form-control" placeholder="Имя" required>
                    </div>

                    <div class="form-group">
                        <label for="profileEmail">Почта</label>
                        <input type="email" name="email" id="profileEmail" class="form-control" placeholder="Почта" required>
                    </div>

                    <div class="form-group">
                        <label for="profileRole">Роль</label>
                        <select class="form-control" name="role" id="profileRole">
                            <option value="1">Инциатор</option>
                            <option value="2">Исполнитель</option>
                            <option value="3">Администратор</option>
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
        function editProfile(id, name, email, role)
        {
            const form = document.querySelector('#editProfileModal form');
            const templateURL = form.dataset.action;
            const nameInput = form.querySelector('input[name="name"]');
            const emailInput = form.querySelector('input[name="email"]');
            const options = form.querySelectorAll('option');

            nameInput.value = name;
            emailInput.value = email;
            role = role.toString();

            options.forEach(option => {
                if (option.hasAttribute('selected')) option.removeAttribute('selected');
                if (option.value === role) option.setAttribute('selected', 'true');
            });

            form.action = templateURL.replace('0', id);

            $(form).on('submit', (e) => {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: form.action,
                    data: $(form).serialize(),
                    success: (res) => {
                        alert(res.status ? 'Профиль пользователя изменен' : 'Не удалось');
                    }
                })
            })
        }

        function userDelete(userId)
        {
            const URL = "{{ route('admin.users.delete', 0) }}".replace('0', userId);

            $.ajax({
                type: 'POST',
                url: URL,
                success: (res) => {
                    alert(res.status ? 'Пользователь успешно удален' : 'Не удалось удалить пользователя');
                }
            })
        }
    </script>
@endsection