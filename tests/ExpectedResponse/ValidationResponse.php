<?php

namespace Tests\ExpectedResponse;

class ValidationResponse extends ExpectedResponse
{
    public function __construct(int $code, array $data = [], array $structure = [])
    {
        parent::__construct($code, $data, $structure, false, false, 'errors');
    }
}
