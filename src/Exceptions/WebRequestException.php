<?php

namespace WhoopsStackdriver\Exceptions;

use Exception;
use Throwable;

class WebRequestException extends Exception implements ExceptionWithContext
{
    /** @var string */
    private $serviceName;

    /** @var string */
    private $serviceVersion;

    /** @var array */
    private $context = [];

    /** @return string */
    public function getServiceName() : string
    {
        return $this->serviceName ?? '';
    }

    /**
     * @param string $serviceName
     * @return self
     */
    public function setServiceName(string $serviceName) : self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    /** @return string */
    public function getServiceVersion() : string
    {
        return $this->serviceVersion ?? '';
    }

    /**
     * @param string $serviceVersion
     * @return self
     */
    public function setServiceVersion(string $serviceVersion) : self
    {
        $this->serviceVersion = $serviceVersion;
        return $this;
    }

    /** @return array */
    public function getContext() : array
    {
        return $this->context;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function addContext(string $key, $value) : self
    {
        $this->context[$key] = $value;
        return $this;
    }

    /**
     * @param array $context;
     * @return self
     */
    public function setContext(array $context) : self
    {
        $this->context = $context;
        return $this;
    }

    /**
     * @param Throwable $throwable
     * @return static
     */
    public static function wrap(Throwable $throwable) : self
    {
        return new static($throwable->getMessage(), $throwable->getCode(), $throwable);
    }
}
