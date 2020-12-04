<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Faq;

class FaqController extends Controller {

    public function viewItems() {
        return view('faq/index', [
            'results' => Faq::all(),
            'total_count' => Faq::count()
        ]);
    }

    public function search(Request $request)
    {
        $errors = $this->validate($request, [
            'search' => 'required|string|min:1|max:30'
        ])->all();

        if (count($errors) > 0) return response()->with('errors', $errors);

        $query = $request->input('search');
        $results = Faq::search($query)->get();

        // dd($results);

        return view('faq/index', [
            'query' => $query,
            'results' => $results,
            'results_count' => count($results),
            'total_count' => Faq::count()
        ]);
    }
}
