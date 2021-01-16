<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestFile extends Model
{

    protected $table = 'request_files';

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

    public function request()
    {
        return $this->hasOne('App\Models\Request', 'id', 'request_id');
    }
}
