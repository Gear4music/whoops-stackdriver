<?php

namespace WhoopsStackdriver\Exceptions;

use Throwable;

interface ExceptionWithContext
{
    /** @return array */
    public function getContext() : array;

    /**
     * @param Throwable $throwable
     * @return ExceptionWithContext
     */
    public static function wrap(Throwable $throwable) : ExceptionWithContext;
}
