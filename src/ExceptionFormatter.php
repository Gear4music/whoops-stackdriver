<?php

namespace WhoopsStackdriver;

use Throwable;
use WhoopsStackdriver\Exceptions\ExceptionWithContext;

class ExceptionFormatter
{
    /** @var Throwable */
    private $throwable;

    /** @var array */
    private $serviceContext;

    /**
     * @param Throwable $throwable
     * @param array $serviceContext
     */
    public function __construct(Throwable $throwable, array $serviceContext = [])
    {
        $this->throwable = $throwable;
        $this->serviceContext = $serviceContext;
    }

    /**
     * Create an approriately formatted log message for a Google Stackdriver Reported Error Event.
     * @link https://cloud.google.com/error-reporting/docs/formatting-error-messages#json_representation
     * @return array
     */
    public function toArray() : array
    {
        $rtn = [
            '@type' => 'type.googleapis.com/google.devtools.clouderrorreporting.v1beta1.ReportedErrorEvent',
            'eventTime' => (new \DateTime())->format(\DateTimeInterface::RFC3339),
            'serviceContext' => $this->serviceContext,
            'context' => [],
            'severity' => 'ERROR',
        ];

        if (defined('GCP_TRACE_ID')) {
            $rtn['logging.googleapis.com/trace'] = GCP_TRACE_ID;
        }

        // Set initial context:
        if ($this->throwable instanceof ExceptionWithContext) {
            $rtn['context'] = $this->throwable->getContext();
        }

        // Format the exception message in such a way that Google will identify it as an error and handle it properly.
        $rtn['message'] = sprintf('PHP Warning: %s', (string)$this->throwable);

        // Add process ID:
        $rtn['context']['pid'] = getmypid();

        // Add exception report details:
        $rtn['context']['reportLocation'] = [
            'filePath' => $this->throwable->getFile(),
            'lineNumber' => $this->throwable->getLine(),
            'functionName' => $this->getFunctionNameForReport($this->throwable->getTrace()),
        ];

        // HTTP request specific context:
        if (php_sapi_name() !== 'cli') {
            // Add HTTP request details to the context array:
            $rtn['context']['httpRequest']['method'] = $_SERVER['REQUEST_METHOD'] ?? 'Unknown';
            $rtn['context']['httpRequest']['url'] = $_SERVER['REQUEST_URI'] ?? 'Unknown';
            $rtn['context']['httpRequest']['userAgent'] = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
            $rtn['context']['httpRequest']['remoteIp'] = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';

            // Add session details to the context array:
            if (PHP_SESSION_ACTIVE === session_status()) {
                $rtn['context']['httpRequest']['sessionId'] = session_id();
            }
        }

        return $rtn;
    }

    /**
     * @return string
     */
    public function toJson() : string
    {
        return json_encode($this->toArray());
    }

    /**
     * Extract the function name from this exception's stack trace. 
     * Borrowed from the Google Cloud API PHP library. See link below.
     * @param array|null $trace
     * @link https://github.com/googleapis/google-cloud-php/blob/3dc62b4a2b5c098ee36dba09d2fa63bf1e5d8a92/ErrorReporting/src/Bootstrap.php#L254
     */
    private function getFunctionNameForReport(array $trace = null) {
        if (null === $trace) {
            return '<unknown function>';
        }
        
        if (empty($trace[0]['function'])) {
            return '<none>';
        }

        $functionName = [$trace[0]['function']];

        if (isset($trace[0]['type'])) {
            $functionName[] = $trace[0]['type'];
        }

        if (isset($trace[0]['class'])) {
            $functionName[] = $trace[0]['class'];
        }

        return implode('', array_reverse($functionName));
    }
}
