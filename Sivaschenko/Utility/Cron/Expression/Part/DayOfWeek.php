<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class DayOfWeek extends AbstractPart
{
    const NAME = 'day of week';
    const PREFIX = 'on';

    const MAX_INT_VALUE = 7;
    const MIN_INT_VALUE = 0;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->replacements['L'] = 'Sunday';
        $this->valueSuffixes['L'] = 'last %s of the month';
        parent::__construct($value);
    }

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        $date = \DateTime::createFromFormat('D', $this->value);
        if ($date) {
            return $date->format('l');
        }
        $string = date('l', strtotime("Sunday + $this->value Days"));

        return $string ? $string : $this->value;
    }

    protected function getAllowedStringValues()
    {
        $allowedValues = [];
        // Foreach int values get days

        foreach (range(static::MIN_INT_VALUE, static::MAX_INT_VALUE) as $number) {
            $allowedValues[] = strtolower(date('D', strtotime("Sunday + $number Days")));
        }

        return $allowedValues;
    }
}
