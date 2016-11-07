<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Day extends AbstractPart
{
    const NAME = 'day of month';
    const PREFIX = 'on';
    const SUFFIX = 'day of month';

    const MIN_INT_VALUE = 1;
    const MAX_INT_VALUE = 31;

    /**
     * @param string $value
     */
    public function __construct($value)
    {
        $this->replacements['W'] = 'weekdays';
        $this->replacements['L'] = 'last';
        $this->valueSuffixes['W'] = 'closest weekday to %s';
        parent::__construct($value);
    }

    /**
     * @return string
     */
    public function getSuffix()
    {
        return ('W' == $this->value) ? '' : parent::getSuffix();
    }

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        return $this->getValueWithOrdinalSuffix();
    }
}
