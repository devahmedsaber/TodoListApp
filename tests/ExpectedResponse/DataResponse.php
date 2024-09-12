<?php

namespace Tests\ExpectedResponse;

class DataResponse extends ExpectedResponse
{
    public function __construct($data, $structure, $entry = 'data')
    {
        parent::__construct(200, $data, $structure, false, false, $entry);
    }
}
