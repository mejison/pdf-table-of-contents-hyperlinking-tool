<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\MessageBag;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function addErrorMessage($key, $message) {
        $errors = session('errors', new ViewErrorBag);
        $exeption = $errors->getBags()['default'] ?? new MessageBag;
        $exeption->add($key, $message);
        $errors->put('default', $exeption);
        return $errors;
    }
}
