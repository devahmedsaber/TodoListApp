<?php

namespace Tests\ExpectedResponse;

class SuccessResponse extends ExpectedResponse
{
    public function __construct()
    {
        parent::__construct(200, [], [], false, false, '');
    }
}
