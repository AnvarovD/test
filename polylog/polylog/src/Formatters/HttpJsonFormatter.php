<?php

namespace Alifuz\Polylog\Formatters;

use Monolog\Formatter\JsonFormatter;

class HttpJsonFormatter extends JsonFormatter
{
    /**
     * @inheritdoc
     */
    public function format(array $record): string
    {
        $custom_record = [
            'ts'              => $record['datetime'],
            'message'         => $record['message'],
            'request_method'  => $record['context']['request_method'],
            'route_requested' => $record['context']['route_requested'],
            'request_params'  => $record['context']['request_params'],
            'started_at'      => $record['context']['started_at'],
            'finished_at'     => $record['context']['finished_at'],
            'spent_ms'        => $record['context']['spent_ms'],
            'response_status' => $record['context']['response_status'],
            'response_body'   => $record['context']['response_body'],
            'errors'          => $record['context']['errors'],
            'debug_info'      => $record['context']['debug_info'],
        ];

        $normalized = $this->normalize($custom_record);

        return $this->toJson($normalized, true) . ($this->appendNewline ? "\n" : '');
    }
}
