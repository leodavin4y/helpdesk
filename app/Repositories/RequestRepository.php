<?php

namespace App\Repositories;

use App\Models\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestRepository implements RequestRepositoryInterface
{
    public function find(int $id)
    {
        return Request::find($id);
    }

    public function update(int $id, array $params): bool
    {
        $request = $this->find($id);

        if (!$request) throw new \Exception('Заявка не существует');

        if (!is_null($request->worker_id) && is_null($params['worker_id'])) {
            $params['worker_id'] = $request->worker_id;
        }

        return $request->fill($params)->save();
    }

    public function updateWithHistory(int $id, array $params): void
    {
        DB::transaction(function() use(&$id, &$params, &$request) {
            if (!$this->update($id, $params)) throw new \Exception('Ошибка сохранения заявки');

            $user = Auth::user();
            $data = [
                'request_id' => $id,
                'user_id' => $user->id,
                'status_id' => $params['status_id']
            ];

            if ((new RequestHistoryRepository)->store($data) === false) throw new \Exception('Ошибка сохранения заявки');
        });
    }
}