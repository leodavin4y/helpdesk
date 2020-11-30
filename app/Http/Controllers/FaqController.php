<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        return view('faq/index', [
            'user' => $user
        ]);
    }
}
