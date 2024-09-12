<?php

namespace Tests\ExpectedResponse;

class ShowResponse extends ExpectedResponse
{
    public function __construct(array $data, array $structure)
    {
        parent::__construct(200, $data, $structure, false, false);
    }
}
