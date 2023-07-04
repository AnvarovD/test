<?php

/*
 * This file is modified part from Monolog package
 * MongoDB\Driver\Manager support was removed
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 */

namespace Alifuz\Polylog\Handlers;

use Exception;
use MongoDB\Client;
use MongoDB\Collection;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\MongoDBFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

/**
 * Logs to a MongoDB database.
 *
 * Usage example:
 *
 *   $log = new \Monolog\Logger('application');
 *   $client = new \MongoDB\Client('mongodb://localhost:27017');
 *   $mongodb = new \Monolog\Handler\MongoDBHandler($client, 'logs', 'prod');
 *   $log->pushHandler($mongodb);
 *
 * The above examples uses the MongoDB PHP library's client class;
 */
class MongoDBHandler extends AbstractProcessingHandler
{
    /** @var Collection|null */
    private ?Collection $collection;

    /**
     * Constructor.
     *
     * @param string         $mongodb    URI
     * @param string         $database   Database name
     * @param string         $collection Collection name
     */
    public function __construct(string $mongodb, string $database, string $collection, $level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);

        if (!extension_loaded('mongodb')) {
            return;
        }

        try {
            $mongodb = new Client($mongodb);
            $this->collection = $mongodb->selectCollection($database, $collection);
        } catch (Exception) {
            $this->collection = null;

            return;
        }
    }

    protected function write(array $record): void
    {
        $this->collection?->insertOne($record['formatted']);
    }

    /**
     * @inheritDoc
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new MongoDBFormatter();
    }
}
