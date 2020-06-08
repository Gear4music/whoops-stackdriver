<?php

namespace WhoopsStackdriver;

use Whoops\Handler\Handler;

class StackdriverHandler extends Handler
{
    /** @var resource */
    private $outputHandle;

    /** @var bool */
    private $shouldCloseHandle = false;

    /** @var array */
    private $serviceContext = [];

    /**
     * @param resource $outputHandle
     */
    public function __construct(array $serviceContext = [], $outputHandle = null)
    {
        // If we don't have a specific output handle, write directly to stderr.
        if (is_null($outputHandle)) {
            $outputHandle = fopen('php://stderr', 'w+');
            $this->shouldCloseHandle = true;
        }

        $this->serviceContext = $serviceContext;
        $this->outputHandle = $outputHandle;
    }

    /**
     * If we created the output handle we should also close it.
     */
    public function __destruct()
    {
        if ($this->shouldCloseHandle) {
            fclose($this->outputHandle);
        }
    }

    /**
     * @return int
     */
    public function handle() : int
    {        
        $formatter = new ExceptionFormatter($this->getException(), $this->serviceContext);
        fwrite($this->outputHandle, $formatter->toJson() . PHP_EOL);
        return Handler::DONE;
    }
}
