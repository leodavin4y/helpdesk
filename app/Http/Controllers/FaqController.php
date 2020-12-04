<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;

class FaqController extends Controller {

    public function index()
    {
        $results = Faq::paginate(1);

        return view('faq/index', [
            'results' => $results
        ]);
    }

    public function search(Request $request)
    {
        $errors = $this
            ->validate($request, ['search' => 'required|string|min:1|max:30'])
            ->all();

        if (count($errors) > 0) return response()->with('errors', $errors);

        $query = $request->input('search');
        $results = Faq::search($query)->paginate(1);

        return view('faq/index', [
            'query' => $query,
            'results' => $results,
        ]);
    }
}
