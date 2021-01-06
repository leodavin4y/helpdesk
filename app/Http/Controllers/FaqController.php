<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
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

    public function store(Request $request)
    {
        if ($request->method() === 'POST') {
            return $this->storeFAQ($request);
        }

        return view('faq/store');
    }

    private function storeFAQ(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'title' => 'required|string|min:2|max:255',
            'text' => 'required|string|min:10|max:65535',
        ], [
            'title.required' => 'Поле "Заголовок" является обязательным',
            'text.required' => 'Поле "Текст" является обязательным',
            'title.min' => 'Минимальная длина заголовка - :min символа',
            'title.max' => 'Максимальная длина заголовка - :max символов',
            'text.min' => 'Минимальная длина текста - :min символов',
            'text.max' => 'Максимальная длина текста - :max символа',
        ]);

        $faq = new FAQ();

        $faq->title = $request->title;
        $faq->text = $request->text;

        if (!$faq->save()) {
            return back()->with('error', 'Не удалось сохранить');
        }

        return back()->with('success', 'Успешно сохранено');
    }
}
