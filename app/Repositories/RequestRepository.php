<?php

namespace App\Repositories;

use App\Models\Request;
use App\Repositories\Interfaces\RequestRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RequestRepository implements RequestRepositoryInterface
{
    public function update(int $id, array $params): bool
    {
        $request = Request::find($id);

        if (!$request) throw new \Exception('Заявка не существует');

        return $request->fill($params)->save();
    }

    public function updateWithHistory(int $id, array $params): void
    {
        DB::transaction(function() use(&$id, &$params) {
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