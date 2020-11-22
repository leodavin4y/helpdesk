<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Override validate method use dingo validation exception
     *
     * @param Request $request
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return array|\Illuminate\Support\MessageBag
     */
    public function validate(
        Request $request,
        array $rules,
        array $messages = [],
        array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()
            ->make(
                $request->all(),
                $rules, $messages,
                $customAttributes
            );

        return $validator->errors();
    }
}
