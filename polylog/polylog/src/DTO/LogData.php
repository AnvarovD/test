<?php

namespace Alifuz\Polylog\DTO;

class LogData
{
    public string $request_method;
    public string $route;
    public mixed $request_body;
    public string $started_at;
    public string $finished_at;
    public int $spent_ms;
    public int $response_status;
    public mixed $response_body;
    public ?array $logStats;
    public ?string $app_name;
    public ?string $chanel;

    public ?array $debug_info;
    public mixed $request_headers;

    public string $type;

    public function toArray(): array
    {
        return [
            'request_method' => $this->request_method,
            'route' => $this->route,
            'request_body' => $this->request_body,
            'started_at' => $this->started_at,
            'finished_at' => $this->finished_at,
            'spent_ms' => $this->spent_ms,
            'response_status' => $this->response_status,
            'response_body' => $this->response_body,
            'debug_info' => $this->debug_info ?? null,
            'app_name' => $this->app_name,
            'chanel' => $this->chanel,
            'type' => $this->type,
            'request_headers' => $this->request_headers,
        ];
    }
}
