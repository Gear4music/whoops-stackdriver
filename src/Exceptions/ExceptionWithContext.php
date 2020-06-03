<?php

namespace WhoopsStackdriver\Exceptions;

use Throwable;

interface ExceptionWithContext
{
    /** @return string */
    public function getServiceName() : string;

    /** @return string */
    public function getServiceVersion() : string;

    /** @return array */
    public function getContext() : array;

    /**
     * @param Throwable $throwable
     * @return static
     */
    public static function wrap(Throwable $throwable) : self;
}
