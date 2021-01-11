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
                        @foreach ($requestForm['categories']['parent'] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="subcategory form-group">
                    <label for="category">Подкатегория</label>
                    <select name="subcategory_id" id="category" class="form-control">
                        @foreach ($requestForm['categories']['sub'] as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="priority">Приоритет</label>
                    <select name="priority_id" id="priority" class="form-control" required>
                        @foreach ($requestForm['priorities'] as $priority)
                            <option value="{{ $priority->id }}">{{ $priority->name }}</option>
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