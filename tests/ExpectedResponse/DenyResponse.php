<?php

namespace Tests\ExpectedResponse;

class DenyResponse extends ErrorResponse
{
    public function __construct(string $code = '')
    {
        parent::__construct(401, $code, __('Deny'));
    }
}
