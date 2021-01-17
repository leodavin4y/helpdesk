<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Request;

class RequestStatus extends Model
{

    const NEW = 1;

    const WORKER_ASSIGNED = 2;

    const IN_PROGRESS = 3;

    const AWAIT_APPROVE = 4;

    const SOLVED = 5;

    const CLOSED = 6;

    protected $table = 'request_statuses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Возвращает суммарное кол-во заявок с таким статусом
     *
     * @return int
     */
    public function getCounter(): int
    {
        return Request::where('status_id', '=', $this->id)->count();
    }

    /**
     * Возвращает суммарное кол-во заявок для исполнителя с таким статусом
     *
     * @param int $workerId - код исполнителя
     * @return int
     */
    public function getCounterByWorker(int $workerId): int
    {
        return Request::where('status_id', '=', $this->id)
            ->where('worker_id', '=', $workerId)
            ->count();
    }
}
