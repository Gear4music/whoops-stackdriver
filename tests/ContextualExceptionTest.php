<?php

namespace WhoopsStackdriver\Tests;

use PHPUnit\Framework\TestCase;
use WhoopsStackdriver\Exceptions\WebRequestException;

class ContextualExceptionTest extends TestCase
{
    public function testAddContext()
    {
        $exception = new WebRequestException();
        $exception->setServiceName('test-service');
        $exception->setServiceVersion('v1.0.0');
        $exception->addContext('test', true);
        $exception->addContext('test2', 'test');

        $this->assertArrayHasKey('test', $exception->getContext());
        $this->assertArrayHasKey('test2', $exception->getContext());
        $this->assertEquals(true, $exception->getContext()['test']);
        $this->assertEquals('test', $exception->getContext()['test2']);
        $this->assertEquals('test-service', $exception->getServiceName());
        $this->assertEquals('v1.0.0', $exception->getServiceVersion());
    }
}