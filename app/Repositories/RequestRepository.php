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

        if (!is_null($request->worker_id ?? null) && is_null($params['worker_id'] ?? null)) {
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

    public function reportCountByMonth(): array
    {
        return DB::select(
            DB::raw("
                SELECT COUNT(*) as counter, MONTH(created_at) as p 
                  FROM `requests` 
                    WHERE YEAR(created_at) = YEAR(CURRENT_DATE)
                  GROUP BY p
            ")
        );
    }

    public function reportCountByYear(): array
    {
        return DB::select(
            DB::raw("
                SELECT COUNT(*) as counter, YEAR(created_at) as p 
                  FROM `requests` 
                    WHERE YEAR(created_at) = YEAR(CURRENT_DATE)
                  GROUP BY p
            ")
        );
    }

    public function reportCountLastWeek(): array
    {
        return DB::select(
            DB::raw("
                SELECT COUNT(*) as counter, DATE(created_at) as p 
                  FROM `requests` 
                    WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) 
                  GROUP BY p
                  ORDER BY p DESC
                LIMIT 7
            ")
        );
    }
}