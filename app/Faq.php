<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use FullTextSearch;

    protected $searchable = [
        'title',
        'text',
    ];
}
