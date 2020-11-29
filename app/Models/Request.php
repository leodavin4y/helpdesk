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
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function category()
    {
        return $this->hasOne('App\Models\Category');
    }

    public function priority()
    {
        return $this->hasOne('App\Models\Priority');
    }

    public function project()
    {
        return $this->hasOne('App\Models\Project');
    }
}
