<?php

namespace Alifuz\Polylog\Middlewares;

use Alifuz\Polylog\DTO\LogData;
use Alifuz\Polylog\Helpers\DataScrubber;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise as P;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\TransferStats;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class GuzzleLogMiddleware
{
    private array $rules;
    private string $level;
    private LogData $logData;
    private LoggerInterface $logger;
    private Carbon $startDateTime;
    private ?string $channel_name;

    private ?string $driver_name;

    /**
     * Middleware that logs requests, responses, and errors using a message formatter.
     *
     * @param string|null $channel_name - channel from config channels list or on-demand
     * @param array|null $rules - specify rule from data scrubbing rules list
     */
    public function __construct(
        ?string $channel_name = null,
        ?array $rules = null,
        ?string $driver_name = 'mongo',
    ) {
        $this->channel_name = $channel_name;
        $this->driver_name = $driver_name;
        $this->logger = $this->getLoggerInstance();
        $this->rules = $rules ?? config('polylog.rules.default');
        $this->startDateTime = Carbon::now()->timezone(config('app.timezone'));
        $this->logData = new LogData;
        $this->logData->type = 'guzzle_log';
    }

    public function __invoke(callable $handler): callable
    {
        /*
         * If http logging state is off then just continue request as usual without logging
         * otherwise log request and response
         */
        if (!config('polylog.http_logging', false)) {
            return $handler;
        }

        return function (RequestInterface $request, array $options) use ($handler): PromiseInterface {
            if (isset($options['on_stats']) && config('polylog.write_os_stats')) {
                $options['on_stats'] = function (TransferStats $stats) {
//                    $this->logStats = $stats;
                };
            }

            return $handler($request, $options)
                ->then(
                    $this->handleSuccess($request),
                    $this->handleFailure($request)
                );
        };
    }

    /**
     * This method will write log in case of 2xx http results.
     *
     * @param RequestInterface $request
     * @return callable
     */
    private function handleSuccess(
        RequestInterface $request,
    ): callable
    {
        return function (ResponseInterface $response) use ($request) {
            $this->level = 'info';
            $this->writeLog($request, $response);

            return $response;
        };
    }

    /**
     * This method will handle 4xx-5xx http results.
     *
     * @param RequestInterface $request
     * @return callable
     */
    private function handleFailure(
        RequestInterface $request,
    ): callable
    {
        return function (\Throwable $reason) use ($request) {
            $response = $reason instanceof RequestException ? $reason->getResponse() : null;
            $this->level = 'error';
            $this->writeLog($request, $response, $reason);

            return P\Create::rejectionFor($reason);
        };
    }

    /**
     * This is a main stuff.
     *
     *
     * @param RequestInterface $request
     * @param ResponseInterface|null $response
     * @param mixed $reason
     * @return void
     */
    private function writeLog(
        RequestInterface $request,
        ResponseInterface $response = null,
        mixed $reason = null,
    ): void {
        $requestBody = json_decode($request->getBody(), true);
        $filteredRequestBody = DataScrubber::filter($requestBody, $this->rules) ?? $request->getBody()->getContents();

        $startDateTime = $this->startDateTime;
        $endDateTime = Carbon::now()->timezone(config('app.timezone'));
        $spentMs = $endDateTime->diffInMilliseconds($startDateTime);
        $this->logData->app_name = config('polylog.app_name');
        $this->logData->chanel = $this->channel_name;
        $this->logData->request_headers = json_encode($this->getHeadersArray($request));
        $this->logData->request_method = $request->getMethod();
        $this->logData->route = $request->getUri()->__toString();
        $this->logData->request_body = !empty($filteredRequestBody) ? json_encode($filteredRequestBody) : null;

        $this->logData->started_at = $startDateTime->format('Y-m-d\TH:i:s.uP');
        $this->logData->finished_at = $endDateTime->format('Y-m-d\TH:i:s.uP');
        $this->logData->spent_ms = $spentMs;

        $this->logData->response_status = 0;
        $this->logData->response_body = null;

        if ($response) {
            $responseBody = json_decode($response->getBody(), true);
            $filteredResponseBody = DataScrubber::filter($responseBody, $this->rules) ?? $response->getBody()->getContents();

            $this->logData->response_status = $response->getStatusCode();
            $this->logData->response_body = !empty($filteredResponseBody) ? json_encode($filteredResponseBody) : null;
        }

        if ($reason instanceof RequestException || $reason instanceof ConnectException) {
            $this->logData->response_status = $reason->getCode();
            $this->logData->response_body = $reason->getMessage();
        }

        $this->logger->log(
            $this->level,
            'http_logging',
            $this->logData->toArray()
        );
    }

    private function getLoggerInstance(): LoggerInterface
    {
        $log_driver = config('polylog.log_driver');
        $config = $log_driver[$this->driver_name] ?? null;

        if (is_null($config)) {
            throw new InvalidArgumentException('Set driver for logging');
        }

        if ($this->driver_name == 'mongo') {
            $config['with']['collection'] = $this->channel_name;
        }

        return Log::build($config);
    }

    /**
     * @param RequestInterface $request
     * @return array
     */
    public function getHeadersArray(RequestInterface $request): array
    {
        $forbiddenHeaders = config('polylog.forbidden_headers');
        $headers = $request->getHeaders();
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
