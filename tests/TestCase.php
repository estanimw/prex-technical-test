<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function getRoute($routeName, $data)
    {
        return $this->withoutMiddleware(\LogUserInteraction::class)
            ->get(route($routeName), $data);
    }

    protected function postRoute($routeName, $data)
    {
        return $this->withoutMiddleware(\LogUserInteraction::class)
            ->post(route($routeName), $data);
    }
}
