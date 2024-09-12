<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public static function getPaginationAttributes($currentPage, $from, $skipped, $perPage, $to, $total): array
    {
        return [
            'current_page' => $currentPage,
            'from' => $from,
            'last_page' => $skipped,
            'per_page' => $perPage,
            'to' => $to,
            'total' => $total,
        ];
    }
}
