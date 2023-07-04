<?php

namespace Alifuz\Polylog\Helpers;

class DataScrubber
{
    /**
     * Filters sensitive data to prevent it to be logged.
     *
     * @param array|mixed $dataToFilter
     * @param array $rules - data scrubbing rule name
     *
     * @return mixed
     */
    public static function filter(mixed $dataToFilter = [], array $rules = []): mixed
    {
        if (!empty($dataToFilter)) {
            array_walk_recursive($dataToFilter, function (&$value, $key) use ($rules) {
                if (in_array($key, $rules)) {
                    $value = '[FILTERED]';
                }
            });
        }

        return $dataToFilter;
    }
}
