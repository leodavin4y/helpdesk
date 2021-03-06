<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Category;
use App\Models\Priority;

class ModalShowRequest extends Component
{
    public $requestForm;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->requestForm = [
            'categories' => [
                'parent' => Category::whereNull('parent_id')->get(),
                'sub' => Category::whereNotNull('parent_id')->get()
            ],
            'priorities' => Priority::all(),
        ];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.modal-show-request');
    }
}
