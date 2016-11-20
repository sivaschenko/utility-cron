<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Month extends AbstractPart
{
    const NAME = 'month';
    const PREFIX = 'in';

    const MIN_INT_VALUE = 1;
    const MAX_INT_VALUE = 12;

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        $date = \DateTime::createFromFormat('M', $this->value);
        if ($date) {
            return $date->format('F');
        }

        $number = $this->value - 1;
        $string = date('F', strtotime("January + $number Months"));

        return $string ? $string : $this->value;
    }

    /**
     * @return string[]
     */
    protected function getAllowedStringValues()
    {
        $allowedValues = [];
        // Foreach int values get days

        foreach (range(static::MIN_INT_VALUE, static::MAX_INT_VALUE) as $number) {
            $allowedValues[] = strtolower(date('M', strtotime("January + $number Months")));
        }

        return $allowedValues;
    }
}
