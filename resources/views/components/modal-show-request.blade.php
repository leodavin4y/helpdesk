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
                            @foreach ($requestForm['categories']['parent'] as $category)
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
                            @foreach ($requestForm['priorities'] as $priority)
                                <option value="{{ $priority->id }}">{{ $priority->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="title">Заголовок заявки</label>
                        <input type="text" name="title" class="form-control" placeholder="Краткое описание проблемы" disabled>
                    </div>

                    <div class="form-group">
                        <label for="description">Описание</label>
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