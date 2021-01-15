<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Faq;

class Category extends Model
{

    protected $with = ['parent'];

    protected $table = 'categories';

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

    public $timestamps = false;

    public function parent()
    {
        return $this->hasOne('App\Models\Category', 'id', 'parent_id');
    }

    public function child()
    {
        return $this->hasMany('App\Models\Category', 'parent_id', 'id');
    }

    public function getFaqsByCategory()
    {
        return faq::where('category_id', '=', $this->id)->get();
    }

    public function getFaqsByCategoryWithPaginate()
    {
        return faq::where('category_id', '=', $this->id)->paginate();
    }
}
