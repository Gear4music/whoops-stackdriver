<?php

namespace WhoopsStackdriver\Tests;

use PHPUnit\Framework\TestCase;
use WhoopsStackdriver\Exceptions\WebRequestException;

class ContextualExceptionTest extends TestCase
{
    public function testAddContext()
    {
        $exception = new WebRequestException();
        $exception->addContext('test', true);
        $exception->addContext('test2', 'test');

        $this->assertArrayHasKey('test', $exception->getContext());
        $this->assertArrayHasKey('test2', $exception->getContext());
        $this->assertEquals(true, $exception->getContext()['test']);
        $this->assertEquals('test', $exception->getContext()['test2']);
    }
}