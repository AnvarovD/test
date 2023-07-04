<?php

namespace Alifuz\Polylog\Formatters;

use Monolog\Formatter\NormalizerFormatter;

class LogstashFormatter extends NormalizerFormatter
{
    /**
     * @var string an application name for the Logstash log message, used to fill the `app_name` field
     */
    protected string $appName;

    /**
     * @var string an application environment for the Logstash log message, used to fill the `app_environment` field
     */
    protected string $appEnvironment;

    /**
     * @var string the key for 'context' fields from the Monolog record
     */
    protected string $contextKey;

    /**
     * @param string|null $app_environment The application environment that sends the data
     * @param string|null $app_name The system/machine name, used as the "source" field of logstash, defaults to the hostname of the machine
     * @param string|null $context_key The key for context keys inside logstash "fields", defaults to context
     */
    public function __construct(
        ?string $app_environment = null,
        ?string $app_name = null,
        ?string $context_key = ''
    ) {
        // logstash requires a ISO 8601 format date with optional millisecond precision.
        parent::__construct('Y-m-d\TH:i:s.uP');

        $this->appName = $app_name ?? (string) gethostname();
        $this->appEnvironment = $app_environment ?? 'undefined_environment';
        $this->contextKey = $context_key ?? 'context_json';
    }

    /**
     * @inheritDoc
     */
    public function format(array $record): array
    {
        $record = parent::format($record);
        $message = [];

        if (isset($record['message'])) {
            $message['message'] = $record['message'];
        }

        if (!empty($record['context'])) {
            $message[$this->contextKey] = $this->toJson($record['context']) . "\n";
        }

        if (isset($record['channel'])) {
            $message['channel'] = $record['channel'];
        }

        if (isset($record['level'])) {
            $message['level'] = $record['level'];
        }

        if (isset($record['level_name'])) {
            $message['level_name'] = $record['level_name'];
        }

        $message['app_name'] = $this->appName;
        $message['app_environment'] = $this->appEnvironment;

        $message['datetime'] = $record['datetime'];

        return $message;
    }
}
