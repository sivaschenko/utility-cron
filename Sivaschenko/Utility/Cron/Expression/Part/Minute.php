<?php
/**
 * Copyright © 2016, Sergii Ivashchenko
 * See LICENSE for license details.
 */
namespace Sivaschenko\Utility\Cron\Expression\Part;

class Minute extends AbstractPart
{
    const NAME = 'minute';
    const PREFIX = 'at';
    const SUFFIX = 'minute';

    const MIN_INT_VALUE = 0;
    const MAX_INT_VALUE = 59;

    /**
     * @return string
     */
    protected function _getVerbalString()
    {
        return $this->getValueWithOrdinalSuffix();
    }
}
