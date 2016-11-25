<?php
/**
 * Crafted with ♥ for developers.
 *
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Config
{
    /**
     * @var array
     */
    private $data = [
        'minute' => [
            'min'            => 0,
            'max'            => 59,
            'values'         => [],
            'prefix'         => 'at',
            'suffix'         => 'minute',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
            ],
            'suffixes' => [],
        ],
        'hour' => [
            'min'            => 0,
            'max'            => 23,
            'values'         => [],
            'prefix'         => 'at',
            'suffix'         => 'hour',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
            ],
            'suffixes' => [],
        ],
        'day of month' => [
            'min'            => 1,
            'max'            => 31,
            'values'         => [],
            'prefix'         => 'on',
            'suffix'         => 'day of month',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
                'W' => 'weekdays',
                'L' => 'last',
            ],
            'suffixes' => [
                'W' => 'closest weekday to %s',
            ],
        ],
        'month' => [
            'min'            => 1,
            'max'            => 12,
            'values'         => ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'],
            'prefix'         => 'in',
            'suffix'         => '',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
            ],
            'suffixes' => [],
        ],
        'day of week' => [
            'min'            => 0,
            'max'            => 7,
            'values'         => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'],
            'prefix'         => 'on',
            'suffix'         => '',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
                'L' => 'Sunday',
            ],
            'suffixes' => [
                'L' => 'last %s of the month',
            ],
        ],
        'year' => [
            'min'            => 1970,
            'max'            => 2099,
            'values'         => [],
            'prefix'         => 'in',
            'suffix'         => '',
            'special_values' => [
                '*' => 'every',
                '?' => 'any',
            ],
            'suffixes' => [],
        ],
    ];

    /**
     * @param string $name
     * @param string $key
     *
     * @return int|string|\string[]|null
     */
    public function getData($name, $key)
    {
        return isset($this->data[$name][$key]) ? $this->data[$name][$key] : null;
    }
}
