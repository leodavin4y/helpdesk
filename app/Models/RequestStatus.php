<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Request;

class RequestStatus extends Model
{

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
}
