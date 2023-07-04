<?php

namespace Alifuz\Polylog\Middlewares;

use Alifuz\Polylog\DTO\LogData;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class RouteLogMiddleware
{
    private LoggerInterface $logger;
    private LogData $logData;

    private ?string $channel_name;

    private ?string $driver_name;

    public function __construct()
    {
        $this->driver_name = config('polylog.route_log_driver');
        if ($this->driver_name == null) {
            throw new \InvalidArgumentException('set driver for route log');
        }
        $this->channel_name = Route::getCurrentRoute()->getPrefix();
        $this->logger = $this->getLoggerInstance();
        $this->logData = new LogData;
        $this->logData->type = 'route_log';
    }

    public function handle(Request $request, Closure $next): mixed
    {
        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        if (config('polylog.write_os_stats')) {
            $this->logData->logStats = [];
        } else {
            $this->logData->logStats = null;
        }

        $startedAt = Carbon::createFromTimestamp(LARAVEL_START, config('app.timezone'));
        $finishedAt = Carbon::createFromTimestamp(microtime(true), config('app.timezone'));

        $this->logData->chanel = $this->channel_name;
        $this->logData->app_name = config('polylog.app_name');

        $this->logData->request_method = $request->getMethod();
        $this->logData->route = $request->getRequestUri();
        $this->logData->started_at = $startedAt->format('Y-m-d\TH:i:s.uP');
        $this->logData->finished_at = $finishedAt->format('Y-m-d\TH:i:s.uP');
        $this->logData->spent_ms = $finishedAt->diffInMilliseconds($startedAt);
        $this->logData->response_status = $response->getStatusCode();
        $this->logData->request_body = !empty($request->all()) ? json_encode($request->all()) : null;
        $this->logData->response_body = ['response' => $response->getContent()];
        $this->logData->request_headers = json_encode($this->getHeadersArray($request->headers->all()));

        $this->logData->debug_info = [
            'route_static' => Route::getCurrentRoute()->getCompiled()->getStaticPrefix(),
            'route_regex' => Route::getCurrentRoute()->getCompiled()->getRegex(),
            'laravel_method' => Route::getCurrentRoute()->getAction()['controller'] ?? null,
        ];

        $this->logger->log('debug', 'route_logging', $this->logData->toArray());
    }

    private function getLoggerInstance(): LoggerInterface
    {
        $log_channels = config('polylog.log_driver');
        $config = $log_channels[$this->driver_name] ?? null;

        if (is_null($config)) {
            throw new InvalidArgumentException('Set driver for logging');
        }

        if ($this->driver_name == 'mongo') {
            $config['with']['collection'] = $this->channel_name;
        }

        return Log::build($config);
    }

    /**
     * @param array $headers
     * @return array
     */
    public function getHeadersArray(array $headers): array
    {
        $forbiddenHeaders = config('polylog.forbidden_headers');
        $headersArray = [];
        foreach ($headers as $header => $value) {
            if (!in_array($header, $forbiddenHeaders)) {
                if (count($value) > 1) {
                    $headersArray += [$header => implode(',', $value)];
                } else {
                    $headersArray += [$header => $value[0]];
                }
            }
        }

        return $headersArray;
    }
}
