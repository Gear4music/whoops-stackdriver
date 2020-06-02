<?php

namespace WhoopsStackdriver\Tests;

use PHPUnit\Framework\TestCase;
use WhoopsStackdriver\ExceptionFormatter;
use WhoopsStackdriver\Exceptions\WebRequestException;

class ExceptionFormatterTest extends TestCase
{
    public function testAddContext()
    {
        $exception = new WebRequestException();
        $exception->setServiceName('test-service');
        $exception->setServiceVersion('v1.0.0');
        $exception->addContext('test', true);
        $exception->addContext('test2', 'test');
        
        $formatter = new ExceptionFormatter($exception);
        $output = $formatter->toArray();

        $this->assertArrayHasKey('test', $output['context']);
        $this->assertArrayHasKey('test2', $output['context']);
        $this->assertEquals(true, $output['context']['test']);
        $this->assertEquals('test', $output['context']['test2']);
        $this->assertEquals('test-service', $output['serviceContext']['service']);
        $this->assertEquals('v1.0.0', $output['serviceContext']['version']);
    }

    public function testJsonFormatting()
    {
        $exception = new WebRequestException();
        $exception->setServiceName('test-service');
        $exception->setServiceVersion('v1.0.0');
        $exception->addContext('test', true);
        $exception->addContext('test2', 'test');
        
        $formatter = new ExceptionFormatter($exception);
        $output = $formatter->toArray();
        $json = $formatter->toJson();

        $this->assertEquals(json_encode($output), $json);
    }
}