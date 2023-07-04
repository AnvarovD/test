<?php

namespace Alifuz\Polylog\Handlers;

use Alifuz\Polylog\Formatters\LogstashFormatter;
use Exception;
use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class LogstashHandler extends AbstractProcessingHandler
{
    public string $host;
    public string $user;
    public string $password;

    public int $port;

    public function __construct(
        string $host,
        string $user,
        string $password,
        int $port,
        $level = Logger::DEBUG,
        bool $bubble = true
    ) {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->port = $port;

        parent::__construct($level, $bubble);
    }

    protected function write(array $record): void
    {

        try {
            $message = $record['formatted']['context_json'];
            $fp = fsockopen("localhost", 50000, $errno, $errstr, 30);
            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            } else {
                fwrite($fp, $message);
//        while (fgets($fp, 128)) {
//            echo fgets($fp, 128); // If you expect an answer
//        }
                fclose($fp); // To close the connection
            }
        } catch (Exception $exception) {
            dd($exception);
        }
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LogstashFormatter();
    }
}
