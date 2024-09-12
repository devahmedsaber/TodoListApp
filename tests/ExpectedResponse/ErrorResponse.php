<?php

namespace Tests\ExpectedResponse;

class ErrorResponse extends ExpectedResponse
{
    public function __construct(int $code, string|int $subCode = '', string $message = '', string $entry = 'data', array $data = [], bool $strict = true)
    {
        $response = [
            'status' => false,
            'error_code' => $subCode,
            'message' => $message,
            $entry => $data,
        ];
        parent::__construct($code, $response, [], $strict, false, '');
    }
}
