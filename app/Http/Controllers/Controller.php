<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function addErrorMessage($key, $message) {
        $errors = session('errors', new ViewErrorBag);
        $exeption = $errors->getBags()['default'] ?? new MessageBag;
        $exeption->add($key, $message);
        $errors->put('default', $exeption);
        return $errors;
    }
}
