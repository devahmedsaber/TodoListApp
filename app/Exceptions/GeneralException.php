<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Exception;

class GeneralException extends Exception
{
    use ApiResponse;

    protected $message;

    protected $code;

    public function __construct($message = null, $code = null)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        return $this->error($this->message ?? __('app.general_error'), $this->code ?? 500);
    }
}
