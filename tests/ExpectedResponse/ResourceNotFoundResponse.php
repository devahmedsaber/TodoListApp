<?php

namespace Tests\ExpectedResponse;

class ResourceNotFoundResponse extends ExpectedResponse
{
    public function __construct()
    {
        parent::__construct(404, [], [], false, false, '');
    }
}
