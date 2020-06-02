<?php

namespace WhoopsStackdriver\Exceptions;

interface ExceptionWithContext
{
    /** @return string */
    public function getServiceName() : string;

    /** @return string */
    public function getServiceVersion() : string;

    /** @return array */
    public function getContext() : array;
}
