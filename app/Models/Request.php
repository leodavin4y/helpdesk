<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'priority_id', 'title', 'description', 'status_id', 'worker_id', 'user_id'];

    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id');
    }

    public function priority()
    {
        return $this->hasOne('App\Models\Priority', 'id', 'priority_id');
    }

    public function status()
    {
        return $this->hasOne('App\Models\RequestStatus', 'id', 'status_id');
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }

    public function worker()
    {
        return $this->hasOne('App\Models\User', 'id', 'worker_id');
    }
}
