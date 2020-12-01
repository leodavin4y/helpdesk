<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Faq;

class FaqController extends Controller {

    public function viewItems() {
        return view('faq/index', ['allFaqs' => Faq::all()]);
    }
}
