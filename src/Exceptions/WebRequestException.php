<?php

namespace WhoopsStackdriver\Exceptions;

use Exception;
use Throwable;

class WebRequestException extends Exception implements ExceptionWithContext
{
    /** @var array */
    private $context = [];

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
     * @return ExceptionWithContext
     */
    public static function wrap(Throwable $throwable) : ExceptionWithContext
    {
        return new static($throwable->getMessage(), $throwable->getCode(), $throwable);
    }
}
